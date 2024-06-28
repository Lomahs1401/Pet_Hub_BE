<?php

namespace Database\Seeders;

use App\Models\AdoptRequest;
use App\Models\AidCenter;
use App\Models\Breed;
use App\Models\Customer;
use App\Models\HistoryAdopt;
use App\Models\Pet;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PetSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker::create();

    $dog_breed_ids = Breed::where('type', 'Dog')->pluck('id')->toArray();
    $cat_breed_ids = Breed::where('type', 'Cat')->pluck('id')->toArray();
    $aid_center_ids = AidCenter::pluck('id')->toArray();
    $customer_ids = Customer::pluck('id')->toArray();

    $number_of_pets = 100;

    for ($i = 0; $i < $number_of_pets; $i++) {
      $is_adopt_pet = $faker->boolean(40); // 40% aid_center_id; 60% customer_id

      if ($is_adopt_pet) {
        $adopted = $faker->boolean(60); // 60% được nhận nuôi, 40% chưa được nhận nuôi
        $aid_center_id = $faker->randomElement($aid_center_ids);
        $customer_id = null;
      } else {
        $adopted = null;
        $aid_center_id = null;
        $customer_id = $faker->randomElement($customer_ids);
      }

      // Loại pet (dog hoặc cat)
      $type = $faker->randomElement(['dog', 'cat']);

      // Random created_at trong khoảng từ 2 năm trước đến hiện tại
      $created_at = $faker->dateTimeBetween('-2 years', 'now');

      $pet = Pet::factory()->create([
        'name' => $faker->lastName(),
        'type' => $type,
        'age' => $faker->numberBetween(1, 16),
        'gender' => $faker->randomElement(['male', 'female']),
        'description' => $faker->paragraph(3),
        'image' => 'gs://new_petshop_bucket/pets/' . ($i + 1) . '.jpg',
        'is_purebred' => $faker->randomElement([true, false]),
        'status' => $adopted,
        'breed_id' => ($type == 'dog') ? $faker->randomElement($dog_breed_ids) : $faker->randomElement($cat_breed_ids),
        'aid_center_id' => $aid_center_id,
        'customer_id' => $customer_id,
        'created_at' => $created_at,
        'updated_at' => $created_at // giữ updated_at giống created_at cho consistency
      ]);

      // Nếu pet đã được nhận nuôi
      if ($adopted === true) {
        $num_requests = $faker->numberBetween(1, 3);
        $requests = [];

        for ($j = 0; $j < $num_requests; $j++) {
          $customer_id = $faker->randomElement($customer_ids);

          // Kiểm tra xem customer này đã tạo request cho pet này chưa
          if (array_key_exists($customer_id, array_column($requests, 'customer_id'))) {
            continue; // Bỏ qua nếu đã tạo request
          }

          $status = 'Pending';
          if ($j === $num_requests - 1) {
            $status = 'Done'; // Đảm bảo request cuối cùng là 'Done'
          } else {
            $status = $faker->randomElement(['Pending', 'Approved']);
          }

          $requests[] = [
            'status' => $status,
            'note' => $faker->sentence(),
            'aid_center_id' => $pet->aid_center_id,
            'pet_id' => $pet->id,
            'customer_id' => $customer_id,
            'created_at' => $created_at,
            'updated_at' => $created_at
          ];

          if ($status == 'Done') {
            break; // Thoát vòng lặp nếu request là 'Done'
          }
        }

        // Lưu các yêu cầu nhận nuôi vào bảng adopt_request
        foreach ($requests as $request) {
          AdoptRequest::create($request);
        }

        // Lấy request có status là 'Done'
        $doneRequest = array_filter($requests, function ($request) {
          return $request['status'] == 'Done';
        });

        // Nếu có request 'Done', lưu vào bảng history_adoptions
        if (!empty($doneRequest)) {
          $history_adoption_created_at = $faker->dateTimeBetween($created_at, 'now');
          HistoryAdopt::create([
            'customer_id' => $doneRequest[array_key_first($doneRequest)]['customer_id'],
            'pet_id' => $pet->id,
            'created_at' => $history_adoption_created_at,
            'updated_at' => $history_adoption_created_at
          ]);
        }
      }

      // Nếu pet chưa được nhận nuôi
      if ($adopted === false) {
        $num_requests = $faker->numberBetween(0, 2);
        $requests = [];
        $existing_customers = [];

        for ($j = 0; $j < $num_requests; $j++) {
          $status = $faker->randomElement(['Pending', 'Approved']);
          $customer_id = $faker->randomElement($customer_ids);

          // Kiểm tra xem customer này đã tạo request cho pet này chưa
          if (in_array($customer_id, $existing_customers)) {
            continue; // Bỏ qua nếu đã tạo request
          }

          $requests[] = [
            'status' => $status,
            'note' => $faker->sentence(),
            'aid_center_id' => $pet->aid_center_id,
            'pet_id' => $pet->id,
            'customer_id' => $customer_id,
            'created_at' => $created_at,
            'updated_at' => $created_at
          ];

          // Thêm customer_id vào danh sách đã tạo request
          $existing_customers[] = $customer_id;
        }

        // Lưu các yêu cầu nhận nuôi vào bảng adopt_request
        foreach ($requests as $request) {
          AdoptRequest::create($request);
        }
      }
    }
  }
}
