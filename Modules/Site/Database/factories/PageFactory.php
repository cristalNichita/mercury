<?php

namespace Modules\Site\Database\factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Settings\Dict\DictFacade;
use Modules\Site\Entities\Page;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Site\Entities\Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = rand(0, count(Page::types) - 1);
        $title = $this->faker->sentence(2);

        $page = [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->text(),
            'type' => $type,
        ];

        $random = rand(0, 26);
        $active = $random % 3 == 2;

        if ($type == Page::NEWS_TYPE) {
            $page['author'] = $this->faker->name();
        } else if ($type == Page::PROJECT_TYPE) {
            $categories = DictFacade::get('project-categories');
            $page['category'] = $categories->items->random()->id;
        }

        $page['active'] = $active;

        return $page;
    }
}

