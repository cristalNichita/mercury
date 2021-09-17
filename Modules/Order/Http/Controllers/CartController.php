<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Settings\Events\SettingsLinksEvent;
use Settings;

class CartController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('order::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('order::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('order::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('order::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function cartSettings()
    {
        Inertia::share('sidebarLinks', event(new SettingsLinksEvent()));

        $managers = User::where('role', '=', User::ROLES['manager'])->get();

        return Inertia::render('@Order/CartSettings', [
            'settings' => Settings::get(),
            'managers' => $managers,
        ]);
    }

    public function cartSettingsSave(Request $request)
    {
        Settings::set('cart__time_clear', $request->time_clear);
        Settings::set('cart__time_notify', $request->time_notify);
        Settings::set('cart__manager_id_notify',$request->manager_id_notify);

        return back();
    }
}
