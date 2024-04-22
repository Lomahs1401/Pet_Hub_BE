<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $faker = Faker::create();

        $role_model = new Role();
        $role_customer = $role_model->where('role_name', 'Customer')->first();
        $role_staff = $role_model->where('role_name', 'Staff')->first();
        $role_admin = $role_model->where('role_name', 'Admin')->first();


        $list_customer_avatars = [
            'gs://petshop-3d4ae.appspot.com/avatars/customer/1.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/2.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/3.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/4.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/5.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/6.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/7.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/8.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/9.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/10.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/11.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/12.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/13.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/14.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/customer/15.jpg',
        ];

        $list_staff_avatars = [
            'gs://petshop-3d4ae.appspot.com/avatars/staff/1.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/staff/2.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/staff/3.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/staff/4.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/staff/5.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/staff/6.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/staff/7.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/staff/8.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/staff/9.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/staff/10.jpg',
        ];

        $list_admin_avatars = [
            'gs://petshop-3d4ae.appspot.com/avatars/admin/1.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/admin/2.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/admin/3.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/admin/4.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/admin/5.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/admin/6.jpg',
            'gs://petshop-3d4ae.appspot.com/avatars/admin/7.jpg',
        ];

        define('TOTAL_CUSTOMER_ACCOUNT', count($list_customer_avatars));
        define('TOTAL_STAFF_ACCOUNT', count($list_staff_avatars));
        define('TOTAL_ADMIN_ACCOUNT', count($list_admin_avatars));

        for($i = 0; $i < TOTAL_CUSTOMER_ACCOUNT; $i++) {
            $customer_account = Account::factory()->create([
                'username' => $faker->userName(),
                'email' => $faker->safeEmail(),
                'password' => Hash::make('customer123'),
                'avatar' => $list_customer_avatars[$i],
                'enabled' => $faker->boolean(100),
                'reset_code'=> null,
                'reset_code_expires_at'=>null,
                'reset_code_attempts'=>null
            ]);

            DB::table('account_has_roles')->insert([
                'account_id' => $customer_account->id,
                'role_id' => $role_customer->id,
                'licensed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for($i = 0; $i < TOTAL_STAFF_ACCOUNT; $i++) {
            $staff_account = Account::factory()->create([
                'username' => $faker->userName(),
                'email' => $faker->safeEmail(),
                'password' => Hash::make('staff123'),
                'avatar' => $list_staff_avatars[$i],
                'enabled' => $faker->boolean(100),
                'reset_code'=> null,
                'reset_code_expires_at'=>null,
                'reset_code_attempts'=>null
            ]);

            DB::table('account_has_roles')->insert([
                'account_id' => $staff_account->id,
                'role_id' => $role_staff->id,
                'licensed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        for($i = 0; $i < TOTAL_ADMIN_ACCOUNT; $i++) {
            $admin_account = Account::factory()->create([
                'username' => $faker->userName(),
                'email' => $faker->safeEmail(),
                'password' => Hash::make('admin123'),
                'avatar' => $list_admin_avatars[$i],
                'enabled' => $faker->boolean(100),
                'reset_code'=> null,
                'reset_code_expires_at'=>null,
                'reset_code_attempts'=>null
            ]);

            DB::table('account_has_roles')->insert([
                'account_id' => $admin_account->id,
                'role_id' => $role_admin->id,
                'licensed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
