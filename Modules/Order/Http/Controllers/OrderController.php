<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\Order\Transformers\OrderResource;
use Modules\Settings\Events\SettingsLinksEvent;
use Settings;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Order\Entities\Order;

class OrderController extends BaseController
{

    public function index(Request $request)
    {
        $orders = Order::with('contact')
            ->sort($request->get('sort', 'id-asc'))
            ->paginate($this->per_page);

        return Inertia::render('@Order/Orders', [
            'orders' => OrderResource::collection($orders),
            'sort' => $request->input('sort'),
        ]);
    }

    /**
     * Новые заказы
     * @return Renderable
     */
    public function orders(Request $request, $type = 'new')
    {
        $orders = Order::sort($request->get('sort', 'id-asc'))->paginate($this->per_page);
        return Inertia::render('Order/Index', [
            'orders' => $orders,
            'sort' => $request->input('sort'),
        ]);
    }

    /**
     * Новые заказы
     * @return Renderable
     */
    public function order(Order $order)
    {
        return Inertia::render('Order/view', [
            'order' => $order,
        ]);
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

    public function paymentSettings()
    {
        Inertia::share('sidebarLinks', event(new SettingsLinksEvent()));

        return Inertia::render('@Order/PaymentSettings', [
            'settings' => Settings::get()
        ]);
    }

    public function paymentSettingsSave(Request $request)
    {
        Settings::set('uniteller__login', $request->login);
        Settings::set('uniteller__password', $request->password);
        Settings::set('uniteller__point_id', $request->point_id);

        return back();
    }
}
