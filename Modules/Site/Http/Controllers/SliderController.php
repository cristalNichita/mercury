<?php

namespace Modules\Site\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Site\Entities\Slider;
use Modules\Site\Http\Requests\StoreSliderRequest;
use Modules\Site\Http\Requests\UpdateSliderRequest;
use Modules\Site\Transformers\SliderResource;

class SliderController extends BaseController
{
    const SLIDER_TYPE = 0;

    public function index(Request $request)
    {
        // Добавить пагинацию
        $sliders = Slider::paginate($this->per_page);

        return Inertia::render('Site/Slider/SliderIndex', [
            'sliders' => $sliders
        ]);
    }

    public function create()
    {
        return Inertia::render('Site/Slider/SliderCreate', [
            'slider' => new Slider(),
            'sliderType' => static::SLIDER_TYPE,
        ]);
    }

    public function store(StoreSliderRequest $request)
    {
        $validated = $request->validated();

        $slider = Slider::create($validated);

        $slider->setImage($validated['new_image']);

        return $this->redirectToIndex();
    }

    public function edit(Slider $slider)
    {
        return Inertia::render('Site/Slider/SliderEdit', [
            'slider' => $slider,
            'sliderType' => static::SLIDER_TYPE,
        ]);
    }

    public function update(UpdateSliderRequest $request, Slider $slider)
    {
        $validated = $request->validated();

        if ($request->has('new_image')) {
            $slider->setImage($validated['new_image']);
        }

        $slider->update($validated);

        return $this->redirectToIndex();
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();

        return $this->redirectToIndex();
    }

    protected function redirectToIndex()
    {
        $route = Str::of(Route::currentRouteName())->split('/\./');

        $route = array_slice($route->toArray(), 0, 2);

        return redirect(route(implode('.', $route)));
    }
}
