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
        $vip_customer_role = $role_model->where('role_name', 'VIP Customer')->first();
        $doctor_customer_role = $role_model->where('role_name', 'Doctor')->first();

        $role_staff = $role_model->where('role_name', 'Staff')->first();
        $role_admin = $role_model->where('role_name', 'Admin')->first();

        $list_normal_customer_permission = [
            // Pet
            'View Pet',
            // Product
            'View Product',
            'Rate Product',
            // Service
            'View Service',
            'Rate Service',
            // Profile
            'View Profile',
            'Update Profile',
            // Appointment
            'Create Appointment',
            'View Appointment',
            'Update Appointment',
            'Delete Appointment',
        ];

        $list_vip_customer_permission = [
            // Pet
            'View Pet',
            // Service
            'View Service',
            'Rate Service',
            // Profile
            'View Profile',
            'Update Profile',
            // Appointment
            'Create Appointment',
            'View Appointment',
            'Update Appointment',
            'Delete Appointment',
        ];

        $list_doctor_customer_permission = [
            // Pet
            'View Pet',
            'Update Pet',
            'Delete Pet',
            // Product
            'View Product',
            'Rate Product',
            // Service
            'View Service',
            'Rate Service',
            // Profile
            'View Profile',
            'Update Profile',
            // Appointment
            'Create Appointment',
            'View Appointment',
            'Update Appointment',
            'Delete Appointment',
        ];

        $list_staff_permission = [
            // Product
            'View Product',
            'Create Product',
            'Update Product',
            // Service
            'View Service',
            'Create Service',
            'Update Service',
            // Profile (User)
            'View Profile',
            'Update Profile',
            // Appointment
            'Create Appointment',
            'View Appointment',
            'Update Appointment',
            'Delete Appointment',
            // Order
            'View Orders',
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

        foreach ($list_vip_customer_permission as $permissionName) {
            $vip_customer_permissions = Permission::factory()->create([
                'permission_name' => $permissionName,
                'check_permission' => 1,
            ]);

            DB::table('role_has_permissions')->insert([
                'role_id' => $vip_customer_role->id,
                'permission_id' => $vip_customer_permissions->id,
                'licensed' => 1,
            ]);
        }

        foreach ($list_doctor_customer_permission as $permissionName) {
            $doctor_customer_permissions = Permission::factory()->create([
                'permission_name' => $permissionName,
                'check_permission' => 1,
            ]);

            DB::table('role_has_permissions')->insert([
                'role_id' => $doctor_customer_role->id,
                'permission_id' => $doctor_customer_permissions->id,
                'licensed' => 1,
            ]);
        }

        // Add permissions for staff
        foreach ($list_staff_permission as $permissionName) {
            $staff_permission = Permission::factory()->create([
                'permission_name' => $permissionName,
                'check_permission' => 1,
            ]);

            DB::table('role_has_permissions')->insert([
                'role_id' => $role_staff->id,
                'permission_id' => $staff_permission->id,
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
