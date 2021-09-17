<?php

namespace Modules\Catalog\Http\Controllers;

use App\Http\Controllers\BaseController;
use http\Env\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Filters\CategoryFilter;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $categories = Category::filter($request->get('filter', []))->get();

        return Inertia::render('Catalog/Categories', [
            'filter' => $request->all(),
            'categories' => $categories->toTree()
        ]);
    }

    public function indexResource(Request $request)
    {
        $filter = new CategoryFilter($request);

        $query = Category::filter($filter);

        if ($request->has('without_paginate')) {
            $categories = $query->get();
        } else {
            $categories = $query->paginate($this->paginate);
        }

        return $categories;
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
     * Обновление изображения категории.
     *
     * @param Category $category
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateImage(Category $category, Request $request) {
        $file = $request->file('image');

        $this->validate($request, [
            'image' => 'required|image',
        ]);

        $category->clearMediaCollection('image');

        try {
            $category
                ->addMedia($file)
                ->toMediaCollection('image');
        }
        catch (FileDoesNotExist | FileIsTooBig $e) {
            back()->withErrors($e->getMessage());
        }

        return back();
    }

    /**
     * Удаление всех изображений категории.
     *
     * @param Category $category
     * @return RedirectResponse
     */
    public function removeImages(Category $category)
    {
        $category->clearMediaCollection('image');

        return back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Category $category)
    {
        return Inertia::render('Catalog/Category', [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return;
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
