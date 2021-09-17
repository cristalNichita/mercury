<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Order\Entities\Delivery;
use Modules\Order\Http\Requests\DeliveryUpdateRequest;

class DeliveryController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $items = Delivery::sort($request->get('sort', 'id-asc'))->get();
        return Inertia::render('Delivery/Index',[
            'items' => $items,
            'sort' => $request->input('sort'),
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
     * @param Delivery $delivery
     * @return \Inertia\Response
     */
    public function show(Delivery $delivery)
    {
        return Inertia::render('Delivery/Show',[
            'item' => $delivery->toArray(),
        ]);
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
     * @param DeliveryUpdateRequest $request
     * @param Delivery $delivery
     */

    public function update(DeliveryUpdateRequest $request, Delivery $delivery)
    {
        $data = $request->validated();
        $delivery->update($data);

        if ($request->has('newImage'))
        {
            $delivery->setImage($data['newImage']);
        }

        $delivery->refresh();
        return Inertia::render('Delivery/Show',[
            'item' => $delivery,
        ]);
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
}
