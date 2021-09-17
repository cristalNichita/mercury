<?php

namespace Modules\Site\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Site\Entities\Slider;

class SliderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Slider::factory()->count(12)->create()->each(function ($slider) {
            $url = 'http://via.placeholder.com/728x400';
            $slider
                ->addMediaFromUrl($url)
                ->toMediaCollection('image');
        });
    }
}
