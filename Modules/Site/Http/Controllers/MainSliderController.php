<?php

namespace Modules\Site\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Site\Entities\Slider;
use Modules\Site\Transformers\SliderResource;

class MainSliderController extends SliderController
{
    const SLIDER_TYPE = 0;

    public function index(Request $request)
    {
        $sliders = Slider::main()
            ->paginate($this->per_page);

        return Inertia::render('Site/Slider/SliderIndex', [
            'sliders' => $sliders
        ]);
    }
}
