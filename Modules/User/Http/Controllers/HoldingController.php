<?php

namespace Modules\User\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Fomvasss\Dadata\Facades\DadataSuggest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Inertia\Response;
use Modules\Order\Transformers\OrderResource;
use Modules\Settings\Entities\Setting;
use Modules\User\Entities\Company;
use Modules\User\Entities\Holding;
use Modules\User\Events\CompanyCreated;
use Modules\User\Helpers\PhoneHelper;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;

class HoldingController extends BaseController
{

    public function index(Request $request)
    {
        $holdins = Holding::with(['contacts', 'companies'])
            ->filter($request->get('filter', []))
            ->paginate($this->per_page);

        return Inertia::render('@User/Holdings', [
            'filter' => $request->all(),
            'holdings' => $holdins
        ]);
    }

    /**
     * Обновить основной контакт холдинга.
     *
     * @param Request $request
     * @param Holding $holding
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOwner(Request $request, Holding $holding)
    {
        $owner_id = $request->input('owner_id');
        $holding->owner()->associate($owner_id);

        if ($holding->owner && !$holding->owner->holding()->is($holding)) {
            return back()->withErrors('Контакт не состоит в холдинге');
        }

        $holding->save();

        return back();
    }

    /**
     * Добавление новой компании в холдинг.
     *
     * @param Request $request
     * @param Holding $holding
     * @return RedirectResponse
     */
    public function addCompany(Request $request, Holding $holding) {
        $inn = $request->get('inn');

        $company = Company::where('inn', $inn)->get()->first();

        if ($company) {
            return back()->withErrors('Компания уже существует');
        }

        try {
            $data = $request->only([
                'name', 'inn', 'kpp', 'ogrn',
            ]);

            $data['type'] = 1;
            $data['type_1c'] = 'Юридическое лицо';
            $data['holding_id'] = $holding->id;

            $company = Company::create($data);

            // Для выгрузки в 1С
            event(new CompanyCreated($company));

            return back();

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Company/Create');
    }

    /**
     * Show the specified resource.
     *
     * @param Request $request
     * @param Holding $holding
     * @return Response
     */
    public function show(Request $request, Holding $holding)
    {
        $holding->load(
            'contacts',
            'companies',
            'contacts.user'
        );

        $orders = $holding->orders()
            ->sort($request->get('sort', 'id-asc'))
            ->paginate($this->per_page);

        return Inertia::render('@User/HoldingShow', [
            'holding' => $holding,
            'orders' => OrderResource::collection($orders),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Company $company)
    {
        if ($company->inn != $request->company) {
            return back()->withErrors('ИНН менять нельзя');
        }
        $company->update($request->input());
//        return back()->withErrors('Такая компания уже существует');
        return back();
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $type = 0)
    {
        $data = $request->input();
        if (!$comp = Company::firstWhere('inn', $data['inn'])) {
            $comp = Company::create($data);
        }
        if ($type) {
            return $comp;
        } else {
            return redirect()->route('users.companies');
        }
        //$request->validated()
        //return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('users.companies');
    }
}
