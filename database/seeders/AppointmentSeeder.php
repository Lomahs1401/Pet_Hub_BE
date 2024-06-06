<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\HistoryDiagnosis;
use App\Models\HistoryVaccine;
use App\Models\MedicalCenter;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AppointmentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker::create();

    $customer_account_ids = Account::whereHas('role', function ($query) {
      $query->where('role_name', 'ROLE_CUSTOMER');
    })->pluck('id')->toArray();
    $medical_center_ids = MedicalCenter::pluck('id')->toArray();

    $vaccines = [
      "Distemper (Dứt điểm)",
      "Parvovirus (Viêm đường ruột)",
      "Canine hepatitis (Viêm gan chó)",
      "Rabies (Dại)",
      "Bordetella (Kennel cough)",
      "Leptospirosis (Sốt hồng ban)",
      "Feline herpesvirus (Herpesvirus mèo)",
      "Calicivirus (Vi rút gây bệnh hô hấp mèo)",
      "Panleukopenia (Viêm gan mèo)",
      "Feline leukemia virus (Viêm nhiễm huyết mèo)"
    ];

    $diagnosis = [
      "Viêm đường ruột",
      "Sốt xuất huyết",
      "Viêm khớp",
      "Viêm gan",
      "Viêm gan tự miễn",
      "Viêm đường hô hấp",
      "Tiêu chảy",
      "Viêm niệu đạo",
      "Ung thư"
    ];

    foreach ($customer_account_ids as $customer_account_id) {
      $random_amount_of_appointments = rand(0, 30);

      // Tạo danh sách các thời gian bắt đầu đã được sử dụng
      $used_start_times = [];

      for ($i = 0; $i < $random_amount_of_appointments; $i++) {
        $customer = Customer::find($customer_account_id);
        $medical_center_id = $faker->randomElement($medical_center_ids);
        $medical_center = MedicalCenter::find($medical_center_id);
        $doctor_ids = $medical_center->doctors->pluck('id')->toArray();

        $doctor_id = null;
        if (count($doctor_ids) > 0) {
          $doctor_id = $faker->randomElement($doctor_ids);
        }

        // Random start_date từ 1 tuần trước đến 1 tháng sau thời điểm hiện tại
        $start_date = $faker->dateTimeBetween('-1 weeks', '+1 month');

        // Lấy khoảng work_time của medical center
        $work_times = $medical_center->getWorkTimes();
        $work_start_time = Carbon::createFromFormat('H:i', $work_times['start']);
        $work_end_time = Carbon::createFromFormat('H:i', $work_times['end']);

        // Tạo các khoảng thời gian hợp lệ trong ngày
        $time_slots = [];
        for ($time = $work_start_time->copy(); $time->lt($work_end_time); $time->addHour()) {
          $time_slots[] = $time->copy();
        }

        // Chọn ngẫu nhiên thời gian bắt đầu từ các khoảng thời gian hợp lệ và chưa được sử dụng
        $valid_start_time = null;
        $attempts = 0;
        do {
          $random_time = $faker->randomElement($time_slots);
          $valid_start_time = Carbon::create(
            $start_date->format('Y'),
            $start_date->format('m'),
            $start_date->format('d'),
            $random_time->format('H'),
            $random_time->format('i'),
            $random_time->format('s')
          );
          $attempts++;
          if ($attempts > count($time_slots) || !in_array($valid_start_time, $used_start_times)) {
            break;
          }
        } while (in_array($valid_start_time, $used_start_times));

        // Thêm thời gian bắt đầu hợp lệ vào danh sách đã sử dụng
        if ($attempts <= count($time_slots)) {
          $used_start_times[] = $valid_start_time;

          $done = Carbon::now()->gt($valid_start_time) ? $faker->boolean(80) : false;

          $customer_pets = $customer->pets;
          $customer_adopted_pets = $customer->adoptedPets;

          $pet_id = null;
          if ($customer_pets->count() > 0 || $customer_adopted_pets->count() > 0) {
            $all_pets = $customer_pets->merge($customer_adopted_pets);
            $pet_id = $all_pets->random()->id;
          }

          $appointment = Appointment::factory()->create([
            'message' => $faker->paragraph(),
            'start_time' => $valid_start_time,
            'customer_id' => $customer_account_id,
            'pet_id' => $pet_id,
            'doctor_id' => $doctor_id,
            'done' => $done,
          ]);

          if ($done) {
            $random_type = rand(0, 1);

            if ($random_type === 0) {
              HistoryDiagnosis::create([
                'reason' => $faker->sentence(),
                'diagnosis' => $faker->randomElement($diagnosis),
                'treatment' => $faker->sentence(12),
                'health_condition' => $faker->paragraph(6),
                'note' => $faker->paragraph(4),
                'doctor_id' => $doctor_id,
                'pet_id' => $pet_id,
              ]);
            } else {
              HistoryVaccine::create([
                'vaccine' => $faker->randomElement($vaccines),
                'note' => $faker->paragraph(),
                'doctor_id' => $doctor_id,
                'pet_id' => $pet_id,
              ]);
            }
          }
        }
      }
    }
  }
}
