<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Entities\BankRequisites;
use Modules\User\Entities\Company;
use Modules\User\Events\BankRequisiteCreated;
use Modules\User\Events\BankRequisiteUpdated;
use Modules\User\Http\Requests\ApiUserCompanyBankRequisites;

class ApiUserCompanyBankRequisitesController extends BaseController
{
    public function index(Request $request, Company $company)
    {
        $this->checkAccess($request, $company);

        return $company->bankRequisites;
    }

    /**
     * Store a newly created resource in storage.
     * @param ApiUserCompanyBankRequisites $request
     * @return Response
     */
    public function store(ApiUserCompanyBankRequisites $request, Company $company)
    {
        $this->checkAccess($request, $company);

        $data_create = $request->only(['bank_name', 'bik', 'invoice', 'kor']);
        $data_create['name'] = array_shift($data_create);

        try {
            $company->bankRequisites()->create($data_create);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        // Для выгрузки в 1С
        event(new BankRequisiteCreated($company));

        return $company->bankRequisites;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, Company $company, BankRequisites $requisites)
    {
        $this->checkAccess($request, $company);

        $data_update = $request->only(['bank_name', 'bik', 'invoice', 'kor', 'default', 'closed']);
        $data_update['name'] = array_shift($data_update);

        try {
            $requisites->update($data_update);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        // Для выгрузки в 1С
        event(new BankRequisiteUpdated($company));

        return $company->bankRequisites;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, Company $company, BankRequisites $requisites)
    {
        $this->checkAccess($request, $company);

        try {
            $requisites->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }

        return ['success'=>true];
    }

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
}
