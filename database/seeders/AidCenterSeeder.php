<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AidCenter;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class AidCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $role_aid_center = Role::where('role_name', 'ROLE_AID_CENTER')->first()->id;

        $aid_centers = [
            ['name' => 'Cứu Trợ Động Vật Đà Nẵng (ARD)', 'address' => '398 Tôn Đản'],
            ['name' => 'Paws For Compassion (PFC)', 'address' => '191/32 Đỗ Quang'],
        ];

        $start_time_options = ['05:00 AM', '05:30 AM', '06:00 AM', '06:30 AM', '07:00 AM']; // Giờ bắt đầu làm việc (AM/PM format)
        $end_time_options = ['22:00 PM', '22:30 PM', '23:00 PM', '23:30 PM', '24:00 PM']; // Giờ bắt đầu làm việc (AM/PM format)

        foreach ($aid_centers as $i => $aid_center) {
            $start_time = $faker->randomElement($start_time_options);
            $end_time = $faker->randomElement($end_time_options);
            $work_time = $start_time . ' : ' . $end_time;

            $aid_center_account = Account::factory()->create([
                'username' => $faker->userName(),
                'email' => $faker->companyEmail(),
                'password' => Hash::make('aidcenter123'),
                'avatar' => 'gs://petshop-3d4ae.appspot.com/avatars/aid_center/' . ($i+1) . '/',
                'enabled' => $faker->boolean(100),
                'role_id' => $role_aid_center,
                'reset_code' => null,
                'reset_code_expires_at' => null,
                'reset_code_attempts' => null
            ]);

            AidCenter::factory()->create([
                'name' => $aid_center['name'],
                'description' => $faker->paragraph(5),
                'image' => 'gs://petshop-3d4ae.appspot.com/aid_centers/' . ($i+1) . '/',
                'phone' => $faker->phoneNumber(),
                'address' => $aid_center['address'],
                'website' => $faker->url(),
                'fanpage' => $faker->url(),
                'work_time' => $work_time,
                'establish_year' => $faker->year(),
                'account_id' => $aid_center_account->id,
            ]);
        }
    }
}
