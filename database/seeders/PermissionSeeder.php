<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $role_model = new Role();
        
        // Customer Role
        $normal_customer_role = $role_model->where('role_name', 'Normal Customer')->first();
        $shop_manager_customer_role = $role_model->where('role_name', 'Shop Manager Customer')->first();

        // Staff Role
        $normal_staff_role = $role_model->where('role_name', 'Normal Staff')->first();
        $doctor_staff_role = $role_model->where('role_name', 'Doctor Staff')->first();
        
        // Admin Role
        $role_admin = $role_model->where('role_name', 'Admin')->first();

        $list_normal_customer_permission = [
            // Pet
            'View Pet',
            'Rate Pet',
            'Buy Pet',
            // Product
            'View Product',
            'Rate Product',
            'Buy Product',
            // Service
            'View Service',
            'Rate Service',
            'Buy Service',
            // Profile
            'View Profile',
            'Update Profile',
            // Appointment
            'Create Appointment',
            'View Appointment',
            'Update Appointment',
            'Delete Appointment',
        ];

        $list_shop_manager_customer_permission = [
            // Pet
            'View Pet',
            'Create Pet',
            'Update Pet',
            'Delete Pet',
            // Product
            'View Product',
            'Create Product',
            'Update Product',
            'Delete Product',
            // Service
            'View Service',
            'Create Service',
            'Update Service',
            'Delete Service',
            // Profile
            'View Profile',
            'Update Profile',
            // Appointment
            'Create Appointment',
            'View Appointment',
            'Update Appointment',
            'Delete Appointment',
        ];

        $list_normal_staff_permission = [
            // Product
            'View Product',
            'Manage Product',
            // Service
            'View Service',
            'Manage Service',
            // Profile (User)
            'View Profile',
            'Manage Profile',
        ];

        $list_doctor_staff_permission = [
            // Pet
            'View Pet',
            'Create Pet',
            'Update Pet',
            'Delete Pet',
            // Product
            'View Product',
            // Service
            'View Service',
            'Create Service',
            'Update Service',
            'Delete Service',
            // Profile
            'View Profile',
            'Update Profile',
            // Appointment
            'Create Appointment',
            'View Appointment',
            'Update Appointment',
            'Delete Appointment',
        ];

        $list_admin_permission = [
            // Manage Product
            'View Product',
            'Create Product',
            'Update Product',
            'Delete Product',
            // Manage Service
            'View Service',
            'Create Service',
            'Update Service',
            'Delete Service',
            // Manage Order
            'View Service',
            'Create Service',
            'Update Service',
            'Delete Service',
            // Profile (User)
            'View Profile',
            'Update Profile',
            // User
            'Create User',
            'Delete User',
            // Order
            'View Orders',
        ];

        // Add permissions for customer
        foreach ($list_normal_customer_permission as $permissionName) {
            $normal_customer_permissions = Permission::factory()->create([
                'permission_name' => $permissionName,
                'check_permission' => 1,
            ]);

            DB::table('role_has_permissions')->insert([
                'role_id' => $normal_customer_role->id,
                'permission_id' => $normal_customer_permissions->id,
                'licensed' => 1,
            ]);
        }

        foreach ($list_shop_manager_customer_permission as $permissionName) {
            $shop_manager_customer_permissions = Permission::factory()->create([
                'permission_name' => $permissionName,
                'check_permission' => 1,
            ]);

            DB::table('role_has_permissions')->insert([
                'role_id' => $shop_manager_customer_role->id,
                'permission_id' => $shop_manager_customer_permissions->id,
                'licensed' => 1,
            ]);
        }

        // Add permissions for staff
        foreach ($list_normal_staff_permission as $permissionName) {
            $normal_staff_permission = Permission::factory()->create([
                'permission_name' => $permissionName,
                'check_permission' => 1,
            ]);

            DB::table('role_has_permissions')->insert([
                'role_id' => $normal_staff_role->id,
                'permission_id' => $normal_staff_permission->id,
                'licensed' => 1,
            ]);
        }

        foreach ($list_doctor_staff_permission as $permissionName) {
            $doctor_staff_permissions = Permission::factory()->create([
                'permission_name' => $permissionName,
                'check_permission' => 1,
            ]);

            DB::table('role_has_permissions')->insert([
                'role_id' => $doctor_staff_role->id,
                'permission_id' => $doctor_staff_permissions->id,
                'licensed' => 1,
            ]);
        }

        // Add permissions for admin
        foreach ($list_admin_permission as $permissionName) {
            $admin_permission = Permission::factory()->create([
                'permission_name' => $permissionName,
                'check_permission' => 1,
            ]);

            DB::table('role_has_permissions')->insert([
                'role_id' => $role_admin->id,
                'permission_id' => $admin_permission->id,
                'licensed' => 1,
            ]);
        }
    }
}
