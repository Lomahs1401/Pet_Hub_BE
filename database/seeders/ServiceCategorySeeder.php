<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $categories_for_dog = [
      // Khám và tư vấn - Điều trị - Lưu trú
      ['name' => 'Medical Treatment', 'target' => 'dog', 'type' => 'Examination and Consultation - Treatment - Accommodation'],

      // Tiêm ngừa - Điều trị ký sinh trùng
      ['name' => 'Vaccination', 'target' => 'dog', 'type' => 'Vaccination - Parasite Treatment'],
      ['name' => 'Deworming Treatment', 'target' => 'dog', 'type' => 'Vaccination - Parasite Treatment'],
      ['name' => 'Parasite Control', 'target' => 'dog', 'type' => 'Vaccination - Parasite Treatment'],
      ['name' => 'Infection Prevention', 'target' => 'dog', 'type' => 'Vaccination - Parasite Treatment'],

      // Xét nghiệm máu và bệnh truyền nhiễm
      ['name' => 'Blood Test', 'target' => 'dog', 'type' => 'Blood Test and Infectious Disease Screening'],
      ['name' => 'Fecal Examination', 'target' => 'dog', 'type' => 'Blood Test and Infectious Disease Screening'],
      ['name' => 'Tick-borne Disease Test', 'target' => 'dog', 'type' => 'Blood Test and Infectious Disease Screening'],

      // Siêu âm - Xray
      ['name' => 'Ultrasound Scanning', 'target' => 'dog', 'type' => 'Ultrasound - Xray'],
      ['name' => 'Radiography', 'target' => 'dog', 'type' => 'Ultrasound - Xray'],
      ['name' => 'Diagnostic Imaging', 'target' => 'dog', 'type' => 'Ultrasound - Xray'],

      // Phẫu thuật
      ['name' => 'Orthopedic Surgery', 'target' => 'dog', 'type' => 'Surgery'], // Phẫu thuật chỉnh hình xương
      ['name' => 'Soft Tissue Surgery', 'target' => 'dog', 'type' => 'Surgery'], // Phẫu thuật mô mềm
      ['name' => 'Tumor Removal Surgery', 'target' => 'dog', 'type' => 'Surgery'], // Phẫu thuật cắt bỏ u
      ['name' => 'Respiratory Surgery', 'target' => 'dog', 'type' => 'Surgery'], // Phẫu thuật chỉnh sửa vấn đề hô hấp
      ['name' => 'Renal Surgery', 'target' => 'dog', 'type' => 'Surgery'], // Phẫu thuật thận
      ['name' => 'Gastrointestinal Surgery', 'target' => 'dog', 'type' => 'Surgery'], // Phẫu thuật tiêu hóa
      ['name' => 'Spaying/Neutering Surgery', 'target' => 'dog', 'type' => 'Surgery'], // Phẫu thuật loại bỏ thừa (thiến)
      ['name' => 'Foreign Body Removal Surgery', 'target' => 'dog', 'type' => 'Surgery'], // Phẫu thuật loại bỏ đồ vật nuốt phải
      ['name' => 'Dental Surgery', 'target' => 'dog', 'type' => 'Surgery'], // Phẫu thuật loại bỏ răng
      ['name' => 'Dewclaw Removal Surgery', 'target' => 'dog', 'type' => 'Surgery'], // Phẫu thuật nha chu
      ['name' => 'Orthopedic Surgery', 'target' => 'dog', 'type' => 'Surgery'], // Phẫu thuật mắt

      // Nha khoa
      ['name' => 'Dental Surgery', 'target' => 'dog', 'type' => 'Dental Care'],
      ['name' => 'Dental Cleaning', 'target' => 'dog', 'type' => 'Dental Care'],
      ['name' => 'Tooth Extraction', 'target' => 'dog', 'type' => 'Dental Care'],
    ];

    foreach ($categories_for_dog as $category) {
      ServiceCategory::create($category);
    }
  }
}
