<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Support\Arr;
use Inertia\Inertia;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Inertia\Response;
use Modules\Order\Transformers\OrderResource;
use Modules\Settings\Entities\Setting;
use Modules\User\Entities\Company;
use Modules\User\Events\CompanyCreated;
use Modules\User\Events\CompanyUpdated;

/**
 * Class CompanyController
 * @package Modules\User\Http\Controllers
 */
class CompanyController extends BaseController
{

    /**
     * CompanyController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->dadata = new \Dadata\DadataClient(
            Setting::firstWhere('name', 'dadata_token')->value,
            Setting::firstWhere('name', 'dadata_secret')->value
        );
    }

    /**
     * @return Response
     */
    public function index()
    {
        $companies = Company::get();
        return Inertia::render('Company/Index', [
            'companies' => $companies
        ]);
    }


    /**
     * @return Response
     */
    public function create()
    {
        return Inertia::render('@User/CompanyEdit', [
            'company' => new Company()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function companyDadata(Request $request)
    {
        $response = $this->dadata->suggest("party", $request->input('query'));
        return $this->sendResponse($response, 'success', 200);
    }


    /**
     * @param Request $request
     * @param Company $company
     * @return Response
     */
    public function show(Request $request, Company $company)
    {
        $company->load(['params', 'holding', 'bankRequisites']);

        $orders = $company->orders()
            ->sort($request->get('sort', 'id-asc'))
            ->paginate($this->per_page);

        return Inertia::render('@User/CompanyShow', [
            'company' => $company,
            'orders' => OrderResource::collection($orders),
        ]);
    }


    /**
     * @param Request $request
     * @param Company $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Company $company)
    {
        if ($company->inn != $request->company) {
            return back()->withErrors('ИНН менять нельзя');
        }
        $company->update($request->only([
            'name', 'inn', 'kpp', 'ogrn'
        ]));

        $data_contacts = $request->only(['phone', 'email', 'u_address', 'f_address']);
        $company->fill($data_contacts);

        $company->save();

        event(new CompanyUpdated($company));

        return back();
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $fill = Arr::only($request->all(), [
            'name', 'holding_id', 'inn',
            'kpp', 'type', 'type_1c'
        ]);
        $params_fill = array_diff($request->all(), $fill);

        $company = Company::create($fill);

        $company->fill($params_fill);
        $company->save();

        event(new CompanyCreated($company));

        return redirect(route('users.company.show', $company->id));
    }


    /**
     * @param Company $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Company $company)
    {
        $company->params()->delete();
        $company->delete();
        return redirect()->route('users.companies');
    }
}
