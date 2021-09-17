<?php

namespace Modules\Site\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Site\Entities\Slider;

class AboutCompanySliderController extends SliderController
{
    const SLIDER_TYPE = 1;

    public function index(Request $request)
    {
        $sliders = Slider::aboutCompany()
            ->paginate($this->per_page);

        return Inertia::render('Site/Slider/SliderIndex', [
            'sliders' => $sliders
        ]);
    }
}
