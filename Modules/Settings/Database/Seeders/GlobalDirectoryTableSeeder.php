<?php

namespace Modules\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Entities\GlobalDirectory;
use Modules\Settings\Entities\GlobalDirectoryItem;

class GlobalDirectoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $contacts = GlobalDirectory::create([
            'name' => 'Контакты',
            'code' => 'contacts-page'
        ]);

        $address = new GlobalDirectoryItem([
            'name' => 'Адрес',
        ]);

        $salesDepartment = new GlobalDirectoryItem([
            'name' => 'отдел продаж оборудования',
        ]);

        $contacts->items()->saveMany([
            $address,
            $salesDepartment
        ]);

        $projectCategories = GlobalDirectory::create([
            'name' => 'Категории проектов',
            'code' => 'project-categories'
        ]);

        $shop = new GlobalDirectoryItem([
            'name' => 'Оснащение магазинов',
        ]);

        $pizza = new GlobalDirectoryItem([
            'name' => 'Пиццерии',
        ]);

        $master = new GlobalDirectoryItem([
            'name' => 'Мастер-классы',
        ]);

        $projectCategories->items()->saveMany([
            $shop,
            $pizza,
            $master
        ]);
    }
}
