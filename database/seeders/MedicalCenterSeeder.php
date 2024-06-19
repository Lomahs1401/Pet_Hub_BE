<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\MedicalCenter;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
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
    $role_doctor = Role::where('role_name', 'ROLE_DOCTOR')->first()->id;

    $medical_centers = [
      ['name' => 'Phòng Khám Thú Y Titi Pet', 'address' => '330 Nguyễn Đình Tựu, Đà Nẵng'],
      ['name' => 'Phòng Khám Thú Y FamilyPet Clinic', 'address' => '191/32 Đỗ Quang, Đà Nẵng'],
      ['name' => 'Phòng Khám Thú Y My Pet Clinic', 'address' => '146 Lê Duy Đình, Đà Nẵng'],
      ['name' => 'Phòng Khám Thú Y VVET Đà Nẵng', 'address' => '14 Bình An 4, Đà Nẵng'],
      ['name' => 'Phòng Khám Thú Y Sông Hàn Pet Clinic', 'address' => '50 Phan Đăng Lưu, Đà Nẵng'],
      ['name' => 'Phòng Khám Thú y - Hello Pets', 'address' => '54 Nguyễn Phẩm, Đà Nẵng'],
      ['name' => 'Bệnh viện Thú y Đà Nẵng', 'address' => '69 Hoàng Thúc Trâm, Đà Nẵng'],
      ['name' => 'Phòng khám thú y love Pet', 'address' => ' 22 Phạm Như Xương, Đà Nẵng'],
      ['name' => 'Tiệm Thú Cưng Đà Nẵng', 'address' => '77 Hồ Tỵ, Đà Nẵng'],
      ['name' => 'Phòng khám Thú y Đà Nẵng City Pet', 'address' => '23 Đặng Dung, Đà Nẵng'],
    ];

    $list_doctors_avatars = [
      'gs://new_petshop_bucket/doctors/1/',
      'gs://new_petshop_bucket/doctors/2/',
      'gs://new_petshop_bucket/doctors/3/',
      'gs://new_petshop_bucket/doctors/4/',
      'gs://new_petshop_bucket/doctors/5/',
      'gs://new_petshop_bucket/doctors/6/',
      'gs://new_petshop_bucket/doctors/7/',
      'gs://new_petshop_bucket/doctors/8/',
      'gs://new_petshop_bucket/doctors/9/',
      'gs://new_petshop_bucket/doctors/10/',
    ];

    $start_time_options = ['06:00 AM', '07:00 AM', '08:00 AM', '09:00 AM']; // Giờ bắt đầu làm việc (AM/PM format)
    $end_time_options = ['06:00 PM', '07:00 PM', '08:00 PM', '09:00 PM']; // Giờ kết thúc làm việc (AM/PM format)

    foreach ($medical_centers as $i => $medical_center) {
      $start_time = $faker->randomElement($start_time_options);
      $end_time = $faker->randomElement($end_time_options);
      $work_time = $start_time . ' : ' . $end_time;

      $created_at = $faker->dateTimeBetween('-2 years', 'now');

      $medical_center_account = Account::factory()->create([
        'username' => $faker->userName(),
        'email' => $faker->companyEmail(),
        'password' => Hash::make('medicalcenter123'),
        'avatar' => 'gs://new_petshop_bucket/avatars/medical_center/' . ($i + 1) . '.jpg',
        'enabled' => $faker->boolean(100),
        'is_approved' => $faker->boolean(80),
        'role_id' => $role_medical_center,
        'created_at' => $created_at,
        'updated_at' => $created_at,
        'reset_code' => null,
        'reset_code_expires_at' => null,
        'reset_code_attempts' => null
      ]);

      $medical_center = MedicalCenter::factory()->create([
        'name' => $medical_center['name'],
        'description' => $faker->paragraph(5),
        'image' => 'gs://new_petshop_bucket/medical_centers/' . ($i + 1) . '/',
        'phone' => $faker->phoneNumber(),
        'address' => $medical_center['address'],
        'website' => $faker->url(),
        'fanpage' => $faker->url(),
        'work_time' => $work_time,
        'establish_year' => $faker->year(),
        'account_id' => $medical_center_account->id,
      ]);

      $doctor_created_at = $faker->dateTimeBetween($created_at, 'now');

      $approved = $faker->boolean(80);

      $doctor_account = Account::factory()->create([
        'username' => $faker->userName(),
        'email' => $faker->safeEmail(),
        'password' => Hash::make('doctor123'),
        'avatar' => 'gs://new_petshop_bucket/avatars/doctor/' . ($i + 1) . '.jpg',
        'enabled' => $approved,
        'is_approved' => $approved,
        'role_id' => $role_doctor,
        'created_at' => $doctor_created_at,
        'updated_at' => $doctor_created_at,
        'reset_code' => null,
        'reset_code_expires_at' => null,
        'reset_code_attempts' => null
      ]);

      $is_male_doctor = $faker->boolean(chanceOfGettingTrue: 50);

      DB::table('doctors')->insert([
        'account_id' => $doctor_account->id,
        'medical_center_id' => $medical_center->id,
        'full_name' => $is_male_doctor ? $faker->lastName() . ' ' . $faker->firstNameMale()
          : $faker->lastName() . ' ' . $faker->firstNameFemale(),
        'gender' => $is_male_doctor ? 'Male' : 'Female',
        'description' => $faker->paragraph(6),
        'birthdate' => $faker->dateTimeInInterval('-20 years', '+2 years', 'Asia/Ho_Chi_Minh')->format('Y-m-d'),
        'CMND' => $faker->numerify('#########'),
        'address' => $faker->city(),
        'phone' => $faker->regexify('0(3|5|7|8|9){1}([0-9]{8})'),
        'image' => $list_doctors_avatars[$i],
        'created_at' => $created_at,
        'updated_at' => $created_at,
      ]);
    }
  }
}
