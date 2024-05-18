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
        $ALL_ROLES = ['ROLE_CUSTOMER', 'ROLE_SHOP', 'ROLE_MEDICAL_CENTER', 'ROLE_AID_CENTER', 'ROLE_ADMIN'];

        for ($i = 0; $i < count($ALL_ROLES); $i++) {
            Role::factory()->create([
                'role_name' => $ALL_ROLES[$i],
            ]);
        }
    }
}
