<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Catalog\Entities\Brand;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Filters\BrandFilter;
use Modules\Catalog\Http\Controllers\Admin\AdminController;

class BrandController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $brands = Brand::paginate($this->per_page);

        return Inertia::render('@Catalog/Brands', [
            'filter' => $request->all(),
            'brands' => $brands,
        ]);
    }

    public function indexResource(Request $request)
    {
        $filter = new BrandFilter( $request );

        $query = Brand::filter( $filter );

        if ($request->has('without_paginate')) {
            $brands = $query->get();
        } else {
            $brands = $query->paginate( $this->paginate );
        }

        return $brands;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        //
    }
}
