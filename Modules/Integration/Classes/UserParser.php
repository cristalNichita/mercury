<?php


namespace Modules\Integration\Classes;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Integration\Entities\Document;
use Modules\Integration\Entities\Holding;
use Modules\Integration\Entities\User;
use Modules\Integration\Entities\UserCompany;
use Modules\Integration\Helpers\ArrHelper;
use Modules\Integration\Helpers\CreatedRow;
use Modules\Integration\Helpers\ExistRow;
use Modules\Integration\Helpers\UpdateRow;
use Modules\Integration\Interfaces\IntegrationFileInterface;
use Modules\Integration\Traits\IntegrationTrait;
use Spatie\MediaLibrary\HasMedia;

class UserParser extends AbstractParser
{

    protected $filesystem_local_path_key = 'users';
    public $companies;
    public $extensions = ['.doc', '.docx', '.xls', '.xlsx', '.pdf'];
    public $holding_data;
    public $holding_company;
    public $users;
    public $holding_users;

    public function __construct($status = 'new', string $file = '')
    {
        $this->companies = collect([]);
        $this->users = collect([]);
        $this->holding_data = new Collection([]);
        parent::__construct($status, $file);
    }

    protected function importRows(Collection $file)
    {

        $partners = $file->get('Партнер');
        foreach ($partners as &$partner) {

            try {

                $this->holding_data = $partner;
                $this->holding_company = null;
                $this->companies = new Collection([]);
                $this->holding_users = new Collection([]);
                $this->importHoldingCompany();
                $this->importCompanies();
                $this->importUsers();

                $this->users = $this->users->merge($this->holding_users);
                $this->holding_company->users()->sync($this->holding_users->pluck('id'));


            } catch(\ErrorException | \Throwable $e) {
                $this->addLog("Ошибка с данными:\n{$e->getMessage()}");
                $this->addLog("Стэк ошибки:\n{$e->getTraceAsString()}");
                $json_data = json_encode($partner);
                $this->addLog("Данные на вход:\n{$json_data}");
            }
        }

    }

    protected function importRow(array $data)
    {
    }

    protected function importCompany($data)
    {

        $for_update = $data;
        $for_update['company_holding_id'] = $this->holding_company->id ?? null;


        if (!empty($data["КонтактнаяИнформацияЮЛ"])) {

            $info = ($data['КонтактнаяИнформацияЮЛ'])->get('СтрокаКИ', new Collection([]));

            if (isset($info['Тип'])) {
                $info = new Collection([$info]);
            }

            foreach ($info as $row) {
                $for_update[$row['Вид']] = $row['Значение'];
            }
        }

        $prepare_data = UserCompany::prepareData($for_update);
        $inner_company = UserCompany::fillFromParser($prepare_data);

        if (!$inner_company) {
            $inner_company = UserCompany::create($prepare_data);
            return new CreatedRow($inner_company);
        }

        $inner_company->fill($prepare_data);

        if ($inner_company->isDirty()) {
            $inner_company->save();
            return new UpdateRow($inner_company);
        }

        return new ExistRow($inner_company);


    }

    protected function importUsers()
    {

        $created = [];
        $updated = [];
        $exist = [];
        $deleted = [];

        if (($this->holding_data['КонтактныеЛица'])->isEmpty()) {
            return;
        }

        $users = ($this->holding_data['КонтактныеЛица'])->get('КонтактноеЛицо', new Collection([]));

        if (isset($users['GUID'])) {
            $users = new Collection([$users]);
        }

        foreach ($users as $user) {

            $result = $this->importUser($user);
            $model = $result->model;
            $this->holding_users[$model->integration_code] = $model;
            if ($result instanceof ExistRow) {
                $exist[] = $model->integration_code;
            } elseif ($result instanceof UpdateRow) {

                $updated[] = $model->integration_code;
            } elseif ($result instanceof CreatedRow) {
                $created[] = $model->integration_code;
            }

        }

        $this->addCheckLog('Создано пользователей', $created);
        $this->addCheckLog('Обновлено пользователей', $updated);
        $this->addCheckLog('Пользователей без изменений', $exist);
        $this->addCheckLog('Удалено пользователей', $deleted);
    }


    protected function importUser(array $data)
    {
        $for_update = $data;

        if (!empty($data["КонтактнаяИнформацияКЛ"])) {

            $info = ($data['КонтактнаяИнформацияКЛ'])->get('СтрокаКИ', new Collection([]));

            if (isset($info['Тип'])) {
                $info = new Collection([$info]);
            }

            foreach ($info as $row) {
                $for_update[$row['Вид']] = $row['Значение'];
            }
        }

        $prepare_data = User::prepareData($for_update);
        $user = $this->users->get($prepare_data[ User::integrationKey() ]);

        if(!$user) {
            $user = User::findByCodes($data) ?? new User();
        }

        $user->fill($prepare_data);

        if(!$user->isDirty()) {
            return new ExistRow($user);
        }

        elseif (empty($user->id)) {
            $user->password = Str::random(16);
            $user->save();
            return new CreatedRow($user);
        }

        elseif ($user->isDirty()) {
            $user->save();
            return new UpdateRow($user);
        }
    }

    protected function importHoldingCompany()
    {

        $prepare_data = Holding::prepareData($this->holding_data);
        $this->holding_company = Holding::fillFromParser($prepare_data);
    }

    protected function importDocuments(array $data, $model)
    {

        $created = [];
        $updated = [];
        $exist = [];
        $deleted = [];

        $new_documents = ($data["Договоры"])->get('Договор', new Collection([]));

        if (isset($new_documents['GUID'])) {
            $new_documents = new Collection([$new_documents]);
        }

        $documents =  Document::where([
            'documentable_type' => get_class($model),
            'documentable_id' => $model->id,

        ])->get()->keyBy(Document::integrationKey());

        foreach ($new_documents as $new_document) {

            $result = $this->importDocument($new_document, $documents, $model);

            if ($result instanceof ExistRow) {
                $exist[] = $result->model->integration_code;
            } elseif ($result instanceof UpdateRow) {

                $updated[] = $result->model->integration_code;
            } elseif ($result instanceof CreatedRow) {
                $created[] = $result->model->integration_code;
            }
        }

        $this->addCheckLog('Создано документов', $created);
        $this->addCheckLog('Обновлено документов', $updated);
        $this->addCheckLog('Документов без изменений', $exist);
        $this->addCheckLog('Удалено документов', $deleted);


    }


    protected function importDocument(array $data, $exist_documents, $model)
    {
        $result = null;
        $prepare_data = Document::prepareData($data);

        $prepare_data['documentable_type'] = get_class($model);
        $prepare_data['documentable_id'] = $model->id;

        $doc = $exist_documents->get($prepare_data[Document::integrationKey()]);

        if (!$doc) {
            $doc = Document::create($prepare_data);
            $result = new CreatedRow($doc);
        } else {

            $doc->fill($prepare_data);
            if ($doc->isDirty()) {
                $doc->save();
                $result = new UpdateRow($doc);
            } else {
                $result = new ExistRow($doc);
            }

        }


        $this->updateFiles($doc, $doc->integration_code, $this->extensions);

        return $result;
    }


    public function importCompanies()
    {

        if (empty($this->holding_data['Контрагенты'])) {
            return;
        }

        $companies = ($this->holding_data["Контрагенты"])->get('Контрагент', new Collection([]));

        if (isset($companies['Наименование'])) {
            $companies = new Collection([$companies]);
        }

        $created = [];
        $updated = [];
        $exist = [];
        $deleted = [];

        foreach ($companies as $company) {

            $result = $this->importCompany($company);

            if ($result instanceof ExistRow) {
                $exist[] = $result->model->integration_code;
            } elseif ($result instanceof UpdateRow) {

                $updated[] = $result->model->integration_code;
            } elseif ($result instanceof CreatedRow) {
                $created[] = $result->model->integration_code;
            }

            $this->companies[$result->model->integration_code] = $result->model;

            $this->importDocuments($company, $result->model);

        }

        $this->addCheckLog('Создано компаний', $created);
        $this->addCheckLog('Обновлено компаний', $updated);
        $this->addCheckLog('Компаний без изменений', $exist);
        $this->addCheckLog('Удалено компаний', $deleted);

    }

    /**
     * Для того чтобы писать на каждую строку только нужные логи
     * @param string $mess
     * @param array $arr
     */
    protected function addCheckLog(string $mess = '', array $arr = []) {

        if(!empty($arr)) {
            $this->addRowsToLog($mess, $arr);
        }

    }


}
