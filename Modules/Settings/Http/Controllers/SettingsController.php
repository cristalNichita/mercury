<?php

namespace Modules\Settings\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Modules\Settings\Entities\GlobalDirectory;
use Modules\Settings\Entities\Setting;
use Modules\Settings\Events\SettingsLinksEvent;
use Settings;

class SettingsController extends Controller
{

    public function __construct()
    {
        Inertia::share('sidebarLinks', event(new SettingsLinksEvent()));
    }

    public function indexModule()
    {
        return redirect(route('settings.settings'));
    }

    public function index()
    {
        return Inertia::render('@Settings/Settings', [
            'settings' => Settings::get(),
            'smsruApi' => env('SMSRU_API_ID'),
        ]);
    }

    public function smsBalance()
    {
        return Helper::getSmsBalance();
    }

    public function update(Request $request)
    {
        $settings = $request->all();
        foreach ($settings as $key => $setting) {
            Settings::set($key, $setting);
        }
        return back();
    }
}
