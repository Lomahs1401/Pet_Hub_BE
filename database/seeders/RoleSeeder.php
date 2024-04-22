<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $list_roles = [
            'Admin',
            'Staff',
            'Customer'
        ];

        for ($i = 0; $i < count($list_roles); $i++) {
            Role::factory()->create([
                'role_name' => $list_roles[$i]
            ]);
        }
    }
}
