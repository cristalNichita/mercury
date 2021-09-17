<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Catalog\Entities\Brand;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Entities\ProductParameter;
use Modules\Catalog\Filters\BrandFilter;
use Modules\Catalog\Filters\ParameterFilter;

class ParameterController extends BaseController
{

    public function index(Request $request)
    {
        $parameters = ProductParameter::with('values')->get();

        return Inertia::render('@Catalog/CatalogParameters', [
            'filters' => $request->all(),
            'parameters' => $parameters,
        ]);
    }

    public function indexResource(Request $request)
    {
        $filter = new ParameterFilter($request);

        $query = ProductParameter::filter($filter)->with('values');

        if ($request->has('without_paginate')) {
            $parameters = $query->get();
        } else {
            $parameters = $query->paginate($this->paginate);
        }

        return $parameters;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('catalog::create');
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
        return view('catalog::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('catalog::edit');
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
}
