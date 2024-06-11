<?php

namespace Database\Seeders;

use App\Models\MedicalCenter;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ServiceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker::create();

    // Get all service categories
    $medical_centers_id = MedicalCenter::pluck('id')->toArray();

    $servicesData = [
      // Examination and Consultation - Treatment - Accommodation
      [
        [
          'name' => 'General Health Checkup',
          'description' => 'Regular health check-up for your dog',
          'price' => 500000,
        ],
        [
          'name' => 'Internal Medicine Consultation',
          'description' => 'Regular health check-up for your dog',
          'price' => 830000,
        ],
        [
          'name' => 'Skin Disease Treatment',
          'description' => 'Regular health check-up for your dog',
          'price' => 720000,
        ],
        [
          'name' => 'Hospitalization',
          'description' => 'Regular health check-up for your dog',
          'price' => 780000,
        ],
        [
          'name' => 'Emergency Care',
          'description' => 'Regular health check-up for your dog',
          'price' => 1200000,
        ],
      ],

      // Vaccination - Parasite Treatment
      [
        [
          'name' => 'Core Vaccination Package',
          'description' => 'Regular health check-up for your dog',
          'price' => 800000,
        ],
        [
          'name' => 'Rabies Vaccination',
          'description' => 'Regular health check-up for your dog',
          'price' => 660000,
        ],
        [
          'name' => 'Flea and Tick Control',
          'description' => 'Regular health check-up for your dog',
          'price' => 520000,
        ],
        [
          'name' => 'Heartworm Prevention',
          'description' => 'Regular health check-up for your dog',
          'price' => 920000,
        ],
        [
          'name' => 'Parasite Treatment',
          'description' => 'Regular health check-up for your dog',
          'price' => 800000,
        ],
      ],

      // Blood Test and Infectious Disease Screening
      [
        [
          'name' => 'Complete Blood Count (CBC)',
          'description' => 'Regular health check-up for your dog',
          'price' => 2000000,
        ],
        [
          'name' => 'Heartworm Test',
          'description' => 'Regular health check-up for your dog',
          'price' => 1100000,
        ],
        [
          'name' => 'Lyme Disease Test',
          'description' => 'Regular health check-up for your dog',
          'price' => 1200000,
        ],
        [
          'name' => 'Feline Leukemia Test',
          'description' => 'Regular health check-up for your dog',
          'price' => 1300000,
        ],
        [
          'name' => 'Feline Immunodeficiency Virus (FIV) Test',
          'description' => 'Regular health check-up for your dog',
          'price' => 1400000,
        ],
      ],

      // Ultrasound - Xray
      [
        [
          'name' => 'Abdominal Ultrasound',
          'description' => 'Regular health check-up for your dog',
          'price' => 1890000,
        ],
        [
          'name' => 'Cardiac Ultrasound',
          'description' => 'Regular health check-up for your dog',
          'price' => 17200000,
        ],
        [
          'name' => 'X-ray Imaging',
          'description' => 'Regular health check-up for your dog',
          'price' => 1680000,
        ],
        [
          'name' => 'Bone Fracture Diagnosis',
          'description' => 'Regular health check-up for your dog',
          'price' => 940000,
        ],
        [
          'name' => 'Tumor Localization',
          'description' => 'Regular health check-up for your dog',
          'price' => 780000,
        ],
      ],

      // Surgery
      [
        [
          'name' => 'Spaying Surgery (Female)',
          'description' => 'Regular health check-up for your dog',
          'price' => 1360000,
        ],
        [
          'name' => 'Tumor Localization',
          'description' => 'Regular health check-up for your dog',
          'price' => 1360000,
        ],
        [
          'name' => 'Orthopedic Surgery',
          'description' => 'Regular health check-up for your dog',
          'price' => 1520000,
        ],
        [
          'name' => 'Soft Tissue Surgery',
          'description' => 'Regular health check-up for your dog',
          'price' => 1140000,
        ],
        [
          'name' => 'Dental Surgery',
          'description' => 'Regular health check-up for your dog',
          'price' => 900000,
        ],
      ],

      // Dental Care
      [
        [
          'name' => 'Dental Examination',
          'description' => 'Regular health check-up for your dog',
          'price' => 524000,
        ],
        [
          'name' => 'Teeth Cleaning',
          'description' => 'Regular health check-up for your dog',
          'price' => 787000,
        ],
        [
          'name' => 'Tooth Extraction',
          'description' => 'Regular health check-up for your dog',
          'price' => 712000,
        ],
        [
          'name' => 'Dental X-ray',
          'description' => 'Regular health check-up for your dog',
          'price' => 12000000,
        ],
        [
          'name' => 'Gum Disease Treatment',
          'description' => 'Regular health check-up for your dog',
          'price' => 428000,
        ],
      ],
    ];

    foreach ($servicesData as $service_category_id => $services) {
      foreach ($services as $service) {
        // Create the service
        Service::create([
          'name' => $service['name'],
          'description' => $service['description'],
          'price' => $service['price'],
          'image' => $faker->url(),
          'sold_quantity' => rand(3, 20),
          'medical_center_id' => $faker->randomElement($medical_centers_id),
          'service_category_id' => $service_category_id + 1,
        ]);
      }
    }
  }
}
