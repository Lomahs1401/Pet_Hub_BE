<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dog_food_products = [
            [
                'name' => 'Thức ăn cho chó Ganador Adult Salmon & Rice',
                'description' => 'Thức ăn cho chó Ganador Adult Salmon & Rice là thực phẩm dành cho chó trưởng thành với công thức chế biến được nghiên cứu bởi các chuyên gia',
                'price' => 29000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Thuc-an-cho-cho-Ganador-Adult-Salmon-amp-Rice.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Pedigree Puppy vị gà và trứng 400g',
                'description' => 'Thức ăn dạng hạt Pedigree Puppy vị gà sốt trứng đem đến những bữa ăn đầy đủ chất dinh dưỡng, cùng hương vị ngon miệng kích thích các bé ăn uống.',
                'price' => 57000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Pedigree-Puppy-vi-ga-va-trung-400g.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Thức ăn hạt Dog On Red - Protein 33%',
                'description' => 'Thức ăn hạt Dog On Red khô dành cho chó với hàm lượng đạm lên tới 33% giúp cho các boss nhỏ có thể nhanh chóng tăng cân, phát triển thể trạng khoẻ mạnh.',
                'price' => 392000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Thuc-an-hat-Dog-On-Red-Protein-33.png',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Thức ăn hạt Dog On Green 5kg - Protein 24%',
                'description' => 'Với hàm lượng dinh dưỡng cao cùng mùi vị ngon miệng, thức ăn hạt Dog On Green thích hợp dành cho các bé cún biếng ăn đang còi cọc ốm yếu.',
                'price' => 370000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Thuc-an-hat-Dog-On-Green-5kg-Protein.png',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Thức ăn hạt Hello Dog 400g dành cho chó',
                'description' => 'Thức ăn cho cún dạng hạt Hello Dog 400g là sản phẩm cung cấp đầy đủ và cân bằng chất dinh dưỡng đem lại nguồn năng lượng hàng ngày cho các boss.',
                'price' => 25000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Thuc-an-hat-Hello-Dog-3kg-danh-cho-cho.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt cho chó A Pro I.Q Fomula Dog Food - 20kg (500gx40gói)',
                'description' => 'Thức ăn hạt cho chó A Pro I.Q Fomula Dog Food là sản phẩm chất lượng dành cho cún cưng có xuất xứ từ Thái Lan. Sản phẩm có công thức chế biến an toàn',
                'price' => 805000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-cho-cho-A-Pro-I.Q-Fomula-Dog-Food-20kg-500gx40goi.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt Classic Pets Small Breed Dog Beef Flavour - 2kg',
                'description' => 'Thức ăn hạt Classic Pets Small Breed Dog Beef Flavour dành cho cún con với hàm lượng dinh dưỡng cao, mùi vị thơm ngon dễ dàng để các boss hấp thụ.',
                'price' => 110000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/thuc-an-cho-cho-truong-thanh-co-lon-classic-pets-beef-flavour.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Zoi Dog thức ăn cho chó 1kg',
                'description' => 'Thức ăn hạt cho chó trưởng thành hạt Zoi Dog Food có thương hiệu từ Thái Lan nổi tiếng về chất lượng, uy tín chắc chắn sẽ đem đến những bữa ăn ngon miệng',
                'price' => 35000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Zoi-Dog-thuc-an-cho-cho-20kg.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Thức ăn Dog Mania cho chó mọi lứa tuổi 1kg',
                'description' => 'Thức ăn Dog Mania là thực phẩm thức ăn hạt có nển tảng dinh dưỡng dồi dào giúp thú cưng phát triển toàn diện về thể chất.',
                'price' => 64000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Thuc-an-Dog-Mania-cho-cho-moi-lua-tuoi-5kg.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Thức ăn hạt Dog Classic cho chó mọi lứa tuổi 5kg',
                'description' => 'Thức ăn hạt Dog Classic cho chó mọi lứa tuổi được sản xuất bằng nguyên liệu cao cấp với thịt gà tươi và độ thơm ngon từ mỡ gà giúp thú cưng phát triển toàn diện.',
                'price' => 332000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/thuc-an-cho-cho-gia-re-dog-classic-5kg-1.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Thức ăn hạt hữu cơ ANF 6Free cho chó',
                'description' => 'Thức ăn hạt hữu cơ ANF 6Free cho chó được làm từ nguyên liệu hữu cơ tự nhiên an toàn và có lợi cho sức khoẻ, hạn chế tình trạng dị ứng thức ăn',
                'price' => 522000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Thuc-an-hat-huu-co-ANF-6Free-cho-cho.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Classic Pets Puppy dành cho chó con vị sữa 400g',
                'description' => 'Hạt Classic Pets Puppy phù hợp với các bé cún trong giai đoạn từ 2-12 tháng tuổi. Hương vị sữa thơm ngon, béo ngậy sẽ kích thích các bé ăn không ngừng.',
                'price' => 32000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Classic-Pets-Puppy-danh-cho-cho-con-vi-sua-400g.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt Smartheart Power Pack Puppy dành cho giống chó cỡ vừa và lớn',
                'description' => 'Hạt Smartheart Power Pack Puppy được phát triển riêng cho giống chó có sức mạnh thể chất, cần nhiều năng lượng như Alaska, Pit Bull hay Rottweiler.',
                'price' => 220000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Smartheart-Power-Pack-Puppy-danh-cho-giong-cho-co-vua-va-lon.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Smartheart Adult Lamb vị thịt cừu dành cho chó trưởng thành',
                'description' => 'Hạt Smartheart Adult Lamb dành cho các bạn cún từ 1 tuổi trở lên, đã phát triển toàn diện về hệ tiêu hóa. Lúc này, các bé cần rất nhiều năng lượng để hoạt động, vui chơi. Chính vì vậy, loại thức ăn cũng cần phù hợp với nhu cầu đó.',
                'price' => 29000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/thuc-an-hat-cho-cho-smartheart-adult-lamb-rice-flavour-5f4732e96c42f-27082020111329.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt Smartheart Small Breeds Puppy cho chó nhỏ dưới 12 tháng tuổi',
                'description' => 'Hạt Smartheart Small Breeds Puppy dành cho chó cỡ nhỏ đang trong độ tuổi phát triển, được sản xuất trên dây chuyền hiện đại, uy tín, an toàn cho cún cưng.',
                'price' => 120000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/thuc-an-hat-cho-cho-smartheart-small-breed-puppy-5f477d829fc82-27082020163146.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Smartheart Small Breeds dành cho các giống chó nhỏ',
                'description' => 'Hạt Smartheart Small Breeds phù hợp với các giống chó nhỏ như Chihuahua, Phốc sóc hay Poodle. Những loại chó cỡ nhỏ có tuổi thọ trung bình cao hơn các giống cỡ lớn, và chúng cũng cần chế độ dinh dưỡng đặc biệt hơn.',
                'price' => 30000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Smartheart-Small-Breeds-danh-cho-cac-giong-cho-nho.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt Smartheart Gold Puppy dành cho chó con dưới 12 tháng tuổi',
                'description' => 'Hạt Smartheart Gold Puppy phù hợp với các bé cún dưới 12 tháng, dễ tiêu hóa và giàu dưỡng chất để cún lớn nhanh, khỏe mạnh.',
                'price' => 132000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Smartheart-Gold-Puppy-danh-cho-cho-con-duoi-12-thang-tuoi.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Smartheart Gold Adult dành cho chó trưởng thành',
                'description' => 'Hạt Smartheart Gold Adult được thiết kế riêng cho dòng chó trưởng thành, đã phát triển cả về răng và hệ tiêu hóa, giúp loại bỏ cả mảng bám trên răng cún.',
                'price' => 119000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Smartheart-Gold-Adult-danh-cho-cho-truong-thanh.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt Smartheart Gold Lamb & Rice dành cho chó cỡ nhỏ',
                'description' => 'Hạt Smartheart Gold Lamb & Rice đến từ thương hiệu thức ăn cho chó mèo nổi tiếng, được thiết kế dành cho các giống chó cỡ mini với công thức đặc biệt.',
                'price' => 124000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Smartheart-Gold-Lamb-amp-Rice-danh-cho-cho-co-nho.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Pedigree Adult cho chó trưởng thành vị thịt bò, thịt gà, rau',
                'description' => 'Hạt Pedigree Adult phù hợp với mọi giống chó từ 1 tuổi trở lên, đã phát triển đầy đủ về mặt thể chất. Sản phẩm có nhiều năng lượng và được bổ sung vitamin.',
                'price' => 115000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Pedigree-Adult-cho-cho-truong-thanh-vi-thit-bo-thit-ga-rau-15kg.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Thức ăn chó Bowwow Cheese Sand Mix 120g',
                'description' => 'Thức ăn chó Bowwow Cheese Sand Mix là dòng thức ăn khô hỗn hợp giàu dinh dưỡng, ít chất béo, ít muối đảm bảo cho sự phát triển khỏe mạnh của thú cưng.',
                'price' => 65000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Thuc-an-cho-Bowwow-Cheese-Sand-Mix-120g.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Thức ăn hữu cơ Origi-7 cho chó đa dạng mùi vị',
                'description' => 'Thức ăn hữu cơ Origi-7 cho chó là dòng sản phẩm cao cấp từ Hàn Quốc, phù hợp với mọi giống chó ở mọi độ tuổi với hương vị thịt bò, cá hồi, gà, cừu.',
                'price' => 260000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Thuc-an-huu-co-Origi-7-cho-cho-da-dang-mui-vi.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt Zenith Adult cho chó trưởng thành 3kg',
                'description' => 'Hạt Zenith Adult cho chó trưởng thành từ 1 tuổi trở lên, có hệ tiêu hóa phát triển, được chế biến từ thịt cừu tươi, cá hồi đem đến nguồn Protein cao cấp.',
                'price' => 494000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Zenith-Adult-cho-cho-truong-thanh-3kg.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt mềm Zenith Senior cho chó lớn tuổi',
                'description' => 'Hạt mềm Zenith Senior dành riêng cho chó lớn tuổi với nhu cầu dinh dưỡng đặc biệt, giải quyết nhiều tình trạng bệnh lý, điều chỉnh về dinh dưỡng.',
                'price' => 229000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-mem-Zenith-Senior-cho-cho-lon-tuoi.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt Natural Core Bene M50 vị thịt gà & cá hồi',
                'description' => 'Hạt Natural Core Bene M50 được chế biến từ các nguyên liệu hữu cơ tự nhiên tốt cho sức khỏe thú cưng, đem tới chế độ dinh dưỡng cân bằng cho cún cưng.',
                'price' => 68000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Natural-Core-Bene-M50-vi-thit-ga-amp-ca-hoi.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Thức ăn dành cho chó trưởng thành Pro-Dog Adult túi 400g',
                'description' => 'Thức ăn dành cho chó trưởng thành Pro-Dog Adult đã phát triển toàn diện về thể chất. Vì vậy, sản phẩm có nguồn năng lượng dồi dào, cho các bé hoạt động cả ngày dài.',
                'price' => 32000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Pro-Dog-Adult-cho-cho-truong-thanh-tui-400g.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Thức ăn dành cho chó con Pro-Dog Puppy túi 400g',
                'description' => 'Hạt Pro-Dog Puppy cho chó con từ 2 tới 12 tháng tuổi, đem đến nguồn dinh dưỡng hoàn chỉnh với Protein từ động vật và tinh bột từ các loại ngũ cốc tự nhiên.',
                'price' => 33000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Pro-Dog-Puppy-cho-cho-con-tui-400g.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Thức ăn hạt cao cấp dành cho chó Pro Pet Grand Magic túi 1kg',
                'description' => 'Hạt Pro-Pet Grandmagic được làm từ những nguyên liệu tự nhiên như thịt bò, gà và hải sản, đem đến cho cún cưng nguồn Protein chất lượng.',
                'price' => 87000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Pro-Pet-Grandmagic-cho-cho-tu-3-thang-tuoi.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Thức ăn cho chó nhỏ hạt Purina Pro Plan nhập khẩu',
                'description' => 'Thức ăn cho chó nhỏ hạt Purina Pro Plan nhập khẩu phù hợp với các giống chó cỡ nhỏ, được chia thành nhiều dòng sản phẩm phù hợp với từng nhu cầu của cún cưng.',
                'price' => 245000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Hat-Purina-ProPlan-Adult-Small-amp-Mini-2.5kg.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Purina ProPlan Puppy Small & Mini dành cho cún con cỡ nhỏ',
                'description' => 'Hạt Purina ProPlan Puppy Small & Mini là dòng sản phẩm thức ăn cao cấp dành cho các bé cún từ 2 tới 12 tháng tuổi thuộc dòng chó kích thước nhỏ.',
                'price' => 289000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/3929ded2c406c94341d3d05891faa56c.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Thức ăn hạt Purina ProPlan Puppy dành cho chó con cỡ trung bình',
                'description' => 'Thức ăn hạt Purina ProPlan Puppy được tạo ra dành riêng cho các bé cún đang trong độ tuổi phát triển phù hợp với các dòng chó vóc dáng trung bình.',
                'price' => 585000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/ezgif-2-aa1be18845.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Purina ProPlan Adult Chicken Formula cho chó trưởng thành 2.5kg',
                'description' => 'Hạt Purina ProPlan Adult Chicken Formula có thành phần dinh dưỡng 44hoàn chỉnh với nhiều Protein giúp cún khỏe mạnh, phù hợp với các bé cún trên 1 tuổi.',
                'price' => 442000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/141c5a1db246ecd3fe0d20560c9c29af.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Thức ăn hạt Reflex Plus cho chó vị thịt gà và cá hồi',
                'description' => 'Thức ăn hạt Reflex Plus cho chó có hai vị thịt gà và cá hồi hấp dẫn, có hàm lượng Protein cao, đáp ứng đầy đủ cho nhu cầu phát triển của cún cưng.',
                'price' => 189000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/Reflex-Plus.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Royal Canin Poodle Adult cho chó Poodle trên 1 tuổi',
                'description' => 'Hạt Royal Canin Poodle Adult có xuất xứ từ Pháp dành riêng cho cho giống chó Poodle, cung cấp nguồn dinh dưỡng tốt để chó có một bộ lông khỏe đẹp.',
                'price' => 154000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/54e27e2b02b0e9d12811aeefcb9b2f72.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt Royal Canin Poodle Puppy dành cho chó Poodle dưới 1 tuổi - 500g',
                'description' => 'Hạt Royal Canin Poodle Puppy sở hữu công thức dành riêng cho giống chó Poodle được nhiều người yêu thích. Poodle có bộ lông hết sức đặc biệt, dài ra liên tục chứ không rụng thường xuyên như các giống chó khác. Cũng vì thế, giống Poodle sẽ cần chế độ dinh dưỡng đặc biệt để nuổi dưỡng bộ lông khỏe đẹp.',
                'price' => 202000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/ezgif-2-d9021b8888.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Royal Canin Mini Adult dành cho các dòng chó cỡ nhỏ',
                'description' => 'Hạt Royal Canin Mini Adult được phát triển riêng cho các dòng chó kích thước nhỏ, nặng dưới 10kg. Thức ăn phù hợp với các bé từ 10-12 tháng tuổi trở lên, đã có hệ tiêu hóa phát triển đầy đủ. Sản phẩm giúp duy trì mức năng lượng cho cún hoạt động hàng ngày, đồng thời được điều chỉnh dinh dưỡng giúp kéo dài tuổi thọ. Các giống chó nhỏ vốn có tuổi thọ cao hơn chó lớn, vậy nên dinh dưỡng cũng cần được chú ý.',
                'price' => 239000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/thuc-an-hat-cho-cho-royal-canin-mini-adult-tu-10-thang-tuoi-5f30d3ea02564-10082020115818.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt Ganador Premium Adult vị thịt cừu dành cho chó trưởng thành 400g',
                'description' => 'Hạt Ganador Premium Adult là dòng sản phẩm cao cấp cho chó trưởng thành, đảm bảo nhu cầu năng lượng rất lớn của thú cưng và bổ sung thêm dưỡng chất.',
                'price' => 27000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/3e5db71b2cdf16d568415e20a78f9b04.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Ganador Puppy cho chó con vị trứng sữa 3kg',
                'description' => 'Hạt Ganador Puppy cho chó con từ 2 cho tới 12 tháng tuổi. Đây là độ tuổi cần sự chăm sóc đặc biệt của chủ nhân, có yêu cầu dinh dưỡng riêng biệt.',
                'price' => 163000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/ganador_d6cc687812ea4badb5d5b09ab918506c_master.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Thức ăn hạt Ganador Adult cho chó trưởng thành vị gà nướng',
                'description' => 'Thức ăn hạt Ganador Adult dành cho các bé cún từ 1 tuổi trở lên, cung cấp nhiều năng lượng cho hoạt động mỗi ngày, bổ sung thêm vitamin và khoáng chất.',
                'price' => 29000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/1044d2d1e94921730b18552b628160a1.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Thức ăn cho chó Fib\'s dạng viên, dành cho chó trưởng thành',
                'description' => 'Thức ăn cho chó Fib\'s phù hợp với mọi giống chó ở mọi độ tuổi, giúp cung cấp đầy đủ năng lượng, Vitamin và khoáng chất giúp cún phát triển toàn diện.',
                'price' => 20000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/111bf08f63f336d2086dc581969d5d28.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt Smart Heart cho chó trưởng thành vị thịt bò 1.5kg',
                'description' => 'Hạt Smart Heart cho chó trưởng thành có hương vị thịt bò nước thơm ngon làm bé cún nào cũng phải thích thú, có lượng Protein rất cao từ các nguồn tự nhiên.',
                'price' => 95000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/ffd19f9a523e3b61e807dce7fb8439cf.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt Pedigree Puppy cho chó con cung cấp đầy đủ dinh dưỡng 400g',
                'description' => 'Hạt Pedigree Puppy cho chó con từ 2 tới 12 tháng tuổi với công thức hoàn hảo để các bé phát triển khỏe mạnh, lớn nhanh, lanh lợi.',
                'price' => 57000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/pp-3kg-0165.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt Ganador Premium Puppy 500g dành cho chó con từ 2-12 tháng',
                'description' => 'Hạt Ganador Premium Puppy phù hợp với các bé cún trong độ tuổi từ 2-12 tháng - độ tuổi phát triển mạnh mẽ của chó, cần nhiều Protein và chất dinh dưỡng.',
                'price' => 35000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/q2_5e708bac911e4b4bb978f080575fb945.png',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Hạt tươi Cuncun Plus cho chó hàm lượng Protein cao 1.2kg',
                'description' => 'Hạt tươi Cuncun Plus cho chó với hàm lượng Protein từ thịt tươi vượt trội, bổ sung nhiều dưỡng chất, phù hợp với các giống cún nhỏ, có lông dày như Poodle',
                'price' => 150000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/64-thuc-an-hat-vien-cho-cun-Cuncun-12kg-150k-1.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Hạt mềm Zenith cho chó con dưới 12 tháng tuổi gói 1.2kg',
                'description' => 'Thức ăn dạng hạt mềm Zenith giúp chó con dễ nhai và dễ hấp thụ. Sản phẩm được bổ sung Vitamin và khoáng chất giúp hỗ trợ quá trình phát triển của chó.',
                'price' => 230000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/11-thuc-an-hat-mem-Zenith-cho-cho-con-1.2kg-2.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            [
                'name' => 'Thức ăn hạt Smart Heart vị thịt bò cho chó con gói 400 gram',
                'description' => 'Hạt thức ăn cho chó con Smart Heart dành cho chó trong độ tuổi từ 2-10 tháng tuổi. Sản phẩm cung cấp đầy đủ dưỡng chất giúp chó có điều kiện tốt nhất trong giai đoạn phát triển.',
                'price' => 32000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/10-hat-thuc-an-cho-cho-con-SmartHeat-vi-thit-bo-400-gram-3.jpg',
                'status' => true,
                'product_category_id' => 1
            ],
            [
                'name' => 'Thức ăn cho chó Royal Canin Puppy Mini 800gr',
                'description' => 'Thức ăn dạng hạt dành cho các dòng chó cỡ nhỏ như Phốc sóc (Pomeranian, Pug, Poodle, Corgi…), trong giai đoạn từ 2-10 tháng tuổi.',
                'price' => 251000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/dog_foods/1-Royal-Canin-3.jpg',
                'status' => true,
                'product_category_id' => 1,
            ],
            // Thêm dữ liệu cho các sản phẩm khác
        ];

        $dog_milk_products = [
            [
                'name' => 'Sữa Dr.Kyan Petsure Premium 400g dành cho chó mèo',
                'description' => 'Sữa Dr.Kyan Petsure Premium 400g là dòng sữa bột bổ sung cho chó mèo được nhiều người ưa thích, phù hợp cho mọi thời điểm phát triển của thú cưng.',
                'price' => 207000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-bot-dinh-duong-cao-cap-cho-cho-petsure-premium-drkyan-G5530-1650969062832.png',
                'status' => true,
                'product_category_id' => 2,
            ],
            [
                'name' => 'Sữa Petsure Premium cho chó mèo hộp 110g',
                'description' => 'Sữa bột Petsure Premium cho chó mèo được sản xuất bởi thương hiệu Dr. Kyan danh tiếng, là dòng sữa bổ sung dinh dưỡng tuyệt vời cho các bé thú cưng.',
                'price' => 51000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-bot-dinh-duong-cao-cap-cho-cho-petsure-premium-drkyan-G5530-1650969062832.png',
                'status' => true,
                'product_category_id' => 2,
            ],
            [
                'name' => 'Sữa bột Colostrum chó mèo hộp 100g',
                'description' => 'Sữa bột Colostrum chó mèo được nhiều người sử dụng thay sữa mẹ trong các trường hợp chó mèo mồ côi hay mẹ thiếu sữa, có đầy đủ chất dinh dưỡng cho thú cưng.',
                'price' => 39000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-bot-colostrum-cho-meo-hop-100g.jpg',
                'status' => true,
                'product_category_id' => 2
            ],
            [
                'name' => 'Sữa Goat Gold Plus cho chó mèo hộp 200g hàng Thái Lan',
                'description' => 'Sữa Goat Gold Plus cho chó mèo được nhiều người nuôi tin dùng, cung cấp đầy đủ các nhu cầu dinh dưỡng thiết yếu của thú cưng.',
                'price' => 135000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-goat-gold-plus-cho-cho-meo-200g-thai-lan.jpg',
                'status' => true,
                'product_category_id' => 2,
            ],
            [
                'name' => 'Sữa bột AG-Science cho chó mèo hộp 250g',
                'description' => 'Sữa bột AG-Science cho chó mèo được làm từ bột sữa dê nguyên chất, đem lại nhiều nguồn dưỡng chất cần thiết, giúp cơ thể thú cưng phát triển khoẻ mạnh.',
                'price' => 129000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-bot-ag-science-250g.jpg',
                'status' => true,
                'product_category_id' => 2,
            ],
            [
                'name' => 'Sữa bột Goat Gold cho chó mèo hộp 200g hàng nhập Thái Lan',
                'description' => 'Sữa bột Goat Gold cho chó mèo được làm từ sữa dê giàu dinh dưỡng, hỗ trợ hiệu quả cho sự phát triển của thú cưng, là món đồ uống khoái khẩu của các bé.',
                'price' => 51000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-bot-goat-gold-cho-meo-hop-200g.jpg',
                'status' => true,
                'product_category_id' => 2,
            ],
            [
                'name' => 'Sữa tươi Pet Own cho chó mèo hộp 1 lít nhập khẩu Úc',
                'description' => 'Sữa tươi Pet Own cho chó mèo phù hợp với cả chó mèo con, cung cấp đầy đủ dinh dưỡng cho các bé phát triển, ngoài ra còn được bổ sung Glucosamine cho khớp.',
                'price' => 118000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-tuoi-pet-own-hop-1-lit.jpg',
                'status' => true,
                'product_category_id' => 2
            ],
            [
                'name' => 'Sữa Wow Milk dành cho chó gói 100g',
                'description' => 'Sữa Wow Milk dành cho chó sở hữu công thức dinh dưỡng cân giúp bổ sung dinh dưỡng cho chó con và còn có thể dùng thay sữa mẹ.',
                'price' => 36000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-wow-milk-cho-cho-100g.jpg',
                'status' => true,
                'product_category_id' => 2,
            ],
            [
                'name' => 'Sữa Dr Kyan cho chó con và chó trưởng thành hộp 110g',
                'description' => 'Sữa Dr Kyan cho chó giúp bổ sung thêm nhiều năng lượng, Vitamin và khoáng chất cho cún yêu nhà bạn. Ngoài những bữa ăn hàng ngày, bạn có thể sử dụng sữa DR Kyan như bữa phụ để giúp cún nhà bạn cảm thấy ngon miệng hơn, nạp thêm được nhiều chất dinh dưỡng hơn. Song song sử dụng sữa kèm các bữa ăn chính sẽ góp phần cho cún cơ thể phát triển khoẻ mạnh, toàn diện.',
                'price' => 50000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-bot-DrKyan-cho-cho-110g-50k-1.jpg',
                'status' => true,
                'product_category_id' => 2,
            ],
            [
                'name' => 'Sữa chó Esbilac bổ sung dưỡng chất cho chó sơ sinh 340g',
                'description' => 'Sữa chó Esbilac là dòng sữa dành cho chó sơ sinh, chó non để sử dụng thay cho nguồn sữa mẹ. Đây là nguồn sữa cực kỳ thiết yếu với các trường hợp chó mất mẹ, bị mẹ bỏ hoặc đang bị suy dinh dưỡng, thiếu chất.',
                'price' => 535000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-cho-Esbilac-2.jpg',
                'status' => true,
                'product_category_id' => 2
            ],
            [
                'name' => 'Sữa Biomilk cho chó mèo bổ sung dưỡng chất gói 100g',
                'description' => 'Sữa Biomilk cho chó mèo giúp cung cấp thêm nhiều dưỡng chất cần thiết, giúp thú cưng luôn mạnh khoẻ, phát triển toàn diện, ngăn ngừa được bệnh tật.',
                'price' => 39000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-Biomilk-cho-cho-meo-1247x1200.jpg',
                'status' => true,
                'product_category_id' => 2,
            ],
        ];

        $dog_pate_sauce_products = [
            [
                'name' => 'Pate Pedigree Adult gói 80g thành phần gà, thịt gò, gan nướng',
                'description' => 'Pate Pedigree Adult gói 80g được chế biến từ những thành phần tự nhiên dành cho cún đã phát triển toàn diện, là nguồn Protein cao cấp với thịt gà, thịt bò.',
                'price' => 15000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-pedigree-adult-goi-80g.jpg',
                'status' => true,
                'product_category_id' => 3,
            ],
            [
                'name' => 'Pate Pedigree Puppy gói 80g thành phần thịt gà, gan, trứng, rau',
                'description' => 'Pate Pedigree Puppy gói 80g được chế biến từ những thành phần thơm ngon và giàu chất dinh dưỡng, dành cho chó dưới 12 tháng tuổi.',
                'price' => 13000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-pedigree-puppy-goi-80g.jpg',
                'status' => true,
                'product_category_id' => 3,
            ],
            [
                'name' => 'Pate Pedigree Adult 130g nhiều hương vị đa dạng',
                'description' => 'Pate Pedigree Adult có nhiều hương vị đa dạng như thịt gà, thịt bò hay gan giúp kích thích vị giác thú cưng, là nguồn Protein cực kỳ chất lượng.',
                'price' => 23000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-pedigree-adult-130g.jpg',
                'status' => true,
                'product_category_id' => 3
            ],
            [
                'name' => 'Pate Pedigree Puppy 130g cho chó con vị gà nấu sốt',
                'description' => 'Pate Pedigree Puppy được chế biến dành cho hệ tiêu hóa non nớt của chó con, phù hợp với các bé cún từ 2 tới 12 tháng tuổi - độ tuổi phát triển mạnh mẽ.',
                'price' => 22000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/milk_for_small_and_large_dogs/sua-goat-gold-plus-cho-cho-meo-200g-thai-lan.jpgs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-pedigree-puppy-goi-130g.jpg',
                'status' => true,
                'product_category_id' => 3,
            ],
            [
                'name' => 'Pate Hug cho chó mèo vị thịt gà lon 400g',
                'description' => 'Pate Hug cho chó mèo là một trong những dòng thức ăn cao cấp được nhiều người tin dùng, có hương vị thơm ngon, đảm bảo an toàn với thú cưng.',
                'price' => 53000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-hug-cho-cho-vi-thit-ga-lon-400g.jpg',
                'status' => true,
                'product_category_id' => 3,
            ],
            [
                'name' => 'Pate Morando dành cho chó lon 400g hàng Ý',
                'description' => 'Pate Morando dành cho chó là dòng sản phẩm luôn được người nuôi tin dùng, hỗ trợ bổ sung các dưỡng chất thiết yếu cho sự phát triển của thú cưng.',
                'price' => 52000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-morando-cho-cho-lon-400g.jpg',
                'status' => true,
                'product_category_id' => 3,
            ],
            [
                'name' => 'Pate SmartHeart Adult cho chó lớn trên 1 tuổi gói 400g',
                'description' => 'Pate SmartHeart Adult cho chó lớn phù hợp với những chú cún từ 1 tuổi trở lên, được điều chỉnh dinh dưỡng, nhiều năng lượng cho thú cưng.',
                'price' => 29000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-smartheart-adult-cho-cho-tren-1-tuoi-400g.jpg',
                'status' => true,
                'product_category_id' => 3
            ],
            [
                'name' => 'Pate cho chó Hug gói 120g nhiều mùi vị hấp dẫn',
                'description' => 'Pate cho chó Hug hương vị thơm ngon và cung cấp nhiều chất dinh dưỡng giúp cho thú cưng có thể phát triển toàn diện, hoàn thiện vóc dáng.',
                'price' => 22000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-cho-hug-goi-120g-nhieu-mui-vi-hap-dan.jpg',
                'status' => true,
                'product_category_id' => 3,
            ],
            [
                'name' => 'Pate Mckelly cho chó làm từ thịt tươi lon 400g',
                'description' => 'Pate Mckelly cho chó là dòng sản phẩm pate thơm ngon, bổ dưỡng có thương hiệu từ Thái Lan, đảm bảo an toàn tuyệt đối với thú cưng.',
                'price' => 65000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-mckelly-cho-cho-lam-tu-thit-tuoi-lon-400g.jpg',
                'status' => true,
                'product_category_id' => 3,
            ],
            [
                'name' => 'Pate Reflex Plus cho chó vị thịt gà lon 400g',
                'description' => 'Pate Reflex Plus cho chó được thiết kế dạng ướt phù hợp với mọi giai đoạn phát triển của thú cưng, đem đến hương vị thơm ngon hấp dẫn các bé.',
                'price' => 58000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-reflex-plus-cho-cho-vi-thit-ga-lon-400g.jpg',
                'status' => true,
                'product_category_id' => 3,
            ],
            [
                'name' => 'Sốt thịt SmartHeart dành cho chó gói 130g vị gà',
                'description' => 'Sốt thị SmartHeart dành cho chó là dòng sản phẩm thức ăn ướt cao cấp với hương vị gà thơm ngon, giúp các bé cún dễ tiêu hóa, bổ sung dinh dưỡng.',
                'price' => 24000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/sot-thit-smartheart-danh-cho-cho-goi-130g-vi-ga.jpg',
                'status' => true,
                'product_category_id' => 3
            ],
            [
                'name' => 'Pate SmartHeart cho chó lon 400g vị thị bò và thịt gà',
                'description' => 'Pate SmartHeart cho chó đem lại nhiều dưỡng chất cần thiết cho cún cưng cùng hương vị thơm ngon hấp đẫn cuốn hút các boss nhỏ.',
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-smartheart-cho-cho-lon-400g-vi-thit-bo-va-thit-ga.jpg',
                'status' => true,
                'product_category_id' => 3,
            ],
            [
                'name' => 'Pate Hello Dog cho chó hương vị thịt gà lon 400g',
                'description' => 'Pate Hello Dog cho chó được làm hoàn toàn từ thịt gà tươi thom ngon, bổ dưỡng, là món khoái khẩu cho các bé cún và được nhiều người tin dùng.',
                'price' => 42000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-hello-dog-cho-cho-huong-vi-thit-ga-lon-400g.jpg',
                'status' => true,
                'product_category_id' => 3,
            ],
            [
                'name' => 'Pate Moochie cho chó gói 85g',
                'description' => 'Pate Moochie cho chó được sản xuất từ dây chuyền hiện đại của Thái Lan, làm từ thịt thật, đem đến những bữa ăn đầy đủ dinh dưỡng cho thú cưng.',
                'price' => 22000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/pate_sauces/pate-moochie-cho-cho-goi-85g.jpg',
                'status' => true,
                'product_category_id' => 3
            ],
        ];

        $dog_treats_and_chew_products = [
            [
                'name' => 'Xương gặm Pedigree DentaStix cho chó con gói 56g',
                'description' => 'Xương gặm Pedigree DentaStix được thiết kế cho chó con từ 2 tới 12 tháng tuổi, được thiết kế phù hợp với bộ răng và hệ tiêu hóa của chó con',
                'price' => 36000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/xuong-gam-pedigree-dentastix-cho-cho-con-goi-56g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Thanh gặm Pedigree DentaStix cho chó gói 98g',
                'description' => 'Thanh gặm Pedigree DentaStix giúp cho cún giải tỏa stress và là món ăn vặt tuyệt vời, giúp loại bỏ mảng bám trên răng thú cưng đồng thời bổ sung canxi.',
                'price' => 45000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/thanh-gam-pedigree-dentastix-cho-cho-goi-98g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Snack cho chó Pedigree Meat Jerky 80g',
                'description' => 'Snack cho chó Pedigree Meat Jerky là dòng sản phẩm thức ăn thêm cho cún, làm từ thịt khô 100%, có chất lượng được khẳng định theo năm tháng.',
                'price' => 39000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/snack-cho-cho-pedigree-meat-jerky-80g.jpg',
                'status' => true,
                'product_category_id' => 4
            ],
            [
                'name' => 'Snack cho chó Bowwow Cheese Roll thịt gà và cá hồi cuộn phô mai',
                'description' => 'Snack cho chó Bowwow Cheese Roll với hương vị phô mai béo ngậy là món ăn ưa thích của các bé cún, được làm từ thịt gà và cá hồi tươi.',
                'price' => 69000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/snack-cho-cho-bowwow-cheese-roll-thit-ga-va-ca-hoi-cuon-pho-mai.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Que thưởng Bowwow Stick Jerky 50g cho chó',
                'description' => 'Que thưởng Bowwow Stick Jerky được làm từ thịt thật 100%, đã qua quá trình chế biến loại bỏ vi khuẩn có hại, có nhiều mùi vị thơm ngon hấp dẫn.',
                'price' => 38000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/que-thuong-bowwow-stick-jerky-50g-cho-cho.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Kem thưởng SmartHeart Creamy dành cho chó gói 60g (4 gói nhỏ 15g)',
                'description' => 'Kem thưởng SmartHeart Creamy có kết cấu sánh mịn cùng hương vị ngon lành sẽ làm các boss nhỏ cực kỳ thích thú, giúp tăng sự ham muốn ăn uống của thú cưng.',
                'price' => 46000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/kem-thuong-smartheart-creamy-danh-cho-cho-goi-60g-moi-goi-15g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Gà khô cho chó mèo Taotaopet gói 100g',
                'description' => 'Gà khô cho chó mèo Taotaopet được làm hoàn toàn từ ức gà tươi. Đây là bữa ăn thơm ngon cho thú cưng, đồng thời bổ sung nhiều dưỡng chất thiết yếu cho các bé.',
                'price' => 45000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/ga-kho-cho-cho-meo-taotaopet-goi-100g.jpg',
                'status' => true,
                'product_category_id' => 4
            ],
            [
                'name' => 'Xương sữa dê Goatmilk\'s Formula dành cho chó gói 500g',
                'description' => 'Xương sữa dê Goatmilk\'s Formula không chỉ là món ăn thơm ngon dành cho thú cưng mà còn bổ sung rất nhiều dưỡng chất giúp cún phát triển toàn diện.',
                'price' => 143000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/xuong-sua-de-goatmilk-formula-danh-cho-cho-goi-500g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Xương gặm Veggiedent cho chó giúp sạch răng hàng Pháp',
                'description' => 'Xương gặm Veggiedent cho chó là hàng nhập khẩu từ Pháp với hương vị bạc hà thơm mát. Sản phẩm sẽ giúp đánh tan mùi hôi khó chịu trong miệng cún cưng.',
                'price' => 99000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/xuong-gam-veggiedent-cho-cho-giup-sach-rang-hang-phap.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Bánh quy cho chó WanWan Biscuits gói 100g',
                'description' => 'Bánh quy cho chó WanWan Biscuits đem lại hương vị thơm ngon cùng giá trị dinh dưỡng cao. Đây là món ăn thưởng cực kỳ cuốn hút các bé cún cưng.',
                'price' => 45000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/banh-quy-cho-cho-wanwan-biscuits-goi-100g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Thức ăn cho chó Trixie gói 100g nhiều mùi vị',
                'description' => 'Thức ăn cho chó Trixie với nguyên liệu làm từ phần ức gà thơm ngon và chất xơ từ rau củ quả, đem lại sự cân bằng dinh dưỡng trong mỗi bữa ăn của thú cưng.',
                'price' => 47000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/trixie_apple.jpg',
                'status' => true,
                'product_category_id' => 4
            ],
            [
                'name' => 'Miếng gà sấy DoggyMan gói 70g',
                'description' => 'Miếng gà sấy DoggyMan làm từ thịt gà tự nhiên được trải qua quy trình làm sạch, sấy khô đảm bảo vệ sinh, có hương vị thơm ngon, dinh dưỡng dồi dào.',
                'price' => 72000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/mieng-ga-say-doggyman-goi-70g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Que gặm DoggyMan da bò sáp ong gói 30 miếng',
                'description' => 'Que gặm cao cấp của thương hiệu DoggyMan từ Nhật, là món phần thưởng tuyệt vời cho các bé cún cưng, bổ sung thêm Canxi và nhiều dưỡng chất.',
                'price' => 82000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/mieng-ga-say-doggyman-goi-70g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Que gặm DoggyMan hương sữa gói 120g',
                'description' => 'Que gặm DoggyMan hương sữa vừa là món ăn thơm ngon, vừa là món đồ chơi hấp dẫn các bé cún. Các bé có thể nhai cả ngày không chán với hương vị sữa béo ngậy.',
                'price' => 56000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/que-gam-doggyman-huong-sua-goi-120g.png',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Xương nơ Doggy Man vị gà gói 5 miếng',
                'description' => 'Xương nơ Doggy Man vị gà thơm ngon là món quả thưởng tuyệt vời dành cho các bé cún, độ dai vừa phải giúp các bé nhai cả ngày không rời.',
                'price' => 30000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/xuong-no-doggyman-vi-ga-goi-5-mieng.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Xương sữa dê Doggy Man cho chó - Gói 14 miếng',
                'description' => 'Xương sữa dê Doggy Man gói 14 miếng giúp các bé cún xả stress, loại bỏ mảng bám cao răng, bổ sung nhiều dưỡng chất thiết yếu cho quá trình phát triển.',
                'price' => 68000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/xuong-sua-de-doggyman-cho-cho-goi-14-mieng.png',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Xương nơ thơm miệng cho cún hàng Nhật Bản 10 cây',
                'description' => 'Xương nơ thơm miệng cho cún Doggy Man hương vị gà sẽ giúp các bé cún đánh tan các mảng bám trên răng, loại bỏ hôi miệng, xả stress hiệu quả',
                'price' => 52000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/xuong-no-thom-mieng-cho-cun-hang-nhat-10-cay.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Bánh Karamo quấn thịt gà phần thưởng cho cún gói 100g',
                'description' => 'Bánh Karamo quấn thịt gà là thức ăn ưa thích của các bé cún, cực kỳ thích hợp để làm phần thưởng khi bé ngoan ngoãn, làm theo mệnh lệnh chủ nhân.',
                'price' => 35000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/banh-karamo.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Bánh thưởng Karamo hình khúc xương dành cho chó gói 100g',
                'description' => 'Bánh thưởng Karamo hình khúc xương là một trong những dòng đồ ăn vặt cho thú cưng bán chạy nhất tại PetHouse, sở hữu hương vị thơm ngon cuốn hút.',
                'price' => 23000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/banh-karamo.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Bánh thưởng Snack Tom hỗ trợ quá trình huấn luyện thú cưng 100g',
                'description' => 'Thực phẩm bánh thưởng Snack Tom cung cấp thêm nhiều chất dinh dưỡng, canxi cho chó, là món quà tuyệt vời dành cho các bé, hỗ trợ quá trình huấn luyện.',
                'price' => 34000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/banh-thuong-snacktom-ho-tro-qua-trinh-huan-luyen-thu-cung-100g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Xương canxi sữa dê Pet2go hình que 500gram',
                'description' => 'Xương canxi sữa dê Pet2go là món thưởng tuyệt vời dành cho cún cưng ở nhà bạn, có hương vị sữa dê béo ngậy, cùng với đó là nhiều dưỡng chất thiết yếu.',
                'price' => 152000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/xuong-canxi-sua-de-pet2go-hinh-que-500g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Bánh thưởng chó mèo Luscious hỗ trợ quá trình huấn luyện 220g',
                'description' => 'Bánh thưởng cho chó mèo Luscious là món quà tuyệt vời mỗi khi các bé cún hoàn thành tốt một hiệu lệnh. Sử dụng bánh thưởng hợp lý sẽ giúp quá trình huấn luyện chó mèo nhanh chóng hơn rất nhiều.',
                'price' => 58000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/banh-thuong-cho-meo-luscious-ho-tro-qua-trinh-huan-luyen-220g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Thanh gặm 7 Dental Effects vị thịt bò dành cho chó gói 160g',
                'description' => 'Thanh gặm 7 Dental Effects 160gr với hương vị thịt bò hấp dẫn các bé cún, giúp làm sạch mảng bám trên răng và giải quyết vấn đề cún cắn phá đồ đạc.',
                'price' => 64000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/thanh-gam-7-dental-effetcs-vi-thit-bo-goi-160g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Thanh gặm 7 Dental Effects vị cá hồi gói 160gram',
                'description' => 'Thanh gặm 7 Dental Effects 160gram vị cá hồi là món đồ ăn vặt ưa thích của các bé cún, đồng thời giúp phát triển cơ hàm và làm sạch mảng bám trên răng.',
                'price' => 64000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/thanh-gam-7-dental-effetcs-vi-ca-hoi-goi-160g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Xương gặm thịt gà Taotaopet 100gr',
                'description' => 'Xương gặm thịt gà Taotaopet giúp phát triển sức mạnh cơ hàm, loại bỏ mảng bám, hạn chế hành vi cắn phá đồ đạc của cún.',
                'price' => 39000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/xuong-gam-thit-ga-taotaopet-100gr.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
            [
                'name' => 'Que thưởng thịt gà phô mai Taotaopet 100gr',
                'description' => 'Que thưởng thịt gà phô mai Taotaopet với mùi vị cực kỳ hấp dẫn, dùng để thưởng cho cún trong quá trình huấn luyện hoặc cho ăn vặt.',
                'price' => 39000,
                'image' => 'gs://petshop-3d4ae.appspot.com/products/dog/food_and_nutritions/treats_and_chews/que-thuong-thit-ga-pho-mai-taotaopet-100g.jpg',
                'status' => true,
                'product_category_id' => 4,
            ],
        ];

        // Tạo fake data cho product từ dữ liệu thực
        foreach ($dog_food_products as $dog_food_product) {
            Product::create($dog_food_product);
        }
    }
}
