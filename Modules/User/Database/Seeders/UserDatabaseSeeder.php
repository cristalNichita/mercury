<?php

namespace Modules\User\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\BankRequisites;
use Modules\User\Entities\Company;
use Modules\User\Entities\Contact;
use Modules\User\Entities\Holding;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Запуск создание демонстрационных пользователей.
     *
     * @return void
     */
    public function run()
    {
        $users = [];

        $users[] = [
            'name' => 'Администратор',
            'email' => 'admin@mail.ru',
            'role' => 1,
        ];
        $users[] = [
            'name' => 'Менеджер',
            'email' => 'manager@mail.ru',
            'role' => 2,
        ];
        $users[] = [
            'name' => 'Контент',
            'email' => 'content@mail.ru',
            'role' => 3,
        ];
        $users[] = [
            'name' => '79998889988',
            'email' => null,
            'role' => 4,
            'company' => true
        ];

        $holding = Holding::create(['name' => 'Управление сайтом']);

        foreach ($users as $user) {
            if ($user['role'] == 4) {
                $holding = Holding::create(['name' => $user['name']]);
            }

            $contact = Contact::create([
                'name' => $user['name'],
                'holding_id' => $holding->id
            ]);
            $contact->email = $user['email'];

            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'password' => Hash::make('password'),
                'contact_id' => $contact->id
            ]);

            if ($user['company'] ?? false) {
                Company::factory()->count(rand(1, 3))->make()->each(function ($company) use ($holding) {
                    $company->holding_id = $holding->id;
                    $company->save();
                    $bank = BankRequisites::factory()->count(rand(1, 3))->make()->toArray();
                    $company->bankRequisites()->createMany($bank);
                });
            }
        }
    }
}
