<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\MedicalCenter;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class MedicalCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $role_medical_center = Role::where('role_name', 'ROLE_MEDICAL_CENTER')->first()->id;

        $medical_centers = [
            ['name' => 'Phòng Khám Thú Y Titi Pet', 'address' => '330 Nguyễn Đình Tựu'],
            ['name' => 'Phòng Khám Thú Y FamilyPet Clinic', 'address' => '191/32 Đỗ Quang'],
            ['name' => 'Phòng Khám Thú Y My Pet Clinic', 'address' => '146 Lê Duy Đình'],
            ['name' => 'Phòng Khám Thú Y VVET Đà Nẵng', 'address' => '14 Bình An 4'],
            ['name' => 'Phòng Khám Thú Y Sông Hàn Pet Clinic', 'address' => '50 Phan Đăng Lưu'],
            ['name' => 'Phòng Khám Thú y - Hello Pets', 'address' => '54 Nguyễn Phẩm'],
            ['name' => 'Bệnh viện Thú y Đà Nẵng', 'address' => '69 Hoàng Thúc Trâm'],
            ['name' => 'Phòng khám thú y love Pet', 'address' => ' 22 Phạm Như Xương'],
            ['name' => 'Tiệm Thú Cưng Đà Nẵng', 'address' => '77 Hồ Tỵ'],
            ['name' => 'Phòng khám Thú y Đà Nẵng City Pet', 'address' => '23 Đặng Dung'],
        ];

        $start_time_options = ['05:00 AM', '05:30 AM', '06:00 AM', '06:30 AM', '07:00 AM']; // Giờ bắt đầu làm việc (AM/PM format)
        $end_time_options = ['22:00 PM', '22:30 PM', '23:00 PM', '23:30 PM', '24:00 PM']; // Giờ bắt đầu làm việc (AM/PM format)

        foreach ($medical_centers as $i => $medical_center) {
            $start_time = $faker->randomElement($start_time_options);
            $end_time = $faker->randomElement($end_time_options);
            $work_time = $start_time . ' : ' . $end_time;

            $medical_center_account = Account::factory()->create([
                'username' => $faker->userName(),
                'email' => $faker->companyEmail(),
                'password' => Hash::make('medicalcenter123'),
                'avatar' => 'gs://petshop-3d4ae.appspot.com/avatars/medical_center/' . ($i+1) . '/',
                'enabled' => $faker->boolean(100),
                'role_id' => $role_medical_center,
                'reset_code' => null,
                'reset_code_expires_at' => null,
                'reset_code_attempts' => null
            ]);

            MedicalCenter::factory()->create([
                'name' => $medical_center['name'],
                'description' => $faker->paragraph(5),
                'image' => 'gs://petshop-3d4ae.appspot.com/medical_centers/' . ($i+1) . '/',
                'phone' => $faker->phoneNumber(),
                'address' => $medical_center['address'],
                'website' => $faker->url(),
                'fanpage' => $faker->url(),
                'work_time' => $work_time,
                'establish_year' => $faker->year(),
                'account_id' => $medical_center_account->id,
            ]);
        }
    }
}
