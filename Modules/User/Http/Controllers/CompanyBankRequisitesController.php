<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\User\Entities\BankRequisites;
use Modules\User\Entities\Company;
use Modules\User\Events\BankRequisiteCreated;
use Modules\User\Events\BankRequisiteUpdated;

class CompanyBankRequisitesController extends BaseController
{

    public function create(Request $request, Company $company)
    {
        return Inertia::render('@User/BankRequisiteEdit', [
            'bank_requisite' => new BankRequisites(),
            'company' => $company
        ]);
    }


    public function store(Request $request, Company $company)
    {
        $fill = $request->only([
            'name', 'bik', 'invoice', 'kor'
        ]);
        $company->bankRequisites()->create($fill);

        event(new BankRequisiteCreated($company));

        return redirect(route('users.company.show',['company' => $company->id]));
    }


    public function show(Company $company, BankRequisites $bank_requisite)
    {

    }


    public function update(Request $request, Company $company, BankRequisites $bank_requisite)
    {

    }

    //TODO: Добавить метод для проверки соответствия реквезитов к компании
    public function destroy(Company $company, BankRequisites $bank_requisite)
    {
        $bank_requisite->delete();

        return redirect(route('users.company.show',['company' => $bank_requisite->company_id]));
    }

    public function toggleClosed(Company $company, BankRequisites $bank_requisite)
    {
        $bank_requisite->update(['closed' => !$bank_requisite->closed]);

        event(new BankRequisiteUpdated($company));

        return redirect(route('users.company.show',['company' => $bank_requisite->company_id]));
    }

    public function toggleDefault(Company $company, BankRequisites $bank_requisite)
    {
        if ($bank_requisite->closed) {
            return redirect(route('users.company.show',['company' => $bank_requisite->company_id]))
                ->withErrors(['Закрытый счет нельзя установить основным']);
        }

        if (!$bank_requisite->default) {
            BankRequisites::where('company_id','=',$bank_requisite->company_id)
                ->whereNotIn('id',[$bank_requisite->id])
                ->update(['default' => $bank_requisite->default]);
            $bank_requisite->update(['default' => !$bank_requisite->default]);
        } else {
            $bank_requisite->update(['default' => !$bank_requisite->default]);
        }

        return redirect(route('users.company.show',['company' => $bank_requisite->company_id]));
    }
}
