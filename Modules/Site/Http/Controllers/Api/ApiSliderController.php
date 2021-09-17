<?php

namespace Modules\Site\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Site\Entities\Slider;

class ApiSliderController extends BaseController
{
    public function index(Request $request, string $type = 'main')
    {
        $is_type = array_search($type, Slider::types) !== false;

        $sliders = Slider::active();

        if ($is_type) {
            if (Slider::types[$type] == Slider::MAIN) {
                $sliders->main();
            } elseif (Slider::types[$type] == Slider::ABOUT_COMPANY) {
                $sliders->aboutCompany();
            }
        }

        return $sliders->get();
    }
}
