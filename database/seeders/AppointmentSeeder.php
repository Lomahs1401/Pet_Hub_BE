<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Doctor;
use App\Models\HistoryDiagnosis;
use App\Models\HistoryVaccine;
use App\Models\MedicalCenter;
use App\Models\RatingDoctor;
use App\Models\RatingDoctorInteract;
use App\Models\RatingMedicalCenter;
use App\Models\RatingMedicalCenterInteract;
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
      $customer = Customer::find($customer_account_id);
      $customer_pets = $customer->pets;
      $customer_adopted_pets = $customer->adoptedPets->pluck('pet_id');

      // Kiểm tra xem khách hàng có thú cưng nào không
      if ($customer_pets->count() === 0 && $customer_adopted_pets->count() === 0) {
        continue; // Bỏ qua khách hàng này nếu không có thú cưng
      }

      $random_amount_of_appointments = rand(0, 30);

      // Tạo danh sách các thời gian bắt đầu đã được sử dụng
      $used_start_times = [];

      for ($i = 0; $i < $random_amount_of_appointments; $i++) {
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

          $all_pets = $customer_pets->pluck('id')->merge($customer_adopted_pets);
          $pet_id = $all_pets->random();

          Appointment::factory()->create([
            'message' => $faker->paragraph(),
            'start_time' => $valid_start_time,
            'customer_id' => $customer_account_id,
            'pet_id' => $pet_id,
            'doctor_id' => $doctor_id,
            'done' => $done,
          ]);

          // Nếu appointment đã hoàn thành (done = true), có xác suất tạo RatingDoctor
          if ($done && $faker->boolean(80)) {
            // Kiểm tra xem khách hàng đã rating cho doctor này chưa
            $existingRatingDoctor = RatingDoctor::where('customer_id', $customer_account_id)
              ->where('doctor_id', $doctor_id)
              ->exists();

            if ($existingRatingDoctor) {
              continue; // Bỏ qua nếu đã có rating cho doctor này
            }

            // 35% xác suất cho rating 5 sao
            // 45% xác suất cho rating 4 sao
            // 6% xác suất cho rating 3 sao
            // 6% xác suất cho rating 2 sao
            // 8% xác suất cho rating 1 sao
            $ratings = array_merge(
              array_fill(0, 35, 5),
              array_fill(0, 45, 4),
              array_fill(0, 6, 3),
              array_fill(0, 6, 2),
              array_fill(0, 8, 1)
            );
            $rating = $faker->randomElement($ratings);

            // Random reply và reply_date (nếu có reply)
            $reply = $faker->boolean(40) ? $faker->paragraph(6) : null;
            $reply_date = $reply ? $faker->dateTimeBetween($valid_start_time, 'now') : null;

            $ratingDoctor = RatingDoctor::create([
              'rating' => $rating,
              'description' => $faker->paragraph(8),
              'customer_id' => $customer_account_id,
              'doctor_id' => $doctor_id,
              'reply' => $reply,
              'reply_date' => $reply_date,
              'created_at' => $valid_start_time,
              'updated_at' => $valid_start_time
            ]);

            // Tạo RatingDoctorInteract
            $num_likes = $faker->numberBetween(0, 5);
            $liked_customer_ids = $faker->randomElements($customer_account_ids, $num_likes);

            foreach ($liked_customer_ids as $liked_customer_id) {
              RatingDoctorInteract::create([
                'rating_doctor_id' => $ratingDoctor->id,
                'account_id' => $liked_customer_id,
              ]);
            }

            // Nếu có reply, quyết định liệu doctor có like hay không
            if ($reply) {
              if ($faker->boolean(50)) {
                $doctor_account_id = Doctor::find($doctor_id)->account_id;

                RatingDoctorInteract::create([
                  'rating_doctor_id' => $ratingDoctor->id,
                  'account_id' => $doctor_account_id,
                ]);
              }
            }
          }

          // Nếu appointment đã hoàn thành (done = true), có xác suất tạo RatingMedicalCenter
          if ($done && $faker->boolean(80)) {
            // Kiểm tra xem khách hàng đã có rating cho medical center này chưa
            $existingRatingMedicalCenter = RatingMedicalCenter::where('customer_id', $customer_account_id)
              ->where('medical_center_id', $medical_center_id)
              ->exists();

            if ($existingRatingMedicalCenter) {
              continue; // Bỏ qua nếu đã có rating cho medical center này
            }

            // 35% xác suất cho rating 5 sao
            // 45% xác suất cho rating 4 sao
            // 6% xác suất cho rating 3 sao
            // 6% xác suất cho rating 2 sao
            // 8% xác suất cho rating 1 sao
            $ratings = array_merge(
              array_fill(0, 35, 5),
              array_fill(0, 45, 4),
              array_fill(0, 6, 3),
              array_fill(0, 6, 2),
              array_fill(0, 8, 1)
            );
            $rating = $faker->randomElement($ratings);

            // Random reply và reply_date (nếu có reply)
            $reply = $faker->boolean(40) ? $faker->paragraph(6) : null;
            $reply_date = $reply ? $faker->dateTimeBetween($valid_start_time, 'now') : null;

            $ratingMedicalCenter = RatingMedicalCenter::create([
              'rating' => $rating,
              'description' => $faker->paragraph(8),
              'customer_id' => $customer_account_id,
              'medical_center_id' => $medical_center_id,
              'reply' => $reply,
              'reply_date' => $reply_date,
              'created_at' => $valid_start_time,
              'updated_at' => $valid_start_time
            ]);

            // Tạo RatingMedicalCenterInteract
            $num_likes = $faker->numberBetween(0, 5);
            $liked_customer_ids = $faker->randomElements($customer_account_ids, $num_likes);

            foreach ($liked_customer_ids as $liked_customer_id) {
              RatingMedicalCenterInteract::create([
                'rating_medical_center_id' => $ratingMedicalCenter->id,
                'account_id' => $liked_customer_id,
              ]);
            }

            // Nếu có reply, quyết định liệu medical center có like hay không
            if ($reply) {
              if ($faker->boolean(50)) {
                $medical_center_account_id = MedicalCenter::find($medical_center_id)->account_id;

                RatingMedicalCenterInteract::create([
                  'rating_medical_center_id' => $ratingMedicalCenter->id,
                  'account_id' => $medical_center_account_id,
                ]);
              }
            }
          }

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
