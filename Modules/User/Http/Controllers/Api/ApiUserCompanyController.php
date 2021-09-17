<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\User\Entities\Company;
use Modules\User\Entities\Contact;
use Modules\User\Events\CompanyCreated;
use Modules\User\Events\CompanyUpdated;
use Modules\User\Http\Requests\ApiUserCompanyRequest;


/**
 * Class ApiUserCompanyController
 * @package Modules\User\Http\Controllers\Api
 */
class ApiUserCompanyController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        /** @var Contact $contact */
        $contact = $request->user()->contact;

        return $contact->holding->companies()
            ->with(['params', 'bankRequisites'])
            ->get();
    }

    /**
     * @param ApiUserCompanyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ApiUserCompanyRequest $request)
    {
        /** @var Contact $contact */
        $contact = $request->user()->contact;

        try {

            $data = $request->only([
                'name', 'inn', 'kpp', 'ogrn'
            ]);

            $data['type'] = 1;
            $data['type_1c'] = 'Юридическое лицо';
            $data['holding_id'] = $contact->holding_id;

            $company = Company::create($data);

            $data_contacts = $request->only(['phone', 'email', 'u_address', 'f_address']);
            $company->fill($data_contacts);
            $company->save();

            // Для выгрузки в 1С
            event(new CompanyCreated($company));

            return $company->load('params');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }


    /**
     * @param Request $request
     * @param Company $company
     * @throws \Exception
     */
    protected function checkAccess(Request $request, Company $company)
    {
        //TODO: Определить почему у пользователей получаются одинаковые контакты
        // TODO: нужно добавить проверку еще на возможность редактирвоать только
        // TODO: приглашенных тобой пользователей
        // Проверяем принадлежность пользователю к холдингу
        if ($company->holding->id !== $request->user()->contact->holding->id) {
            throw new \Exception('Forbidden', 403);
        }
    }

    /**
     * @param Request $request
     * @param Company $company
     * @return bool[]|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Company $company)
    {
        $this->checkAccess($request, $company);

        if ($company->orders->count()) {
            return $this->sendError('order_exist');
        }

        $company->params()->delete();
        $company->delete();

        return ['success'=>true];
    }

    /**
     * @param Request $request
     * @param Company $company
     * @return Company
     * @throws \Exception
     */
    public function update(Request $request, Company $company)
    {
        $this->checkAccess($request, $company);
        $company->update($request->only([
            'name', 'inn', 'kpp', 'ogrn'
        ]));

        $data_contacts = $request->only(['phone', 'email', 'u_address', 'f_address']);
        $company->fill($data_contacts);

        $company->save();

        // Для выгрузки в 1С
        event(new CompanyUpdated($company));

        return $company->load('params');
    }

}
