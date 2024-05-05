<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories_for_dog = [
            // Thức ăn và thực phẩm dinh dưỡng
            ['name' => 'Dog Food', 'target' => 'dog', 'type' => 'Food and Nutrition'],
            ['name' => 'Milk for Small and Large Dogs', 'target' => 'dog', 'type' => 'Food and Nutrition'],
            ['name' => 'Pate - Sauce', 'target' => 'dog', 'type' => 'Food and Nutrition'],
            ['name' => 'Treats and Chews', 'target' => 'dog', 'type' => 'Food and Nutrition'],
            
            // Đồ dùng, Đồ chơi, Phụ kiện
            ['name' => 'Dog Clothing', 'target' => 'dog', 'type' => 'Accessories and Toys'],
            ['name' => 'Dog Toys', 'target' => 'dog', 'type' => 'Accessories and Toys'],
            ['name' => 'Collars', 'target' => 'dog', 'type' => 'Accessories and Toys'],
            ['name' => 'Leashes', 'target' => 'dog', 'type' => 'Accessories and Toys'],
            ['name' => 'Muzzles', 'target' => 'dog', 'type' => 'Accessories and Toys'],
            ['name' => 'Water Bowls', 'target' => 'dog', 'type' => 'Accessories and Toys'],
            ['name' => 'Feeding Bowls', 'target' => 'dog', 'type' => 'Accessories and Toys'],
            ['name' => 'Grooming Brushes', 'target' => 'dog', 'type' => 'Accessories and Toys'],

            // Vệ sinh, Chăm sóc
            ['name' => 'Shampoos', 'target' => 'dog', 'type' => 'Hygiene and Care'],
            ['name' => 'Towels', 'target' => 'dog', 'type' => 'Hygiene and Care'],
            ['name' => 'Perfumes', 'target' => 'dog', 'type' => 'Hygiene and Care'],
            ['name' => 'Diapers', 'target' => 'dog', 'type' => 'Hygiene and Care'],
            ['name' => 'Trays', 'target' => 'dog', 'type' => 'Hygiene and Care'],
            
            // Chuồng, Balo, Đệm
            ['name' => 'Houses', 'target' => 'dog', 'type' => 'Accommodation'],
            ['name' => 'Backpacks', 'target' => 'dog', 'type' => 'Accommodation'],
            ['name' => 'Carrying Bags', 'target' => 'dog', 'type' => 'Accommodation'],
            ['name' => 'Mattresses, Beddings', 'target' => 'dog', 'type' => 'Accommodation'],
            
            // Thuốc, Thực phẩm chức năng
            ['name' => 'Veterinary Medicines', 'target' => 'dog', 'type' => 'Medicine and Functional Foods'],
            ['name' => 'Functional Foods', 'target' => 'dog', 'type' => 'Medicine and Functional Foods'],
        ];

        $categories_for_cat = [
            // Thức ăn và thực phẩm dinh dưỡng
            ['name' => 'Cat Food', 'target' => 'cat', 'type' => 'Food and Nutrition'],
            ['name' => 'Milk for Cats', 'target' => 'cat', 'type' => 'Food and Nutrition'],
            ['name' => 'Pate - Sauce', 'target' => 'cat', 'type' => 'Food and Nutrition'],
            ['name' => 'Treats', 'target' => 'cat', 'type' => 'Food and Nutrition'],
            ['name' => 'Catnip', 'target' => 'cat', 'type' => 'Food and Nutrition'],
            
            // Đồ dùng, Đồ chơi, Phụ kiện
            ['name' => 'Cat Clothing', 'target' => 'cat', 'type' => 'Accessories and Toys'],
            ['name' => 'Cat Toys', 'target' => 'cat', 'type' => 'Accessories and Toys'],
            ['name' => 'Collars', 'target' => 'cat', 'type' => 'Accessories and Toys'],
            ['name' => 'Leashes', 'target' => 'cat', 'type' => 'Accessories and Toys'],
            ['name' => 'Water Bowls', 'target' => 'cat', 'type' => 'Accessories and Toys'],
            ['name' => 'Feeding Bowls', 'target' => 'cat', 'type' => 'Accessories and Toys'],
            ['name' => 'Grooming Brushes', 'target' => 'cat', 'type' => 'Accessories and Toys'],
            
            // Vệ sinh, Chăm sóc
            ['name' => 'Cat Shampoo', 'target' => 'cat', 'type' => 'Hygiene and Care'],
            ['name' => 'Towels', 'target' => 'cat', 'type' => 'Hygiene and Care'],
            ['name' => 'Perfumes', 'target' => 'cat', 'type' => 'Hygiene and Care'],
            ['name' => 'Cat Litter', 'target' => 'cat', 'type' => 'Hygiene and Care'],
            
             // Chuồng, Chậu, Balo, Túi vận chuyển
            ['name' => 'Houses', 'target' => 'cat', 'type' => 'Accommodation'],
            ['name' => 'Backpacks', 'target' => 'cat', 'type' => 'Accommodation'],
            ['name' => 'Carrying Bags', 'target' => 'cat', 'type' => 'Accommodation'],
            ['name' => 'Mattresses, Beddings', 'target' => 'cat', 'type' => 'Accommodation'],

            // Thuốc, Thực phẩm chức năng
            ['name' => 'Veterinary Medicines', 'target' => 'cat', 'type' => 'Medicine and Functional Foods'],
            ['name' => 'Functional Foods', 'target' => 'cat', 'type' => 'Medicine and Functional Foods'],
        ];

        foreach ($categories_for_dog as $category) {
            ProductCategory::create($category);
        }

        foreach ($categories_for_cat as $category) {
            ProductCategory::create($category);
        }
    }
}
