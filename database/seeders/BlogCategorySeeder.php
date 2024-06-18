<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $blog_categories_for_dog = [
      'Health',
      'Training Tips',
      'Nutrition and Diet',
      'Puppy Care',
      'Behavioral Issues',
      'Fun Facts',
      'Adoption Stories',
      'Shared Experiences',
    ];

    foreach ($blog_categories_for_dog as $category) {
      BlogCategory::create([
        'name' => $category,
      ]);
    }
  }
}
