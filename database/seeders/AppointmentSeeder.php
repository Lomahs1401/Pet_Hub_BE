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
      $random_amount_of_appointments = rand(0, 20);

      for ($i = 0; $i < $random_amount_of_appointments; $i++) {
        // Random start_time từ 1 tuần trước đến 2 tuần sau thời điểm hiện tại
        $start_time = $faker->dateTimeBetween('-1 week', '+2 weeks');

        // Kiểm tra nếu thời gian hiện tại sau start_time, done là true với tỉ lệ 80%, ngược lại là false
        $done = Carbon::now()->gt($start_time) ? $faker->boolean(80) : false;

        $customer = Customer::find($customer_account_id);
        $medical_center_id = $faker->randomElement($medical_center_ids);

        // Lấy danh sách các pets thuộc customer
        $customer_pets = $customer->pets;
        // Lấy danh sách các pets đã nhận nuôi bởi customer
        $customer_adopted_pets = $customer->adoptedPets;

        // Random pet_id từ danh sách pets thuộc customer hoặc pets đã nhận nuôi
        $pet_id = null;
        if ($customer_pets->count() > 0 || $customer_adopted_pets->count() > 0) {
          $all_pets = $customer_pets->merge($customer_adopted_pets);
          $pet_id = $all_pets->random()->id;
        }

        // Lấy danh sách các doctors thuộc medical center đã random
        $medical_center = MedicalCenter::find($medical_center_id);
        $doctor_ids = $medical_center->doctors->pluck('id')->toArray();

        $doctor_id = null;
        if (count($doctor_ids) > 0) {
          $doctor_id = $faker->randomElement($doctor_ids);
        }

        Appointment::factory()->create([
          'message' => $faker->paragraph(),
          'start_time' => $start_time,
          'customer_id' => $customer_account_id,
          'medical_center_id' => $medical_center_id,
          'pet_id' => $pet_id,
          'doctor_id' => $doctor_id,
          'done' => $done,
        ]);

        // Nếu appointment đã hoàn thành (done là true)
        if ($done) {
          // Random loại history (0: history_diagnosis, 1: history_vaccine)
          $random_type = rand(0, 1);

          // Tạo history_diagnosis
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
          } else { // Tạo history_vaccine
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
