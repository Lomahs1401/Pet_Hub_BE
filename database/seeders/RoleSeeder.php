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

        $list_admin_roles = [
            'Admin'
        ];

        $list_staff_roles = [
            'Staff'
        ];

        $list_customer_roles = [
            'Normal Customer',
            'VIP Customer',
            'Doctor'
        ];

        // Add Admin Role
        for ($i = 0; $i < count($list_admin_roles); $i++) {
            Role::factory()->create([
                'role_name' => $list_admin_roles[$i],
                'role_type' => 'Admin'
            ]);
        }

        // Add Staff Role
        for ($i = 0; $i < count($list_staff_roles); $i++) {
            Role::factory()->create([
                'role_name' => $list_staff_roles[$i],
                'role_type' => 'Staff'
            ]);
        }

        // Add Customer Role
        for ($i = 0; $i < count($list_customer_roles); $i++) {
            Role::factory()->create([
                'role_name' => $list_customer_roles[$i],
                'role_type' => 'Customer'
            ]);
        }
    }
}
