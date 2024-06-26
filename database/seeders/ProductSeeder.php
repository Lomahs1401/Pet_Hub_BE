<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker::create();

    $dog_food_products = [
      [
        'name' => 'Thức ăn cho chó Ganador Adult Salmon & Rice',
        'description' => 'Thức ăn cho chó Ganador Adult Salmon & Rice là thực phẩm dành cho chó trưởng thành với công thức chế biến được nghiên cứu bởi các chuyên gia',
        'price' => 29000,
        'image' => 'gs://new_petshop_bucket/products/1/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Pedigree Puppy vị gà và trứng 400g',
        'description' => 'Thức ăn dạng hạt Pedigree Puppy vị gà sốt trứng đem đến những bữa ăn đầy đủ chất dinh dưỡng, cùng hương vị ngon miệng kích thích các bé ăn uống.',
        'price' => 57000,
        'image' => 'gs://new_petshop_bucket/products/2/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn hạt Dog On Red - Protein 33%',
        'description' => 'Thức ăn hạt Dog On Red khô dành cho chó với hàm lượng đạm lên tới 33% giúp cho các boss nhỏ có thể nhanh chóng tăng cân, phát triển thể trạng khoẻ mạnh.',
        'price' => 392000,
        'image' => 'gs://new_petshop_bucket/products/3/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn hạt Dog On Green 5kg - Protein 24%',
        'description' => 'Với hàm lượng dinh dưỡng cao cùng mùi vị ngon miệng, thức ăn hạt Dog On Green thích hợp dành cho các bé cún biếng ăn đang còi cọc ốm yếu.',
        'price' => 370000,
        'image' => 'gs://new_petshop_bucket/products/4/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn hạt Hello Dog 400g dành cho chó',
        'description' => 'Thức ăn cho cún dạng hạt Hello Dog 400g là sản phẩm cung cấp đầy đủ và cân bằng chất dinh dưỡng đem lại nguồn năng lượng hàng ngày cho các boss.',
        'price' => 25000,
        'image' => 'gs://new_petshop_bucket/products/5/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt cho chó A Pro I.Q Fomula Dog Food - 20kg (500gx40gói)',
        'description' => 'Thức ăn hạt cho chó A Pro I.Q Fomula Dog Food là sản phẩm chất lượng dành cho cún cưng có xuất xứ từ Thái Lan. Sản phẩm có công thức chế biến an toàn',
        'price' => 805000,
        'image' => 'gs://new_petshop_bucket/products/6/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Classic Pets Small Breed Dog Beef Flavour - 2kg',
        'description' => 'Thức ăn hạt Classic Pets Small Breed Dog Beef Flavour dành cho cún con với hàm lượng dinh dưỡng cao, mùi vị thơm ngon dễ dàng để các boss hấp thụ.',
        'price' => 110000,
        'image' => 'gs://new_petshop_bucket/products/7/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Zoi Dog thức ăn cho chó 1kg',
        'description' => 'Thức ăn hạt cho chó trưởng thành hạt Zoi Dog Food có thương hiệu từ Thái Lan nổi tiếng về chất lượng, uy tín chắc chắn sẽ đem đến những bữa ăn ngon miệng',
        'price' => 35000,
        'image' => 'gs://new_petshop_bucket/products/8/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn Dog Mania cho chó mọi lứa tuổi 1kg',
        'description' => 'Thức ăn Dog Mania là thực phẩm thức ăn hạt có nển tảng dinh dưỡng dồi dào giúp thú cưng phát triển toàn diện về thể chất.',
        'price' => 64000,
        'image' => 'gs://new_petshop_bucket/products/9/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Thức ăn hạt Dog Classic cho chó mọi lứa tuổi 5kg',
        'description' => 'Thức ăn hạt Dog Classic cho chó mọi lứa tuổi được sản xuất bằng nguyên liệu cao cấp với thịt gà tươi và độ thơm ngon từ mỡ gà giúp thú cưng phát triển toàn diện.',
        'price' => 332000,
        'image' => 'gs://new_petshop_bucket/products/10/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn hạt hữu cơ ANF 6Free cho chó',
        'description' => 'Thức ăn hạt hữu cơ ANF 6Free cho chó được làm từ nguyên liệu hữu cơ tự nhiên an toàn và có lợi cho sức khoẻ, hạn chế tình trạng dị ứng thức ăn',
        'price' => 522000,
        'image' => 'gs://new_petshop_bucket/products/11/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt Classic Pets Puppy dành cho chó con vị sữa 400g',
        'description' => 'Hạt Classic Pets Puppy phù hợp với các bé cún trong giai đoạn từ 2-12 tháng tuổi. Hương vị sữa thơm ngon, béo ngậy sẽ kích thích các bé ăn không ngừng.',
        'price' => 32000,
        'image' => 'gs://new_petshop_bucket/products/12/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Smartheart Power Pack Puppy dành cho giống chó cỡ vừa và lớn',
        'description' => 'Hạt Smartheart Power Pack Puppy được phát triển riêng cho giống chó có sức mạnh thể chất, cần nhiều năng lượng như Alaska, Pit Bull hay Rottweiler.',
        'price' => 220000,
        'image' => 'gs://new_petshop_bucket/products/13/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt Smartheart Adult Lamb vị thịt cừu dành cho chó trưởng thành',
        'description' => 'Hạt Smartheart Adult Lamb dành cho các bạn cún từ 1 tuổi trở lên, đã phát triển toàn diện về hệ tiêu hóa. Lúc này, các bé cần rất nhiều năng lượng để hoạt động, vui chơi. Chính vì vậy, loại thức ăn cũng cần phù hợp với nhu cầu đó.',
        'price' => 29000,
        'image' => 'gs://new_petshop_bucket/products/14/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Smartheart Small Breeds Puppy cho chó nhỏ dưới 12 tháng tuổi',
        'description' => 'Hạt Smartheart Small Breeds Puppy dành cho chó cỡ nhỏ đang trong độ tuổi phát triển, được sản xuất trên dây chuyền hiện đại, uy tín, an toàn cho cún cưng.',
        'price' => 120000,
        'image' => 'gs://new_petshop_bucket/products/15/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt Smartheart Small Breeds dành cho các giống chó nhỏ',
        'description' => 'Hạt Smartheart Small Breeds phù hợp với các giống chó nhỏ như Chihuahua, Phốc sóc hay Poodle. Những loại chó cỡ nhỏ có tuổi thọ trung bình cao hơn các giống cỡ lớn, và chúng cũng cần chế độ dinh dưỡng đặc biệt hơn.',
        'price' => 30000,
        'image' => 'gs://new_petshop_bucket/products/16/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Smartheart Gold Puppy dành cho chó con dưới 12 tháng tuổi',
        'description' => 'Hạt Smartheart Gold Puppy phù hợp với các bé cún dưới 12 tháng, dễ tiêu hóa và giàu dưỡng chất để cún lớn nhanh, khỏe mạnh.',
        'price' => 132000,
        'image' => 'gs://new_petshop_bucket/products/17/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt Smartheart Gold Adult dành cho chó trưởng thành',
        'description' => 'Hạt Smartheart Gold Adult được thiết kế riêng cho dòng chó trưởng thành, đã phát triển cả về răng và hệ tiêu hóa, giúp loại bỏ cả mảng bám trên răng cún.',
        'price' => 119000,
        'image' => 'gs://new_petshop_bucket/products/18/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Smartheart Gold Lamb & Rice dành cho chó cỡ nhỏ',
        'description' => 'Hạt Smartheart Gold Lamb & Rice đến từ thương hiệu thức ăn cho chó mèo nổi tiếng, được thiết kế dành cho các giống chó cỡ mini với công thức đặc biệt.',
        'price' => 124000,
        'image' => 'gs://new_petshop_bucket/products/19/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt Pedigree Adult cho chó trưởng thành vị thịt bò, thịt gà, rau',
        'description' => 'Hạt Pedigree Adult phù hợp với mọi giống chó từ 1 tuổi trở lên, đã phát triển đầy đủ về mặt thể chất. Sản phẩm có nhiều năng lượng và được bổ sung vitamin.',
        'price' => 115000,
        'image' => 'gs://new_petshop_bucket/products/20/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn chó Bowwow Cheese Sand Mix 120g',
        'description' => 'Thức ăn chó Bowwow Cheese Sand Mix là dòng thức ăn khô hỗn hợp giàu dinh dưỡng, ít chất béo, ít muối đảm bảo cho sự phát triển khỏe mạnh của thú cưng.',
        'price' => 65000,
        'image' => 'gs://new_petshop_bucket/products/21/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Thức ăn hữu cơ Origi-7 cho chó đa dạng mùi vị',
        'description' => 'Thức ăn hữu cơ Origi-7 cho chó là dòng sản phẩm cao cấp từ Hàn Quốc, phù hợp với mọi giống chó ở mọi độ tuổi với hương vị thịt bò, cá hồi, gà, cừu.',
        'price' => 260000,
        'image' => 'gs://new_petshop_bucket/products/22/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Zenith Adult cho chó trưởng thành 3kg',
        'description' => 'Hạt Zenith Adult cho chó trưởng thành từ 1 tuổi trở lên, có hệ tiêu hóa phát triển, được chế biến từ thịt cừu tươi, cá hồi đem đến nguồn Protein cao cấp.',
        'price' => 494000,
        'image' => 'gs://new_petshop_bucket/products/23/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt mềm Zenith Senior cho chó lớn tuổi',
        'description' => 'Hạt mềm Zenith Senior dành riêng cho chó lớn tuổi với nhu cầu dinh dưỡng đặc biệt, giải quyết nhiều tình trạng bệnh lý, điều chỉnh về dinh dưỡng.',
        'price' => 229000,
        'image' => 'gs://new_petshop_bucket/products/24/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Natural Core Bene M50 vị thịt gà & cá hồi',
        'description' => 'Hạt Natural Core Bene M50 được chế biến từ các nguyên liệu hữu cơ tự nhiên tốt cho sức khỏe thú cưng, đem tới chế độ dinh dưỡng cân bằng cho cún cưng.',
        'price' => 68000,
        'image' => 'gs://new_petshop_bucket/products/25/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Thức ăn dành cho chó trưởng thành Pro-Dog Adult túi 400g',
        'description' => 'Thức ăn dành cho chó trưởng thành Pro-Dog Adult đã phát triển toàn diện về thể chất. Vì vậy, sản phẩm có nguồn năng lượng dồi dào, cho các bé hoạt động cả ngày dài.',
        'price' => 32000,
        'image' => 'gs://new_petshop_bucket/products/26/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn dành cho chó con Pro-Dog Puppy túi 400g',
        'description' => 'Hạt Pro-Dog Puppy cho chó con từ 2 tới 12 tháng tuổi, đem đến nguồn dinh dưỡng hoàn chỉnh với Protein từ động vật và tinh bột từ các loại ngũ cốc tự nhiên.',
        'price' => 33000,
        'image' => 'gs://new_petshop_bucket/products/27/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Thức ăn hạt cao cấp dành cho chó Pro Pet Grand Magic túi 1kg',
        'description' => 'Hạt Pro-Pet Grandmagic được làm từ những nguyên liệu tự nhiên như thịt bò, gà và hải sản, đem đến cho cún cưng nguồn Protein chất lượng.',
        'price' => 87000,
        'image' => 'gs://new_petshop_bucket/products/28/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn cho chó nhỏ hạt Purina Pro Plan nhập khẩu',
        'description' => 'Thức ăn cho chó nhỏ hạt Purina Pro Plan nhập khẩu phù hợp với các giống chó cỡ nhỏ, được chia thành nhiều dòng sản phẩm phù hợp với từng nhu cầu của cún cưng.',
        'price' => 245000,
        'image' => 'gs://new_petshop_bucket/products/29/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt Purina ProPlan Puppy Small & Mini dành cho cún con cỡ nhỏ',
        'description' => 'Hạt Purina ProPlan Puppy Small & Mini là dòng sản phẩm thức ăn cao cấp dành cho các bé cún từ 2 tới 12 tháng tuổi thuộc dòng chó kích thước nhỏ.',
        'price' => 289000,
        'image' => 'gs://new_petshop_bucket/products/30/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn hạt Purina ProPlan Puppy dành cho chó con cỡ trung bình',
        'description' => 'Thức ăn hạt Purina ProPlan Puppy được tạo ra dành riêng cho các bé cún đang trong độ tuổi phát triển phù hợp với các dòng chó vóc dáng trung bình.',
        'price' => 585000,
        'image' => 'gs://new_petshop_bucket/products/31/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt Purina ProPlan Adult Chicken Formula cho chó trưởng thành 2.5kg',
        'description' => 'Hạt Purina ProPlan Adult Chicken Formula có thành phần dinh dưỡng 44hoàn chỉnh với nhiều Protein giúp cún khỏe mạnh, phù hợp với các bé cún trên 1 tuổi.',
        'price' => 442000,
        'image' => 'gs://new_petshop_bucket/products/32/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn hạt Reflex Plus cho chó vị thịt gà và cá hồi',
        'description' => 'Thức ăn hạt Reflex Plus cho chó có hai vị thịt gà và cá hồi hấp dẫn, có hàm lượng Protein cao, đáp ứng đầy đủ cho nhu cầu phát triển của cún cưng.',
        'price' => 189000,
        'image' => 'gs://new_petshop_bucket/products/33/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt Royal Canin Poodle Adult cho chó Poodle trên 1 tuổi',
        'description' => 'Hạt Royal Canin Poodle Adult có xuất xứ từ Pháp dành riêng cho cho giống chó Poodle, cung cấp nguồn dinh dưỡng tốt để chó có một bộ lông khỏe đẹp.',
        'price' => 154000,
        'image' => 'gs://new_petshop_bucket/products/34/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Royal Canin Poodle Puppy dành cho chó Poodle dưới 1 tuổi - 500g',
        'description' => 'Hạt Royal Canin Poodle Puppy sở hữu công thức dành riêng cho giống chó Poodle được nhiều người yêu thích. Poodle có bộ lông hết sức đặc biệt, dài ra liên tục chứ không rụng thường xuyên như các giống chó khác. Cũng vì thế, giống Poodle sẽ cần chế độ dinh dưỡng đặc biệt để nuổi dưỡng bộ lông khỏe đẹp.',
        'price' => 202000,
        'image' => 'gs://new_petshop_bucket/products/35/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt Royal Canin Mini Adult dành cho các dòng chó cỡ nhỏ',
        'description' => 'Hạt Royal Canin Mini Adult được phát triển riêng cho các dòng chó kích thước nhỏ, nặng dưới 10kg. Thức ăn phù hợp với các bé từ 10-12 tháng tuổi trở lên, đã có hệ tiêu hóa phát triển đầy đủ. Sản phẩm giúp duy trì mức năng lượng cho cún hoạt động hàng ngày, đồng thời được điều chỉnh dinh dưỡng giúp kéo dài tuổi thọ. Các giống chó nhỏ vốn có tuổi thọ cao hơn chó lớn, vậy nên dinh dưỡng cũng cần được chú ý.',
        'price' => 239000,
        'image' => 'gs://new_petshop_bucket/products/36/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Ganador Premium Adult vị thịt cừu dành cho chó trưởng thành 400g',
        'description' => 'Hạt Ganador Premium Adult là dòng sản phẩm cao cấp cho chó trưởng thành, đảm bảo nhu cầu năng lượng rất lớn của thú cưng và bổ sung thêm dưỡng chất.',
        'price' => 27000,
        'image' => 'gs://new_petshop_bucket/products/37/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt Ganador Puppy cho chó con vị trứng sữa 3kg',
        'description' => 'Hạt Ganador Puppy cho chó con từ 2 cho tới 12 tháng tuổi. Đây là độ tuổi cần sự chăm sóc đặc biệt của chủ nhân, có yêu cầu dinh dưỡng riêng biệt.',
        'price' => 163000,
        'image' => 'gs://new_petshop_bucket/products/38/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn hạt Ganador Adult cho chó trưởng thành vị gà nướng',
        'description' => 'Thức ăn hạt Ganador Adult dành cho các bé cún từ 1 tuổi trở lên, cung cấp nhiều năng lượng cho hoạt động mỗi ngày, bổ sung thêm vitamin và khoáng chất.',
        'price' => 29000,
        'image' => 'gs://new_petshop_bucket/products/39/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Thức ăn cho chó Fib\'s dạng viên, dành cho chó trưởng thành',
        'description' => 'Thức ăn cho chó Fib\'s phù hợp với mọi giống chó ở mọi độ tuổi, giúp cung cấp đầy đủ năng lượng, Vitamin và khoáng chất giúp cún phát triển toàn diện.',
        'price' => 20000,
        'image' => 'gs://new_petshop_bucket/products/40/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Smart Heart cho chó trưởng thành vị thịt bò 1.5kg',
        'description' => 'Hạt Smart Heart cho chó trưởng thành có hương vị thịt bò nước thơm ngon làm bé cún nào cũng phải thích thú, có lượng Protein rất cao từ các nguồn tự nhiên.',
        'price' => 95000,
        'image' => 'gs://new_petshop_bucket/products/41/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt Pedigree Puppy cho chó con cung cấp đầy đủ dinh dưỡng 400g',
        'description' => 'Hạt Pedigree Puppy cho chó con từ 2 tới 12 tháng tuổi với công thức hoàn hảo để các bé phát triển khỏe mạnh, lớn nhanh, lanh lợi.',
        'price' => 57000,
        'image' => 'gs://new_petshop_bucket/products/42/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt Ganador Premium Puppy 500g dành cho chó con từ 2-12 tháng',
        'description' => 'Hạt Ganador Premium Puppy phù hợp với các bé cún trong độ tuổi từ 2-12 tháng - độ tuổi phát triển mạnh mẽ của chó, cần nhiều Protein và chất dinh dưỡng.',
        'price' => 35000,
        'image' => 'gs://new_petshop_bucket/products/43/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Hạt tươi Cuncun Plus cho chó hàm lượng Protein cao 1.2kg',
        'description' => 'Hạt tươi Cuncun Plus cho chó với hàm lượng Protein từ thịt tươi vượt trội, bổ sung nhiều dưỡng chất, phù hợp với các giống cún nhỏ, có lông dày như Poodle',
        'price' => 150000,
        'image' => 'gs://new_petshop_bucket/products/44/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Hạt mềm Zenith cho chó con dưới 12 tháng tuổi gói 1.2kg',
        'description' => 'Thức ăn dạng hạt mềm Zenith giúp chó con dễ nhai và dễ hấp thụ. Sản phẩm được bổ sung Vitamin và khoáng chất giúp hỗ trợ quá trình phát triển của chó.',
        'price' => 230000,
        'image' => 'gs://new_petshop_bucket/products/45/',
        'status' => true,
        'product_category_id' => 1,
      ],
      [
        'name' => 'Thức ăn hạt Smart Heart vị thịt bò cho chó con gói 400 gram',
        'description' => 'Hạt thức ăn cho chó con Smart Heart dành cho chó trong độ tuổi từ 2-10 tháng tuổi. Sản phẩm cung cấp đầy đủ dưỡng chất giúp chó có điều kiện tốt nhất trong giai đoạn phát triển.',
        'price' => 32000,
        'image' => 'gs://new_petshop_bucket/products/46/',
        'status' => true,
        'product_category_id' => 1
      ],
      [
        'name' => 'Thức ăn cho chó Royal Canin Puppy Mini 800gr',
        'description' => 'Thức ăn dạng hạt dành cho các dòng chó cỡ nhỏ như Phốc sóc (Pomeranian, Pug, Poodle, Corgi…), trong giai đoạn từ 2-10 tháng tuổi.',
        'price' => 251000,
        'image' => 'gs://new_petshop_bucket/products/47/',
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
        'image' => 'gs://new_petshop_bucket/products/48/',
        'status' => true,
        'product_category_id' => 2,
      ],
      [
        'name' => 'Sữa Petsure Premium cho chó mèo hộp 110g',
        'description' => 'Sữa bột Petsure Premium cho chó mèo được sản xuất bởi thương hiệu Dr. Kyan danh tiếng, là dòng sữa bổ sung dinh dưỡng tuyệt vời cho các bé thú cưng.',
        'price' => 51000,
        'image' => 'gs://new_petshop_bucket/products/49/',
        'status' => true,
        'product_category_id' => 2,
      ],
      [
        'name' => 'Sữa bột Colostrum chó mèo hộp 100g',
        'description' => 'Sữa bột Colostrum chó mèo được nhiều người sử dụng thay sữa mẹ trong các trường hợp chó mèo mồ côi hay mẹ thiếu sữa, có đầy đủ chất dinh dưỡng cho thú cưng.',
        'price' => 39000,
        'image' => 'gs://new_petshop_bucket/products/50/',
        'status' => true,
        'product_category_id' => 2
      ],
      [
        'name' => 'Sữa Goat Gold Plus cho chó mèo hộp 200g hàng Thái Lan',
        'description' => 'Sữa Goat Gold Plus cho chó mèo được nhiều người nuôi tin dùng, cung cấp đầy đủ các nhu cầu dinh dưỡng thiết yếu của thú cưng.',
        'price' => 135000,
        'image' => 'gs://new_petshop_bucket/products/51/',
        'status' => true,
        'product_category_id' => 2,
      ],
      [
        'name' => 'Sữa bột AG-Science cho chó mèo hộp 250g',
        'description' => 'Sữa bột AG-Science cho chó mèo được làm từ bột sữa dê nguyên chất, đem lại nhiều nguồn dưỡng chất cần thiết, giúp cơ thể thú cưng phát triển khoẻ mạnh.',
        'price' => 129000,
        'image' => 'gs://new_petshop_bucket/products/52/',
        'status' => true,
        'product_category_id' => 2,
      ],
      [
        'name' => 'Sữa bột Goat Gold cho chó mèo hộp 200g hàng nhập Thái Lan',
        'description' => 'Sữa bột Goat Gold cho chó mèo được làm từ sữa dê giàu dinh dưỡng, hỗ trợ hiệu quả cho sự phát triển của thú cưng, là món đồ uống khoái khẩu của các bé.',
        'price' => 51000,
        'image' => 'gs://new_petshop_bucket/products/53/',
        'status' => true,
        'product_category_id' => 2,
      ],
      [
        'name' => 'Sữa tươi Pet Own cho chó mèo hộp 1 lít nhập khẩu Úc',
        'description' => 'Sữa tươi Pet Own cho chó mèo phù hợp với cả chó mèo con, cung cấp đầy đủ dinh dưỡng cho các bé phát triển, ngoài ra còn được bổ sung Glucosamine cho khớp.',
        'price' => 118000,
        'image' => 'gs://new_petshop_bucket/products/54/',
        'status' => true,
        'product_category_id' => 2
      ],
      [
        'name' => 'Sữa Wow Milk dành cho chó gói 100g',
        'description' => 'Sữa Wow Milk dành cho chó sở hữu công thức dinh dưỡng cân giúp bổ sung dinh dưỡng cho chó con và còn có thể dùng thay sữa mẹ.',
        'price' => 36000,
        'image' => 'gs://new_petshop_bucket/products/55/',
        'status' => true,
        'product_category_id' => 2,
      ],
      [
        'name' => 'Sữa Dr Kyan cho chó con và chó trưởng thành hộp 110g',
        'description' => 'Sữa Dr Kyan cho chó giúp bổ sung thêm nhiều năng lượng, Vitamin và khoáng chất cho cún yêu nhà bạn. Ngoài những bữa ăn hàng ngày, bạn có thể sử dụng sữa DR Kyan như bữa phụ để giúp cún nhà bạn cảm thấy ngon miệng hơn, nạp thêm được nhiều chất dinh dưỡng hơn. Song song sử dụng sữa kèm các bữa ăn chính sẽ góp phần cho cún cơ thể phát triển khoẻ mạnh, toàn diện.',
        'price' => 50000,
        'image' => 'gs://new_petshop_bucket/products/56/',
        'status' => true,
        'product_category_id' => 2,
      ],
      [
        'name' => 'Sữa chó Esbilac bổ sung dưỡng chất cho chó sơ sinh 340g',
        'description' => 'Sữa chó Esbilac là dòng sữa dành cho chó sơ sinh, chó non để sử dụng thay cho nguồn sữa mẹ. Đây là nguồn sữa cực kỳ thiết yếu với các trường hợp chó mất mẹ, bị mẹ bỏ hoặc đang bị suy dinh dưỡng, thiếu chất.',
        'price' => 535000,
        'image' => 'gs://new_petshop_bucket/products/57/',
        'status' => true,
        'product_category_id' => 2
      ],
      [
        'name' => 'Sữa Biomilk cho chó mèo bổ sung dưỡng chất gói 100g',
        'description' => 'Sữa Biomilk cho chó mèo giúp cung cấp thêm nhiều dưỡng chất cần thiết, giúp thú cưng luôn mạnh khoẻ, phát triển toàn diện, ngăn ngừa được bệnh tật.',
        'price' => 39000,
        'image' => 'gs://new_petshop_bucket/products/58/',
        'status' => true,
        'product_category_id' => 2,
      ],
    ];

    $dog_pate_sauce_products = [
      [
        'name' => 'Pate Pedigree Adult gói 80g thành phần gà, thịt gò, gan nướng',
        'description' => 'Pate Pedigree Adult gói 80g được chế biến từ những thành phần tự nhiên dành cho cún đã phát triển toàn diện, là nguồn Protein cao cấp với thịt gà, thịt bò.',
        'price' => 15000,
        'image' => 'gs://new_petshop_bucket/products/59/',
        'status' => true,
        'product_category_id' => 3,
      ],
      [
        'name' => 'Pate Pedigree Puppy gói 80g thành phần thịt gà, gan, trứng, rau',
        'description' => 'Pate Pedigree Puppy gói 80g được chế biến từ những thành phần thơm ngon và giàu chất dinh dưỡng, dành cho chó dưới 12 tháng tuổi.',
        'price' => 13000,
        'image' => 'gs://new_petshop_bucket/products/60/',
        'status' => true,
        'product_category_id' => 3,
      ],
      [
        'name' => 'Pate Pedigree Adult 130g nhiều hương vị đa dạng',
        'description' => 'Pate Pedigree Adult có nhiều hương vị đa dạng như thịt gà, thịt bò hay gan giúp kích thích vị giác thú cưng, là nguồn Protein cực kỳ chất lượng.',
        'price' => 23000,
        'image' => 'gs://new_petshop_bucket/products/61/',
        'status' => true,
        'product_category_id' => 3
      ],
      [
        'name' => 'Pate Pedigree Puppy 130g cho chó con vị gà nấu sốt',
        'description' => 'Pate Pedigree Puppy được chế biến dành cho hệ tiêu hóa non nớt của chó con, phù hợp với các bé cún từ 2 tới 12 tháng tuổi - độ tuổi phát triển mạnh mẽ.',
        'price' => 22000,
        'image' => 'gs://new_petshop_bucket/products/62/',
        'status' => true,
        'product_category_id' => 3,
      ],
      [
        'name' => 'Pate Hug cho chó mèo vị thịt gà lon 400g',
        'description' => 'Pate Hug cho chó mèo là một trong những dòng thức ăn cao cấp được nhiều người tin dùng, có hương vị thơm ngon, đảm bảo an toàn với thú cưng.',
        'price' => 53000,
        'image' => 'gs://new_petshop_bucket/products/63/',
        'status' => true,
        'product_category_id' => 3,
      ],
      [
        'name' => 'Pate Morando dành cho chó lon 400g hàng Ý',
        'description' => 'Pate Morando dành cho chó là dòng sản phẩm luôn được người nuôi tin dùng, hỗ trợ bổ sung các dưỡng chất thiết yếu cho sự phát triển của thú cưng.',
        'price' => 52000,
        'image' => 'gs://new_petshop_bucket/products/64/',
        'status' => true,
        'product_category_id' => 3,
      ],
      [
        'name' => 'Pate SmartHeart Adult cho chó lớn trên 1 tuổi gói 400g',
        'description' => 'Pate SmartHeart Adult cho chó lớn phù hợp với những chú cún từ 1 tuổi trở lên, được điều chỉnh dinh dưỡng, nhiều năng lượng cho thú cưng.',
        'price' => 29000,
        'image' => 'gs://new_petshop_bucket/products/65/',
        'status' => true,
        'product_category_id' => 3
      ],
      [
        'name' => 'Pate cho chó Hug gói 120g nhiều mùi vị hấp dẫn',
        'description' => 'Pate cho chó Hug hương vị thơm ngon và cung cấp nhiều chất dinh dưỡng giúp cho thú cưng có thể phát triển toàn diện, hoàn thiện vóc dáng.',
        'price' => 22000,
        'image' => 'gs://new_petshop_bucket/products/66/',
        'status' => true,
        'product_category_id' => 3,
      ],
      [
        'name' => 'Pate Mckelly cho chó làm từ thịt tươi lon 400g',
        'description' => 'Pate Mckelly cho chó là dòng sản phẩm pate thơm ngon, bổ dưỡng có thương hiệu từ Thái Lan, đảm bảo an toàn tuyệt đối với thú cưng.',
        'price' => 65000,
        'image' => 'gs://new_petshop_bucket/products/67/',
        'status' => true,
        'product_category_id' => 3,
      ],
      [
        'name' => 'Pate Reflex Plus cho chó vị thịt gà lon 400g',
        'description' => 'Pate Reflex Plus cho chó được thiết kế dạng ướt phù hợp với mọi giai đoạn phát triển của thú cưng, đem đến hương vị thơm ngon hấp dẫn các bé.',
        'price' => 58000,
        'image' => 'gs://new_petshop_bucket/products/68/',
        'status' => true,
        'product_category_id' => 3,
      ],
      [
        'name' => 'Sốt thịt SmartHeart dành cho chó gói 130g vị gà',
        'description' => 'Sốt thị SmartHeart dành cho chó là dòng sản phẩm thức ăn ướt cao cấp với hương vị gà thơm ngon, giúp các bé cún dễ tiêu hóa, bổ sung dinh dưỡng.',
        'price' => 24000,
        'image' => 'gs://new_petshop_bucket/products/69/',
        'status' => true,
        'product_category_id' => 3
      ],
      [
        'name' => 'Pate SmartHeart cho chó lon 400g vị thị bò và thịt gà',
        'description' => 'Pate SmartHeart cho chó đem lại nhiều dưỡng chất cần thiết cho cún cưng cùng hương vị thơm ngon hấp đẫn cuốn hút các boss nhỏ.',
        'price' => 38000,
        'image' => 'gs://new_petshop_bucket/products/70/',
        'status' => true,
        'product_category_id' => 3,
      ],
      [
        'name' => 'Pate Hello Dog cho chó hương vị thịt gà lon 400g',
        'description' => 'Pate Hello Dog cho chó được làm hoàn toàn từ thịt gà tươi thom ngon, bổ dưỡng, là món khoái khẩu cho các bé cún và được nhiều người tin dùng.',
        'price' => 42000,
        'image' => 'gs://new_petshop_bucket/products/71/',
        'status' => true,
        'product_category_id' => 3,
      ],
      [
        'name' => 'Pate Moochie cho chó gói 85g',
        'description' => 'Pate Moochie cho chó được sản xuất từ dây chuyền hiện đại của Thái Lan, làm từ thịt thật, đem đến những bữa ăn đầy đủ dinh dưỡng cho thú cưng.',
        'price' => 22000,
        'image' => 'gs://new_petshop_bucket/products/72/',
        'status' => true,
        'product_category_id' => 3
      ],
    ];

    $dog_treats_and_chew_products = [
      [
        'name' => 'Xương gặm Pedigree DentaStix cho chó con gói 56g',
        'description' => 'Xương gặm Pedigree DentaStix được thiết kế cho chó con từ 2 tới 12 tháng tuổi, được thiết kế phù hợp với bộ răng và hệ tiêu hóa của chó con',
        'price' => 36000,
        'image' => 'gs://new_petshop_bucket/products/73/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Thanh gặm Pedigree DentaStix cho chó gói 98g',
        'description' => 'Thanh gặm Pedigree DentaStix giúp cho cún giải tỏa stress và là món ăn vặt tuyệt vời, giúp loại bỏ mảng bám trên răng thú cưng đồng thời bổ sung canxi.',
        'price' => 45000,
        'image' => 'gs://new_petshop_bucket/products/74/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Snack cho chó Pedigree Meat Jerky 80g',
        'description' => 'Snack cho chó Pedigree Meat Jerky là dòng sản phẩm thức ăn thêm cho cún, làm từ thịt khô 100%, có chất lượng được khẳng định theo năm tháng.',
        'price' => 39000,
        'image' => 'gs://new_petshop_bucket/products/75/',
        'status' => true,
        'product_category_id' => 4
      ],
      [
        'name' => 'Snack cho chó Bowwow Cheese Roll thịt gà và cá hồi cuộn phô mai',
        'description' => 'Snack cho chó Bowwow Cheese Roll với hương vị phô mai béo ngậy là món ăn ưa thích của các bé cún, được làm từ thịt gà và cá hồi tươi.',
        'price' => 69000,
        'image' => 'gs://new_petshop_bucket/products/76/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Que thưởng Bowwow Stick Jerky 50g cho chó',
        'description' => 'Que thưởng Bowwow Stick Jerky được làm từ thịt thật 100%, đã qua quá trình chế biến loại bỏ vi khuẩn có hại, có nhiều mùi vị thơm ngon hấp dẫn.',
        'price' => 38000,
        'image' => 'gs://new_petshop_bucket/products/77/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Kem thưởng SmartHeart Creamy dành cho chó gói 60g (4 gói nhỏ 15g)',
        'description' => 'Kem thưởng SmartHeart Creamy có kết cấu sánh mịn cùng hương vị ngon lành sẽ làm các boss nhỏ cực kỳ thích thú, giúp tăng sự ham muốn ăn uống của thú cưng.',
        'price' => 46000,
        'image' => 'gs://new_petshop_bucket/products/78/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Gà khô cho chó mèo Taotaopet gói 100g',
        'description' => 'Gà khô cho chó mèo Taotaopet được làm hoàn toàn từ ức gà tươi. Đây là bữa ăn thơm ngon cho thú cưng, đồng thời bổ sung nhiều dưỡng chất thiết yếu cho các bé.',
        'price' => 45000,
        'image' => 'gs://new_petshop_bucket/products/79/',
        'status' => true,
        'product_category_id' => 4
      ],
      [
        'name' => 'Xương sữa dê Goatmilk\'s Formula dành cho chó gói 500g',
        'description' => 'Xương sữa dê Goatmilk\'s Formula không chỉ là món ăn thơm ngon dành cho thú cưng mà còn bổ sung rất nhiều dưỡng chất giúp cún phát triển toàn diện.',
        'price' => 143000,
        'image' => 'gs://new_petshop_bucket/products/80/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Xương gặm Veggiedent cho chó giúp sạch răng hàng Pháp',
        'description' => 'Xương gặm Veggiedent cho chó là hàng nhập khẩu từ Pháp với hương vị bạc hà thơm mát. Sản phẩm sẽ giúp đánh tan mùi hôi khó chịu trong miệng cún cưng.',
        'price' => 99000,
        'image' => 'gs://new_petshop_bucket/products/81/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Bánh quy cho chó WanWan Biscuits gói 100g',
        'description' => 'Bánh quy cho chó WanWan Biscuits đem lại hương vị thơm ngon cùng giá trị dinh dưỡng cao. Đây là món ăn thưởng cực kỳ cuốn hút các bé cún cưng.',
        'price' => 45000,
        'image' => 'gs://new_petshop_bucket/products/82/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Thức ăn cho chó Trixie gói 100g nhiều mùi vị',
        'description' => 'Thức ăn cho chó Trixie với nguyên liệu làm từ phần ức gà thơm ngon và chất xơ từ rau củ quả, đem lại sự cân bằng dinh dưỡng trong mỗi bữa ăn của thú cưng.',
        'price' => 47000,
        'image' => 'gs://new_petshop_bucket/products/83/',
        'status' => true,
        'product_category_id' => 4
      ],
      [
        'name' => 'Miếng gà sấy DoggyMan gói 70g',
        'description' => 'Miếng gà sấy DoggyMan làm từ thịt gà tự nhiên được trải qua quy trình làm sạch, sấy khô đảm bảo vệ sinh, có hương vị thơm ngon, dinh dưỡng dồi dào.',
        'price' => 72000,
        'image' => 'gs://new_petshop_bucket/products/84/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Que gặm DoggyMan da bò sáp ong gói 30 miếng',
        'description' => 'Que gặm cao cấp của thương hiệu DoggyMan từ Nhật, là món phần thưởng tuyệt vời cho các bé cún cưng, bổ sung thêm Canxi và nhiều dưỡng chất.',
        'price' => 82000,
        'image' => 'gs://new_petshop_bucket/products/85/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Que gặm DoggyMan hương sữa gói 120g',
        'description' => 'Que gặm DoggyMan hương sữa vừa là món ăn thơm ngon, vừa là món đồ chơi hấp dẫn các bé cún. Các bé có thể nhai cả ngày không chán với hương vị sữa béo ngậy.',
        'price' => 56000,
        'image' => 'gs://new_petshop_bucket/products/86/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Xương nơ Doggy Man vị gà gói 5 miếng',
        'description' => 'Xương nơ Doggy Man vị gà thơm ngon là món quả thưởng tuyệt vời dành cho các bé cún, độ dai vừa phải giúp các bé nhai cả ngày không rời.',
        'price' => 30000,
        'image' => 'gs://new_petshop_bucket/products/87/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Xương sữa dê Doggy Man cho chó - Gói 14 miếng',
        'description' => 'Xương sữa dê Doggy Man gói 14 miếng giúp các bé cún xả stress, loại bỏ mảng bám cao răng, bổ sung nhiều dưỡng chất thiết yếu cho quá trình phát triển.',
        'price' => 68000,
        'image' => 'gs://new_petshop_bucket/products/88/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Xương nơ thơm miệng cho cún hàng Nhật Bản 10 cây',
        'description' => 'Xương nơ thơm miệng cho cún Doggy Man hương vị gà sẽ giúp các bé cún đánh tan các mảng bám trên răng, loại bỏ hôi miệng, xả stress hiệu quả',
        'price' => 52000,
        'image' => 'gs://new_petshop_bucket/products/89/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Bánh Karamo quấn thịt gà phần thưởng cho cún gói 100g',
        'description' => 'Bánh Karamo quấn thịt gà là thức ăn ưa thích của các bé cún, cực kỳ thích hợp để làm phần thưởng khi bé ngoan ngoãn, làm theo mệnh lệnh chủ nhân.',
        'price' => 35000,
        'image' => 'gs://new_petshop_bucket/products/90/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Bánh thưởng Karamo hình khúc xương dành cho chó gói 100g',
        'description' => 'Bánh thưởng Karamo hình khúc xương là một trong những dòng đồ ăn vặt cho thú cưng bán chạy nhất tại PetHouse, sở hữu hương vị thơm ngon cuốn hút.',
        'price' => 23000,
        'image' => 'gs://new_petshop_bucket/products/91/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Bánh thưởng Snack Tom hỗ trợ quá trình huấn luyện thú cưng 100g',
        'description' => 'Thực phẩm bánh thưởng Snack Tom cung cấp thêm nhiều chất dinh dưỡng, canxi cho chó, là món quà tuyệt vời dành cho các bé, hỗ trợ quá trình huấn luyện.',
        'price' => 34000,
        'image' => 'gs://new_petshop_bucket/products/92/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Xương canxi sữa dê Pet2go hình que 500gram',
        'description' => 'Xương canxi sữa dê Pet2go là món thưởng tuyệt vời dành cho cún cưng ở nhà bạn, có hương vị sữa dê béo ngậy, cùng với đó là nhiều dưỡng chất thiết yếu.',
        'price' => 152000,
        'image' => 'gs://new_petshop_bucket/products/93/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Bánh thưởng chó mèo Luscious hỗ trợ quá trình huấn luyện 220g',
        'description' => 'Bánh thưởng cho chó mèo Luscious là món quà tuyệt vời mỗi khi các bé cún hoàn thành tốt một hiệu lệnh. Sử dụng bánh thưởng hợp lý sẽ giúp quá trình huấn luyện chó mèo nhanh chóng hơn rất nhiều.',
        'price' => 58000,
        'image' => 'gs://new_petshop_bucket/products/94/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Thanh gặm 7 Dental Effects vị thịt bò dành cho chó gói 160g',
        'description' => 'Thanh gặm 7 Dental Effects 160gr với hương vị thịt bò hấp dẫn các bé cún, giúp làm sạch mảng bám trên răng và giải quyết vấn đề cún cắn phá đồ đạc.',
        'price' => 64000,
        'image' => 'gs://new_petshop_bucket/products/95/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Thanh gặm 7 Dental Effects vị cá hồi gói 160gram',
        'description' => 'Thanh gặm 7 Dental Effects 160gram vị cá hồi là món đồ ăn vặt ưa thích của các bé cún, đồng thời giúp phát triển cơ hàm và làm sạch mảng bám trên răng.',
        'price' => 64000,
        'image' => 'gs://new_petshop_bucket/products/96/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Xương gặm thịt gà Taotaopet 100gr',
        'description' => 'Xương gặm thịt gà Taotaopet giúp phát triển sức mạnh cơ hàm, loại bỏ mảng bám, hạn chế hành vi cắn phá đồ đạc của cún.',
        'price' => 39000,
        'image' => 'gs://new_petshop_bucket/products/97/',
        'status' => true,
        'product_category_id' => 4,
      ],
      [
        'name' => 'Que thưởng thịt gà phô mai Taotaopet 100gr',
        'description' => 'Que thưởng thịt gà phô mai Taotaopet với mùi vị cực kỳ hấp dẫn, dùng để thưởng cho cún trong quá trình huấn luyện hoặc cho ăn vặt.',
        'price' => 39000,
        'image' => 'gs://new_petshop_bucket/products/98/',
        'status' => true,
        'product_category_id' => 4,
      ],
    ];

    $dog_clothing_products = [
      [
        'name' => 'Áo con vịt thời trang cho chó mèo',
        'description' => 'Sản phẩm có vẻ ngoài nổi bật với hình con vịt đáng yêu. được làm từ vải polyester có khả năng thông thoáng nhất định, dễ vệ sinh, làm sạch. Chiếc áo có nhiều kích cỡ để lựa chọn phù hợp cho vừa với thú cưng nhà bạn.',
        'price' => 35000,
        'image' => 'gs://new_petshop_bucket/products/99/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Váy dạ hội cao cấp',
        'description' => 'Sự sang trọng, tinh tế và lịch sự chính là điểm nhấn của chiếc váy này. Khi mặc lên người, thú cưng của bạn trông sẽ thật lộng lẫy, nổi bật giữa những chốn đông người trong các sự kiện, cuộc đi chơi, hội nghị.',
        'price' => 250000,
        'image' => 'gs://new_petshop_bucket/products/100/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Áo 2 dây mỏng cho chó mèo',
        'description' => 'Sản phẩm với nhiều mẫu mã đa dạng hình thù đáng yêu cùng màu sắc bắt mắt, nổi bật. Chất liệu vải mềm mại tạo sự thông thoáng, mát mẻ cho thú cưng cùng thiết kế 2 dây năng động dễ vui chơi, chạy nhảy',
        'price' => 50000,
        'image' => 'gs://new_petshop_bucket/products/101/',
        'status' => true,
        'product_category_id' => 5
      ],
      [
        'name' => 'Áo ba lỗ họa tiết mùa hè',
        'description' => 'Với thiết kế là áo ba lỗ thì đây là sự lựa chọn tuyệt vời vào những ngày hè cho thú cưng của bạn. Với giá thành rẻ nhưng chất lượng ổn khiến các bé cảm thấy dễ chịu, mát mẻ hơn bởi khả năng thấm hút, co dãn của áo',
        'price' => 60000,
        'image' => 'gs://new_petshop_bucket/products/102/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Váy hồng Sakura cho chó mèo',
        'description' => 'Chiếc váy vô cùng xinh xắn, dễ thương phù hợp cho các bạn thú cưng nhỏ mặc để đi chơi, đi du lịch. Được thiết kế tinh tế với họa tiết kẻ sọc hồng, tô điểm với những bông hoa anh đào xinh xắn là điểm thu hút, đáng chú ý của sản phẩm này',
        'price' => 150000,
        'image' => 'gs://new_petshop_bucket/products/103/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Yếm cho chó mèo kèm dây dắt',
        'description' => 'Với thiết kế hợp xu hướng thời trang cùng màu sắc bắt mắt, làm từ chất liệu vải cao cấp mang tới sư thoải mái thì đây là sản phẩm không thể thiếu trong tủ đồ thú cưng của bạn. Thú cưng sẽ cực kì nổi bật, thu hút khi mặc lên chiếc yếm xinh xắn này. Ngoài ra còn được tặng kèm dây dắt khi mà dẫn thú cưng đi chơi',
        'price' => 250000,
        'image' => 'gs://new_petshop_bucket/products/104/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Áo kẻ kèm túi đeo chéo gấu Bear',
        'description' => 'Áo kẻ kèm túi đeo chéo gấu Bear là dạng áo ba lỗ đang được ưa thích vào mùa hè với họa tiết kẻ nhiều màu sắc, dễ làm nồi bật thú cưng. Với túi đeo chéo hình gấu ngộ nghĩnh phía sau càng làm các bạn thú cưng trở nên bắt mắt hơn khi diện lên bộ đồ này.',
        'price' => 110000,
        'image' => 'gs://new_petshop_bucket/products/105/',
        'status' => true,
        'product_category_id' => 5
      ],
      [
        'name' => 'Áo 2 dây caro Baby',
        'description' => 'Áo 2 dây caro Baby là sản phẩm phù hợp cho thú cưng tránh được phần nào cái nóng bức mùa hè. Được thiết kế 2 dây tạo cảm giác thoáng mát, mát mẻ, cùng những màu sắc rực rỡ và họa tiết kẻ caro thì đây.là lựa chọn tuyệt vời cho thú cưng vào mùa hè này',
        'price' => 55000,
        'image' => 'gs://new_petshop_bucket/products/106/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Áo ngực nơ chuông',
        'description' => 'Áo ngực nơ chuông mang tới cảm giác sang trọng, lịch sự cho cún cưng khi mặc vào. Bộ đồ này được thiết kế kèm nơ chuông đáng yêu cũng khả năng điều chỉnh dễ dàng tạo sự thoải mái, không bị gò bó, khó chịu cho bé cún.',
        'price' => 115000,
        'image' => 'gs://new_petshop_bucket/products/107/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Áo ba lỗ kẻ sọc',
        'description' => 'Áo ba lỗ kẻ sọc đang đươc ưa chuộng vào mùa hè bởi sự thoải mái, thông thoáng, mát mẻ mà nó mang lại. Với thiết kế có độ rộng hợp lý cùng khả năng thấm hút mồ hôi tốt là những ưu điểm giúp sản phẩm này được quan tâm nhiều bởi mọi người.',
        'price' => 140000,
        'image' => 'gs://new_petshop_bucket/products/108/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Áo 4 chân ngôi sao',
        'description' => 'Áo 4 chân ngôi sao mùa đông là sản phẩm có thiết kế mang tính thời trang cao với hoạt tiết ngôi sao bắt mắt. Được làm từ vải bông ấm áp có chiếc mũ đội đầu dễ thương giúp giữ ấm được toàn thân của cún cưng những ngày giá lạnh.',
        'price' => 145000,
        'image' => 'gs://new_petshop_bucket/products/109/',
        'status' => true,
        'product_category_id' => 5
      ],
      [
        'name' => 'Áo hình xương cá',
        'description' => 'Áo xương cá được làm từ chất vải len có độ dày dặn, thoải mái tốt cùng thiết kế đơn giản nhưng lại rất bắt mắt là sản phẩm đang nhận nhiều sự quan tâm của những người nuôi thú cưng.',
        'price' => 130000,
        'image' => 'gs://new_petshop_bucket/products/110/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Áo cộc tay thỏ carrot',
        'description' => 'Áo cộc tay thỏ carrot được yêu thích bởi thiết kế xinh xắn, đáng yêu, tạo vẻ nổi bật cho thú cưng của bạn mỗi khi ra đường. Với độ co dãn tốt, bé cưng nhà bạn sẽ cảm thấy thoải mái, mát mẻ khi khoác lên chiếc áo này.',
        'price' => 80000,
        'image' => 'gs://new_petshop_bucket/products/111/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Áo nỉ 4 chân cho chó mèo',
        'description' => 'Áo nỉ 4 chân cho chó mèo là sản phẩm cần thiết cho thú cưng vào những ngày đông lạnh. Được làm từ chất nỉ ấm áp giúp giữ ấm thân nhiệt và tránh ảnh hưởng đến sức khỏe của bé cún và mèo nhà bạn.',
        'price' => 110000,
        'image' => 'gs://new_petshop_bucket/products/112/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Áo ba lỗ vịt',
        'description' => 'Áo ba lỗ vịt là sản phẩm thời trang đẹp không thể thiếu vào những ngày hè oi nóng dành cho cún cưng hay mèo cưng của bạn. Với chất liệu thoáng mát, có khả năng thấm hút tốt, đem đến cho thú cưng nhà bạn cảm giác thoải mái, mát mẻ khi mặc vào.',
        'price' => 50000,
        'image' => 'gs://new_petshop_bucket/products/113/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Áo 2 dây mặt gấu Happy',
        'description' => 'Áo 2 dây mặt gấu Happy làm từ chất liệu thoáng mát tạo cảm giác thoải mái cho thú cưng. Sản phẩm giúp tăng tính thời trang cho bé cưng của bạn khi mặc vào',
        'price' => 70000,
        'image' => 'gs://new_petshop_bucket/products/114/',
        'status' => true,
        'product_category_id' => 5,
      ],
      [
        'name' => 'Áo ba lỗ vịt Hipi',
        'description' => 'Áo ba lỗ cho chó tạo cho chúng cảm giác thoải mái, thoáng mát khi mặc. Ngoài ra những chiếc áo đó cũng tạo cho cún hình ảnh đáng yêu, ngộ nghĩnh',
        'price' => 70000,
        'image' => 'gs://new_petshop_bucket/products/115/',
        'status' => true,
        'product_category_id' => 5,
      ],
    ];

    $dog_toy_products = [
      [
        'name' => 'Sao 2 tròn cao su dẻo',
        'description' => 'Sao 2 tròn cao su dẻo được làm bằng chất liệu cao su mềm đem lại cảm giác êm ái khi gặm, cắn kích thích thú cưng vận động, vui chơi.',
        'price' => 24000,
        'image' => 'gs://new_petshop_bucket/products/116/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Con tôm cao su dẻo',
        'description' => 'Đồ chơi mô hình con tôm cao su đáng yêu, ngộ nghĩnh được làm từ nhựa cao cấp chịu được tác động liên tục khi thú cưng chơi đùa.',
        'price' => 30000,
        'image' => 'gs://new_petshop_bucket/products/117/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Cá dây bố',
        'description' => 'Cá dây bố được bện bằng dây dù chắc chắn, có độ bền cơ học cao ngoài ra với kiểu dáng chú cá nhiều màu sắc đem lại sự thích thú, không nhàm chán khi chơi',
        'price' => 17000,
        'image' => 'gs://new_petshop_bucket/products/118/',
        'status' => true,
        'product_category_id' => 6
      ],
      [
        'name' => 'Khúc xương nhựa cao su dẻo cao cấp',
        'description' => 'Khúc xương nhựa cao su dẻo cao cấp là món đồ chơi tiện lợi, ưa thích dành cho cún cưng nhà bạn. Với kểu dáng khúc xương kích thích các boss nhỏ',
        'price' => 40000,
        'image' => 'gs://new_petshop_bucket/products/119/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Tạ gai cao su dẻo',
        'description' => 'Tạ gai cao su dẻo với nhiều màu sắc tươi tắn, kiểu dáng nhỏ gọn xinh xắn được làm bằng chất liệu cao su mềm, không độc hại sẽ là món đồ chơi ưa thích,',
        'price' => 17000,
        'image' => 'gs://new_petshop_bucket/products/120/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Chuột đuôi bi',
        'description' => 'Mèo cưng có bản năng đuổi bắt chuột vì vậy với món đồ chơi chuột đuôi bị được bện từ vải mềm chắc chắn, siêu đẹp đem lại sự hứng thú, kích thích',
        'price' => 17000,
        'image' => 'gs://new_petshop_bucket/products/121/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Cá lông vũ',
        'description' => 'Cá lông vũ với mẫu mã bắt bắt hình chú cá ngộ nghĩnh, dễ thương. Phần đuôi cá được làm từ lông vũ khi chơi đùa phe phẩy kích thích các boss nhỏ vờn nghịch, nô hoài không thấy chán.',
        'price' => 17000,
        'image' => 'gs://new_petshop_bucket/products/122/',
        'status' => true,
        'product_category_id' => 6
      ],
      [
        'name' => 'Con ong cao su đồ chơi cho chó mèo',
        'description' => 'Đồ chơi con ong cao su với kiểu dáng đáng yêu, ngộ nghĩnh bắt mắt chắc chắn sẽ làm các bé cún cảm thấy bị cuốn hút muôn nô đùa cùng.',
        'price' => 14000,
        'image' => 'gs://new_petshop_bucket/products/123/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bánh Hotdog cao su đồ chơi cho chó mèo',
        'description' => 'Bánh Hotdog cao su đồ chơi cho chó mèo với kiểu dáng bắt mắt, gần gũi đem lại sự tò mò, kích thích các bé chơi đùa, phát ra tiếng kêu chíp chíp.',
        'price' => 20000,
        'image' => 'gs://new_petshop_bucket/products/124/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Chai bia đồ chơi dành cho thú cưng',
        'description' => 'Mô hình chai bia đồ chơi sẽ là món quà ý nghĩa, thú vị mà mọi người có thể dành cho thú cưng của mình. Nổi bật với nhiều màu sắc bắt mắt, tươi tắn',
        'price' => 28000,
        'image' => 'gs://new_petshop_bucket/products/125/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Gà đầu đỏ xanh đồ chơi thú cưng',
        'description' => 'Chỉ với mô hình đồ chơi gà đầu đỏ xanh được làm bằng chất liệu an toàn, siêu bền giúp thú cưng có thể thoải mái chơi đùa, giải trí.',
        'price' => 21000,
        'image' => 'gs://new_petshop_bucket/products/126/',
        'status' => true,
        'product_category_id' => 6
      ],
      [
        'name' => 'Lốp xe cao su dành cho chó mèo',
        'description' => 'Đồ chơi phát tiếng kêu hình lốp xe bằng cao su là món đồ chơi sẽ đem lại những giây phút chơi đùa thoả thích, giải trí giảm căng thẳng dàng cho thú cưng.',
        'price' => 17000,
        'image' => 'gs://new_petshop_bucket/products/127/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Đùi gà lớn',
        'description' => 'Mô hình đùi gà lớn đồ chơi là món đồ chơi hấp dẫn cho thú cưng chơi đùa mỗi khi rảnh rỗi hay ngứa răng muốn cắn phá đồ đạc.',
        'price' => 25000,
        'image' => 'gs://new_petshop_bucket/products/128/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Dĩa bay huấn luyện dành cho chó',
        'description' => 'Dĩa bay huấn luyện với chất liệu silicone siêu bền chịu được mọi lực cắn của cún khi vui chơi. Với đĩa bay bạn có thể dễ dàng chơi đùa với thú cưng',
        'price' => 14000,
        'image' => 'gs://new_petshop_bucket/products/129/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Dép em bé cao su dành cho chó mèo',
        'description' => 'Dép em bé đồ chơi cao su cho thú cưng với hình dáng ngộ nghĩnh, dễ thương màu sắc tươi tắn nổi bật. Món đồ chơi sẽ là món quà ý nghĩa, đem lại sự thích thú',
        'price' => 14000,
        'image' => 'gs://new_petshop_bucket/products/130/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bánh Hamburger cao su',
        'description' => 'Bánh Hamburger cao su mô hình đồ chơi với kiểu dáng bắt mắt, cuốn hút dành cho thú cưng, giúp kích thích cho các boss nhỏ nhai, gặm vào thời kì ngứa răng',
        'price' => 17000,
        'image' => 'gs://new_petshop_bucket/products/131/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Đùi gà cao su',
        'description' => 'Đùi gà cao su mô hình là món đồ chơi yêu thích đối với nhiều bé thú cưng trong gia đình. Đồ chơi với kiểu dáng đáng yêu, màu sắc nổi bật',
        'price' => 15000,
        'image' => 'gs://new_petshop_bucket/products/132/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bóng hàm răng',
        'description' => 'Bóng hàm răng với hoạ tiết in hình hàm răng vui nhộn, ngộ nghĩnh làm thú cưng thích thú. Bóng được làm bằng nhựa cao cấp, chịu được va chạm liên tục',
        'price' => 14000,
        'image' => 'gs://new_petshop_bucket/products/133/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Gậy tét đít BO',
        'description' => 'Gậy tét đít BO- Dài được làm bằng chất liệu nhựa cao cấp, dẻo chống gẫy thích hợp trong việc huấn luyện, dạy bảo thú cưng nhà bạn.',
        'price' => 33000,
        'image' => 'gs://new_petshop_bucket/products/134/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Quả cầu dây thừng',
        'description' => 'Quả cầu dây thừng làm từ chất liệu an toàn, màu sắc thu hút bắt mắt đem lại sự thích thú, hăng say khi chơi đùa với đồ chơi.',
        'price' => 17000,
        'image' => 'gs://new_petshop_bucket/products/135/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Đồ chơi con gà chút chít',
        'description' => 'Đồ chơi con gà kêu chút chít dành cho thú cưng với kiểu dáng hình chú gà đáng yêu, ngộ nghĩnh. Đồ chơi làm bằng chất liệu cao su siêu bền có độ đàn hồi tốt',
        'price' => 31000,
        'image' => 'gs://new_petshop_bucket/products/136/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Đồ chơi dây thừng hình quả tạ',
        'description' => 'Đồ chơi dây thừng hình quả tạ bằng sợi cotton chất lượng cao, dệt kim nhỏ gọn, chắc chắn không gây độc hại. Đồ chơi giúp các boss nhỏ có thể thoải mái gặm',
        'price' => 42000,
        'image' => 'gs://new_petshop_bucket/products/137/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bóng thừng tay cầm size lớn',
        'description' => 'Bóng thừng tay cầm size lớn là món đồ chơi thú vị giúp giảm stress, giúp thú cưng thư giãn hạn chế việc đồ đạc bị hư khi các bé trong giai đoạn ngứa răng.',
        'price' => 28000,
        'image' => 'gs://new_petshop_bucket/products/138/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bóng lật đật có gắn chuột bông',
        'description' => 'Bóng lật đật có gắn chuột bông là món đồ chơi ưa thích đối nhiều bé thú cưng với thiết kế như một lật đật không bao giờ đổ,',
        'price' => 21000,
        'image' => 'gs://new_petshop_bucket/products/139/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bóng cao su dây thừng',
        'description' => 'Bóng cao su dây thừng với thiết kế bóng cao su kèm theo dây thừng chắc chắn, siêu bền tạo cảm giác đung đưa, thích thú khi thú cưng vờn nghịch.',
        'price' => 24000,
        'image' => 'gs://new_petshop_bucket/products/140/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bóng nhựa chuông - Set 4 trái',
        'description' => 'Bóng nhựa chuông - Set 4 trái là từ chất liệu nhựa bền, chịu được va chạm liên tục khi nô đùa. Set gồm 4 trái bóng với các màu sắc khác nhau bắt mắt,',
        'price' => 21000,
        'image' => 'gs://new_petshop_bucket/products/141/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Set túi đồ chơi nhồi bông mini',
        'description' => 'Set túi đồ chơi nhồi bông mini với nhiều kiểu dáng, hình hài dễ thương, xinh xắn cho các bé nô đùa, được làm bằng chất liệu thân thiện, không gây độc hại.',
        'price' => 37000,
        'image' => 'gs://new_petshop_bucket/products/142/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bóng cao su hình dấu chân cún',
        'description' => 'Bóng cao su hình dấu chân cún có kiểu dáng ngộ nghĩnh, đáng yêu giúp thú cưng nhà bạn có thể thoải mái vui chơi đồ đùa kể cả khi ở một mình.',
        'price' => 15000,
        'image' => 'gs://new_petshop_bucket/products/143/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bóng cao su gai',
        'description' => 'Bạn đang đi tìm một món đồ chơi đem lại sự thích thú cho thú cưng mà vừa an toàn, thân thiện đối sức khoẻ của thú cưng? Vậy sẽ là món quà hoàn hảo dành cho các bé. Với kiểu dáng nhỏ nhắn, đáng yêu siêu bền bỉ đem lại những giây phút vui chơi giải trí cho các boss nhỏ.',
        'price' => 20000,
        'image' => 'gs://new_petshop_bucket/products/144/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Vịt mỏ cam chíp chíp',
        'description' => 'Vịt mỏ cam là món đồ chơi ưa thích, cuốn hút dành cho thú cưng nhà bạn nhà bạn với kiểu dáng vui nhọn, đáng yêu.',
        'price' => 50000,
        'image' => 'gs://new_petshop_bucket/products/145/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Cây kem đồ chơi dành cho thú cưng',
        'description' => 'Cây kem đồ chơi dành cho thú cưng sẽ là món đồ chơi giúp thú cưng nô đùa xua tan căng thẳng, kích thích vận động cho các bé luôn ở trong thể trạng tốt nhất.',
        'price' => 17000,
        'image' => 'gs://new_petshop_bucket/products/146/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Cá catnip chất liệu bông dài 40cm',
        'description' => 'Cá catnip 40 cm dành cho mèo cưng với kiểu dáng mô hình như cá thật đem lại sự thích thú, cuốn hút cho thú cưng.',
        'price' => 69000,
        'image' => 'gs://new_petshop_bucket/products/147/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bóng cao su mặt chó',
        'description' => 'Bóng cao su mặt chó với hình dáng xinh xắn, dễ thương cùng màu sắc nổi bật sẽ là món quá ý nghĩa đem lại sự thích thú đến cho thú cưng nhà bạn.',
        'price' => 20000,
        'image' => 'gs://new_petshop_bucket/products/148/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bóng xương gai đồ chơi cho thú cưng',
        'description' => 'Bóng xương gai với nhiều màu sắc bắt mắt, cuốn hút cho thú cưng giúp các bé có thể thoải mái gặm mà không sợ bị la mắng.',
        'price' => 19000,
        'image' => 'gs://new_petshop_bucket/products/149/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Bóng bảy sắc chuông đồ chơi cho chó mèo',
        'description' => 'Bóng bảy sắc chuông nhiều màu sắc nổi bật , bắt mắt cùng kiểu dáng hình xương đan xen thành quả bóng tăng sự thích thú cho các boss nhỏ.',
        'price' => 28000,
        'image' => 'gs://new_petshop_bucket/products/150/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Đồ chơi con chuột lò xo',
        'description' => 'Đồ chơi con chuột gắn lò xo với đính kèm phần lông vũ màu sắc bắt mắt kích thích mèo cưng vờn nghịch, vô bắt khi vui chơi.',
        'price' => 14000,
        'image' => 'gs://new_petshop_bucket/products/151/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Đồ chơi dây thừng khúc xương nhựa',
        'description' => 'Đồ chơi dây thừng khúc xương nhựa được thiết kế bằng các sợi vải xoắn, đan xen chắc chắn. Ở giữ kèm thêm khúc xương nhựa bắt mắt kích thích sự tò mò',
        'price' => 24000,
        'image' => 'gs://new_petshop_bucket/products/152/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Thú ngộ nghĩnh nhồi bông dành cho thú cưng',
        'description' => 'Thú ngộ nghĩnh nhồi bông sẽ là món quà ý nghĩa dành cho thú cưng của bạn để hàng ngày vui chơi. Thú nhồi bông có kiểu dáng đáng yêu, chất liệu mềm mại',
        'price' => 89000,
        'image' => 'gs://new_petshop_bucket/products/153/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Đồ chơi dây thừng 2 đầu tròn',
        'description' => 'Đồ chơi dây thừng 2 đầu tròn là phụ kiện đồ chơi tiện ích dành cho thú cưng khi các boss đang vào thời kì thay răng hay có thói quen cào cắn đồ đạc.',
        'price' => 38000,
        'image' => 'gs://new_petshop_bucket/products/154/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Đồ chơi con chim kêu',
        'description' => 'Đồ chơi con chim kêu với kiểu dáng ngộ nghĩnh, sắc màu chắc chắn sẽ làm món quà thú vị dành cho thú cưng nhà bạn, có thể phát tiếng kêu vui nhộn',
        'price' => 83000,
        'image' => 'gs://new_petshop_bucket/products/155/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Hình nộm động vật hoạt hình',
        'description' => 'Hình nộm động vật hoạt hình dễ thương, đáng yêu với nhiều kiểu dáng, màu sắc khác nhau sẽ là món quà tuyệt vời dành cho thú cưng nhà bạn vui chơi',
        'price' => 47000,
        'image' => 'gs://new_petshop_bucket/products/156/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Gấu trúc dệt vải',
        'description' => 'Gấu trúc dệt vải được làm bằng vải dù đan xen chắc chắn, êm ái không làm kích ứng cho thú cưng, thích hợp dành cho các bé đang vào trong thời kì mọc răng',
        'price' => 55000,
        'image' => 'gs://new_petshop_bucket/products/157/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Xương gai đồ chơi dành cho chó mèo',
        'description' => 'Món đồ chơi xương gai giúp thứ có thể thoải mái đùa nghịch không sợ bị mắng, có thể độc lập chơi khi ở một mình, giúp bạn có thêm thời gian rảnh',
        'price' => 31000,
        'image' => 'gs://new_petshop_bucket/products/158/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Vịt trời nhồi bông',
        'description' => 'Vịt trời nhồi bông là phụ kiện đồ chơi ưa thích đối với nhiều boss nhỏ vởi kiếu dáng dễ thương, thân thuộc, giúp hạn chế việc thú cưng căn nghịch đồ đạc.',
        'price' => 56000,
        'image' => 'gs://new_petshop_bucket/products/159/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Đồ chơi vòng gai',
        'description' => 'Thú cưng nhà bạn có thói quen cào căn làm hỏng đồ đạc hay lười vận động làm bạn phải đau đầu. Mọi thứ thật dễ dàng với đồ chơi vòng gai ngộ nghĩnh',
        'price' => 38000,
        'image' => 'gs://new_petshop_bucket/products/160/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Chuông đồ chơi cho cún hỗ trợ huấn luyện',
        'description' => 'Chuông đồ chơi cho cún đã được nhiều người nuôi tin dùng để huấn luyện thú cưng. Sản phẩm tuy nhỏ mà có võ, với âm thanh to, đanh và cực kỳ bắt tai.',
        'price' => 36000,
        'image' => 'gs://new_petshop_bucket/products/161/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Đồ chơi cao su cho chó mèo nhiều hình ngộ nghĩnh',
        'description' => 'Đồ chơi cao su là cứu cánh cho những trường hợp chó mèo phá phách đồ đạc. Với màu sắc sặc sỡ, sản phẩm sẽ dễ dàng thu hút được sự chú ý của các bé cưng.',
        'price' => 29000,
        'image' => 'gs://new_petshop_bucket/products/162/',
        'status' => true,
        'product_category_id' => 6,
      ],
      [
        'name' => 'Dây thừng gặm cho cún giải tỏa stress',
        'description' => 'Dây thừng gặm cho cún thích hợp cho các boss đang vào thời kì mọc răng, ngứa răng. Sản phẩm giúp cún giảm stress, hạn chế cắn phá đồ đạc gây hỏng hóc.',
        'price' => 35000,
        'image' => 'gs://new_petshop_bucket/products/163/',
        'status' => true,
        'product_category_id' => 6,
      ],
    ];

    $dog_collar_products = [
      [
        'name' => 'Vòng cổ trị ve Taotao pet dành cho chó mèo',
        'description' => 'Hiện nay, thương hiệu Taotao Pet đã cho ra sản phẩm vòng cổ trị ve Taotao Pet bằng hợp chất margosa được tiết ra bảo vệ cơ thể thú cưng hiệu quả.',
        'price' => 56000,
        'image' => 'gs://new_petshop_bucket/products/164/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Bảng 12 vòng cổ nhiều màu',
        'description' => 'Bảng 12 vòng cổ nhiều màu cho người nuôi có thể dễ dàng lựa chọn theo sở thích, thay đổi theo từng ngày. Vòng cổ với chất liệu vải dù chắc chắn, dày dặn',
        'price' => 175000,
        'image' => 'gs://new_petshop_bucket/products/165/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Bảng 12 vòng cổ Zichen 0.8cm loại xịn',
        'description' => 'Bảng 12 vòng cổ Zichen 0.8cm loại xịn là dòng sản phẩm phụ kiện chuyên dụng dành cho chó mèo đến từ thương hiệu Zichen nổi tiếng.',
        'price' => 175000,
        'image' => 'gs://new_petshop_bucket/products/166/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Bảng vòng cổ Zichen',
        'description' => 'Bảng vòng cổ Zichen là dòng phụ kiện chuyên dùng dành riêng cho chó mèo với màu sắc đẹp mắt, đa dạng đi kèm với chất liệu vảo cao cấp, siêu bền',
        'price' => 105000,
        'image' => 'gs://new_petshop_bucket/products/167/',
        'status' => true,
        'product_category_id' => 7
      ],
      [
        'name' => 'Vòng cổ đan tay nhiều chuông cho chó mèo',
        'description' => 'Vòng cổ đan tay nhiều chuông cho chó mèo được thiết kế dành riêng cho các boss nhỏ được bện bằng dây dù chắc chắn, không sợ bị phai màu theo thời gian.',
        'price' => 12000,
        'image' => 'gs://new_petshop_bucket/products/168/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Vòng cổ mặt mèo',
        'description' => 'Vòng cổ mặt mèo với nhiều màu sắc cuốn hút, tươi tắn đem lại sự nổi bật cho thú cưng khi đeo trên cổ, được kèm thêm chiếc chuông nhỏ giúp định vị thú cưng',
        'price' => 37000,
        'image' => 'gs://new_petshop_bucket/products/169/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Vòng cổ chuông ngọc trai Zichen',
        'description' => 'Vòng cổ chuông ngọc trai Zichen được làm bằng vải da đan vào nhau chắc chắn, dày dặn có độ bền cao, có gắn thêm phụ kiện chuông hình ngọc trai nhiều màu',
        'price' => 38000,
        'image' => 'gs://new_petshop_bucket/products/170/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Vòng cổ Zichen chuông to',
        'description' => 'Vòng cổ Zichen chuông to với thiết kế cực kì chắc chắn, bền đẹp cùng nhiều màu sắc cuốn hút, làm nổi bật thú cưng trước đám đông.',
        'price' => 35000,
        'image' => 'gs://new_petshop_bucket/products/171/',
        'status' => true,
        'product_category_id' => 7
      ],
      [
        'name' => 'Vòng cổ da',
        'description' => 'Chỉ với vòng cổ da chuyên dụng dành cho thú cưng với chất liêu dày dặn, bạn sẽ loại bỏ được nỗi lo lạc khi thú cưng ra ngoài chơi',
        'price' => 25000,
        'image' => 'gs://new_petshop_bucket/products/172/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Vòng cổ chuông 1.5cm VN',
        'description' => 'Vòng cổ chuông 1.5 cm VN là vòng cổ dành cho chó mèo với thiết kế dày dặn, chắc chắn khi đeo đem lại sự nổi bật, gọn gàng trên cổ các boss nhỏ.',
        'price' => 9000,
        'image' => 'gs://new_petshop_bucket/products/173/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Vòng cổ đại 3.0cm VN',
        'description' => 'Vòng cổ cỡ đại 3.0cm VN dành cho thú cưng giúp người nuôi có thể quản lí, trông coi thú cưng nhà mình trong những chuyến đi chơi, đi dạo bên ngoài',
        'price' => 20000,
        'image' => 'gs://new_petshop_bucket/products/174/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Vòng cổ đơn sắc VN',
        'description' => 'Vòng cổ đơn sắc VN là phụ kiện giúp người nuôi có thể dễ dàng chăm sóc, trông nom thú cưng khi đi chơi ở bên ngoài, nơi công cộng.',
        'price' => 12000,
        'image' => 'gs://new_petshop_bucket/products/175/',
        'status' => true,
        'product_category_id' => 7
      ],
      [
        'name' => 'Vòng cổ xanh đỏ đen dành cho chó mèo',
        'description' => 'Vòng cổ xanh đỏ đen được thiết kế với kểu dáng sành điệu, đẹp mắt đem lại sự nổi bật khi đeo lên người các boss nhỏ, được làm từ chất liệu vải dù siêu bền',
        'price' => 17000,
        'image' => 'gs://new_petshop_bucket/products/176/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Vòng cổ nhôm vàng - trắng',
        'description' => 'Vòng cổ nhôm vàng - trắng với chất liệu bằng nhôm siêu bền, cứng cap chịu được các tác động khắc nghiệt của môi trường, là phụ kiện trang sức cho thú cưng',
        'price' => 35000,
        'image' => 'gs://new_petshop_bucket/products/177/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Vòng cổ nơ',
        'description' => 'Vòng cổ nơ xinh xắn với kiểu dáng nổi bật giúp thú cưng nhà bạn trở nên “sành điệu” hơn. Sản phẩm chất liệu vải dù có độ bền cao, êm ái không gây cọ xát',
        'price' => 25000,
        'image' => 'gs://new_petshop_bucket/products/178/',
        'status' => true,
        'product_category_id' => 7,
      ],
      [
        'name' => 'Set 03 vòng cổ Taotaopet',
        'description' => 'Set 03 vòng cổ Taotaopet là phụ kiện chuyên dụng giúp làm dây dắt thú cưng đi dạo hàng ngày, giúp cho thú cưng nhà bạn trở nên nổi bật hơn.',
        'price' => 34000,
        'image' => 'gs://new_petshop_bucket/products/179/',
        'status' => true,
        'product_category_id' => 7,
      ],
    ];

    $dog_leash_products = [
      [
        'name' => 'Dây yếm rẻ dành cho chó mèo',
        'description' => 'Dây yếm rẻ mang kiểu dáng thanh lịch, sang trọng với màu sắc, hoa văn nổi bật. Chất liệu vòng cổ mềm mại, gọn gàng không làm thú cưng khó chịu.',
        'price' => 12000,
        'image' => 'gs://new_petshop_bucket/products/180/',
        'status' => true,
        'product_category_id' => 8,
      ],
      [
        'name' => 'Dây kéo ngực màu tương phản dành cho chó mèo cưng',
        'description' => 'Dây kéo ngực màu tương phản chắc chắn giúp bạn dễ dàng kiểm soát, trông coi thú cưng trong phạm vi kiểm soát khi đi dạo trên phố, công viên.',
        'price' => 52000,
        'image' => 'gs://new_petshop_bucket/products/181/',
        'status' => true,
        'product_category_id' => 8,
      ],
      [
        'name' => 'Dây dắt yếm VN',
        'description' => 'Dây dắt yếm VN với chất liệu vải dù bền bỉ, co dãn chịu được ngoại lực thích hợp để đeo giúp người nuôi trông nom thú cưng.',
        'price' => 25000,
        'image' => 'gs://new_petshop_bucket/products/182/',
        'status' => true,
        'product_category_id' => 8,
      ],
      [
        'name' => 'Dây dắt yếm Police Zichen 2cm',
        'description' => 'Dây dắt yếm Police Zichen với thiết kế ôm sát cực kì chắc chắn và êm ái được nhiều người nuôi tin dùng trong việc theo sát và trông nom thú cưng',
        'price' => 78000,
        'image' => 'gs://new_petshop_bucket/products/183/',
        'status' => true,
        'product_category_id' => 8
      ],
      [
        'name' => 'Dây dắt vòng cổ Police Zichen 1.5cm',
        'description' => 'Dây dắt vòng cổ Police Zichen được làm bằng chất liệu có độ bền cao, thiết kế dầy dặn cực kì chắc chắn không gây cọ xát tạo cảm giác êm ái khi đeo vào cổ thú cưng.',
        'price' => 67000,
        'image' => 'gs://new_petshop_bucket/products/184/',
        'status' => true,
        'product_category_id' => 8,
      ],
      [
        'name' => 'Dây dắt Zichen vòng cổ tay xốp',
        'description' => 'Dây dắt Zichen vòng cổ tay xốp với kiểu dáng thanh lịch, sang trọng chắc chắn cùng phần tay cầm được kèm lót tay xốp êm ái cực kì tiện lợi.',
        'price' => 29000,
        'image' => 'gs://new_petshop_bucket/products/185/',
        'status' => true,
        'product_category_id' => 8,
      ],
      [
        'name' => 'Dây dắt vòng cổ VN',
        'description' => 'Dây dắt vòng cổ VN chất lượng cao với chất liệu siêu bền, chịu đươc tác động vật lí êm ái, mềm mại không gậy cọ xát, kích ứng cho thú cưng.',
        'price' => 33000,
        'image' => 'gs://new_petshop_bucket/products/186/',
        'status' => true,
        'product_category_id' => 8,
      ],
      [
        'name' => 'Dây dắt cánh thiên thần',
        'description' => 'Dây dắt cánh thiên thần mang lại kiểu dáng dễ thương, đáng yêu đến cho các boss nhỏ khi đeo. Dây dắt được làm bằng chất liệu vải tổng hợp có độ bền cao',
        'price' => 34000,
        'image' => 'gs://new_petshop_bucket/products/187/',
        'status' => true,
        'product_category_id' => 8
      ],
      [
        'name' => 'Dây dắt vòng cổ nhiều hoạ tiết Zeichen',
        'description' => 'Dây dắt vòng cổ nhiều hoạ tiết Zeichen là phụ kiện giúp cho thú cưng nhà bạn trở nên nổi bật, “sành điệu” hơn khi đeo, được làm bằng chất liệu vải dù',
        'price' => 240000,
        'image' => 'gs://new_petshop_bucket/products/188/',
        'status' => true,
        'product_category_id' => 8,
      ],
      [
        'name' => 'Dây yếm in mặt mèo',
        'description' => 'Dây yếm in mặt mèo hay còn gọi là dây xích đai ngựa giúp tạo cảm giác thoải mái, dễ chịu hơn đối với thú cưng thay vì đeo dây xích vào cổ.',
        'price' => 105000,
        'image' => 'gs://new_petshop_bucket/products/189/',
        'status' => true,
        'product_category_id' => 8,
      ],
      [
        'name' => 'Dây dắt yếm Zeichen nhiều hoạt tiết',
        'description' => 'Dây dắt yếm Zeichen nhiều hoạt tiết với thiết kế dễ dàng điều chỉnh kích thước phù hợp với từng giống thú cưng, được làm bằng chất liệu vải siêu bền, êm ái',
        'price' => 343000,
        'image' => 'gs://new_petshop_bucket/products/190/',
        'status' => true,
        'product_category_id' => 8,
      ],
      [
        'name' => 'Dây cổ 7 màu Zichen 1.0cm',
        'description' => 'Dây cổ 7 màu Zichen 1.0 cm mang nhiều màu sắc bắt mắt cùng chất liệu vải siêu bền, không lo rách hay đứt có khoá nhựa dễ dàng tháo mở',
        'price' => 27000,
        'image' => 'gs://new_petshop_bucket/products/191/',
        'status' => true,
        'product_category_id' => 8
      ],
      [
        'name' => 'Dây yếm 7 màu Taotao 1.0cm',
        'description' => 'Dây yếm 7 màu Taotao 1.0 cm là sự lựa chọn hoàn hảo để người nuôi có thể dễ dàng quản lí, trông nom thú cưng khi đi dạo, đi chơi một cách thoải mái.',
        'price' => 34000,
        'image' => 'gs://new_petshop_bucket/products/192/',
        'status' => true,
        'product_category_id' => 8,
      ],
      [
        'name' => 'Dây dắt hình con thỏ dễ thương',
        'description' => 'Vòng cổ nhôm vàng - trắng với chất liệu bằng nhôm siêu bền, cứng cap chịu được các tác động khắc nghiệt của môi trường, là phụ kiện trang sức cho thú cưng',
        'price' => 50000,
        'image' => 'gs://new_petshop_bucket/products/193/',
        'status' => true,
        'product_category_id' => 8,
      ],
      [
        'name' => 'Dây dắt chó mèo kèm vòng cổ chắc chắn, nhiều màu sắc',
        'description' => 'Vòng cổ nơ xinh xắn với kiểu dáng nổi bật giúp thú cưng nhà bạn trở nên “sành điệu” hơn. Sản phẩm chất liệu vải dù có độ bền cao, êm ái không gây cọ xát',
        'price' => 32000,
        'image' => 'gs://new_petshop_bucket/products/194/',
        'status' => true,
        'product_category_id' => 8,
      ],
    ];

    $dog_muzzles_products = [
      [
        'name' => 'Rọ mõm da dành cho chó size M',
        'description' => 'Với chiếc rọ mõm da cao cấp sẽ giúp người nuôi cảm thấy an tâm khi dẫn các bé ra ngoài đi chơi, đảm bảo không gây nguy hiểm cho người xung quanh',
        'price' => 25000,
        'image' => 'gs://new_petshop_bucket/products/195/',
        'status' => true,
        'product_category_id' => 9,
      ],
      [
        'name' => 'Rọ mõm nhựa cho chó',
        'description' => 'Khi bạn dẫn cún cưng đến nơi công cộng, việc đảm bảo an toàn cho tất cả mọi người và thú cưng là vô cùng cần thiết. Vì vậy, việc chuẩn bị cho cún cưng nhà bạn một chiếc rọ mõm nhựa là việc cực kỳ thiết yếu. Điều này sẽ đảm bảo an toàn tối đa khi dẫn các bé ra ngoài đi chơi.',
        'price' => 13000,
        'image' => 'gs://new_petshop_bucket/products/196/',
        'status' => true,
        'product_category_id' => 9,
      ],
    ];

    $dog_water_bowls_products = [
      [
        'name' => 'Bình sữa chó mèo kèm dụng cụ vệ sinh',
        'description' => 'Bình sữa chó mèo kèm phụ kiện bộ nhỏ với 3 núm ti khác nhau phù hợp với từng giống thú nuôi, được tặng kèm thêm gậy lông cọ giúp vệ sinh bình sữa',
        'price' => 28000,
        'image' => 'gs://new_petshop_bucket/products/197/',
        'status' => true,
        'product_category_id' => 10,
      ],
      [
        'name' => 'Bình nước gài chuồng xịn có hộp 500ml',
        'description' => 'Bình nước gài chuồng có nút van điều chỉnh được lưu lượng nước chảy khi uống giúp thuận tiện trong việc chăm sóc thú cưng.',
        'price' => 78000,
        'image' => 'gs://new_petshop_bucket/products/198/',
        'status' => true,
        'product_category_id' => 10,
      ],
      [
        'name' => 'Bình nước gài chuồng rẻ chất liệu nhựa, vòi kim loại',
        'description' => 'Bình nước gài chuồng giá rẻ có phần móc treo cố dịnh vào chuồng giúp thú cưng có thể thoải mái uống nước, chất liệu nhựa chịu lực cứng cáp',
        'price' => 35000,
        'image' => 'gs://new_petshop_bucket/products/199/',
        'status' => true,
        'product_category_id' => 10,
      ],
      [
        'name' => 'Máy lọc nước thông minh cho chó mèo',
        'description' => 'Máy lọc nước thông minh cho chó mèo sẽ giúp loại bỏ cặn bã thức ăn, lông rụng trong bát. Nhờ đó, các boss nhỏ sẽ luôn có nguồn nước sạch để uống.',
        'price' => 30000,
        'image' => 'gs://new_petshop_bucket/products/200/',
        'status' => true,
        'product_category_id' => 10,
      ],
      [
        'name' => 'Bình nước gài chuồng cao cấp dành cho chó mèo',
        'description' => 'Bình nước gài chuồng dành cho chó mèo được làm từ nhựa cao su cao cấp, không ảnh hưởng tới sức khỏe thú nuôi, phù hợp với tất cả các loại chuồng sắt.',
        'price' => 64000,
        'image' => 'gs://new_petshop_bucket/products/201/',
        'status' => true,
        'product_category_id' => 10,
      ],
    ];

    $dog_feeding_bowls_products = [
      [
        'name' => 'Bát inox size lớn đựng thức ăn thú cưng',
        'description' => 'Bát inox size lớn có đường kính 34cm, chứa được nhiều thức ăn phù hợp các giống chó to. Sản phẩm được làm bằng chất liệu inox chống han gỉ, có độ bền cao',
        'price' => 132000,
        'image' => 'gs://new_petshop_bucket/products/202/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát inox size nhỏ đựng thức ăn thú cưng',
        'description' => 'Bát inox size nhỏ có đường kính 30cm phù hợp với các giống chó dưới 10kg. Sản phẩm được làm bằng chất liệu inox chống han gỉ, có độ bền cao.',
        'price' => 110000,
        'image' => 'gs://new_petshop_bucket/products/203/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đơn inox hình đoá hoa',
        'description' => 'Bát đơn inox hình đoá hoa với lòng bát làm bằng inox siêu bền chịu lực tốt, phần thành bát cao ngăn được thức ăn vương vãi ra bên ngoài.',
        'price' => 44000,
        'image' => 'gs://new_petshop_bucket/products/204/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát gài chuồng hình bò sữa',
        'description' => 'Bát gài chuồng hình bò sữa giúp cố định bát thức ăn vào chuồng tránh tình trạng bát bị xê dịch, đổ vương vãi thức ăn khi thú cưng sinh hoạt.',
        'price' => 58000,
        'image' => 'gs://new_petshop_bucket/products/205/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Tô thức ăn tự động Pet blow Plus',
        'description' => 'Tô thức ăn tự động Pet blow Plus với thiết kế thông minh gồm phần bình nhựa chứa thức ăn dự trữ, giúp người dùng chăm sóc thú cưng ngay cả khi đi du lịch',
        'price' => 145000,
        'image' => 'gs://new_petshop_bucket/products/206/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát tự động cho thú cưng, đựng thức ăn hoặc nước uống 3.5L',
        'description' => 'Khi bạn vắng nhà, đi du lịch dài ngày mà lo lắng không thể chăm sóc thú cưng. Bát tự động cho thú cưng với kích thước lên tới 3.5 L sẽ xua tan đi nỗi lo đó',
        'price' => 95000,
        'image' => 'gs://new_petshop_bucket/products/207/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Máy ăn uống tự động cao cấp có hai ngăn cho chó mèo',
        'description' => 'Máy ăn uống tự động cao cấp với thiết kế tinh tế với phần bình giúp cấp nước, thức ăn tự động, cùng kiểu dáng vuông vức, gọn nhẹ thuận tiện khi sử dụng.',
        'price' => 189000,
        'image' => 'gs://new_petshop_bucket/products/208/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát cho ăn tự động dành cho chó mèo',
        'description' => 'Bát cho ăn tự động với ngăn chứa thức ăn phía trên, giúp người nuôi tiết kiệm thời gian chăm sóc thú cưng, phù hợp với những gia đình bận rộn.',
        'price' => 154000,
        'image' => 'gs://new_petshop_bucket/products/209/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Máy cho ăn tự động kèm bình nước dành cho chó mèo',
        'description' => 'Máy cho ăn tự động kèm bình nước sẽ giúp cung cấp cho các boss đầy đủ thức ăn và nước uống, hợp với những gia đình bận rộn, hoặc thường xuyên đi du lịch',
        'price' => 168000,
        'image' => 'gs://new_petshop_bucket/products/210/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát ăn tự động nắp hai khe 73x51x47cm',
        'description' => 'Bát ăn tự động nắp hai khe với thiết kế bình đựng dự trữ thức ăn, nước uống giúp cho thú cưng luôn có đủ nguồn thức ăn. Sản phẩm cực kỳ hữu dụng khi bạn đi du lịch, hay không có ở nhà chăm sóc thú cưng. Ngoài ra với cơ chế tự động cung cấp thức ăn, nước uống sẽ tiết kiệm được thời gian cho người nuôi.',
        'price' => 63000,
        'image' => 'gs://new_petshop_bucket/products/211/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đơn inox hình ốc sên đựng thức ăn nước uống cho pet',
        'description' => 'Bát đơn inox hình ốc sên với lòng bát từ inox siêu bền chống bám bẩn, chịu được tác động vật lí, giải quyết hiệu quả vấn đề thú cưng làm vương vãi thức ăn',
        'price' => 42000,
        'image' => 'gs://new_petshop_bucket/products/212/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đơn inox hình cua cho chó mèo',
        'description' => 'Bát đơn inox hình cua mang kiểu dáng đáng yêu, dễ thương kích thích chó mèo ăn uống. Phần thành bát cao, lòng bát sâu đựng nhiều thức ăn, chống vương vãi.',
        'price' => 42000,
        'image' => 'gs://new_petshop_bucket/products/213/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đơn chống kiến bằng inox đựng thức ăn chó mèo',
        'description' => 'Bát đơn chống kiến bằng inoxx có phần rãnh ngoài đựng nước chống kiến, sâu bọ tiếp xúc vào thức ăn, đảm bảo an toàn cho thú cưng.',
        'price' => 48000,
        'image' => 'gs://new_petshop_bucket/products/214/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi Inox hình ếch dễ thương',
        'description' => 'Bát đôi Inox hình ếch dễ thương, bắt mắt cùng phần lòng bát bằng chất liệu inox có độ bền cơ học cao siêu bền, được thiết kế 2 ngăn riêng biệt đựng thức ăn.',
        'price' => 54000,
        'image' => 'gs://new_petshop_bucket/products/215/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi trong suốt dành cho chó mèo, có ngăn nước tự động',
        'description' => 'Bát đôi trong suốt dành cho chó mèo có tới hai ngăn đựng thức ăn và ngăn nước tự động giúp các bé luôn có nước sạch mọi lúc.',
        'price' => 93000,
        'image' => 'gs://new_petshop_bucket/products/216/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi có bình đựng nước hình trái dâu cho mèo',
        'description' => 'Bát đôi có bình đựng nước hình trái dâu có thiết kế thông minh với phần đựng thức ăn được đẩy lên cao, giúp các bé không phải cúi sâu mỗi bữa ăn.',
        'price' => 77000,
        'image' => 'gs://new_petshop_bucket/products/217/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi chó mèo hình tàu vũ trụ, chất liệu nhựa cao cấp',
        'description' => 'Bát đôi chó mèo hình tàu vũ trụ là một trong những sản phẩm bán chạy nhất tại PetHouse. Sản phẩm đáp ứng đầy đủ cả về công năng và mặt thẩm mỹ.',
        'price' => 42000,
        'image' => 'gs://new_petshop_bucket/products/218/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát ăn chó mèo hình trái đào chất liệu nhựa cao cấp',
        'description' => 'Bát ăn chó mèo hình trái đào sẽ đáp ứng được yêu cầu thẩm mĩ về phụ kiện thú cưng của mọi người, đem đến những bữa ăn ngon miệng cho thú cưng',
        'price' => 28000,
        'image' => 'gs://new_petshop_bucket/products/219/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát ăn dặm hình xương cá dành cho chó mèo',
        'description' => 'Bát ăn dặm hình xương cá là giải pháp tuyệt vời cho chó mèo trong thời kỳ chuyển khẩu phần ăn từ bú sữa mẹ sang ăn đồ cứng, hạn chế bị hóc.',
        'price' => 54000,
        'image' => 'gs://new_petshop_bucket/products/220/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Chén ăn dặm hình cánh hoa loại nhỏ',
        'description' => 'Với chén ăn dặm hình cánh hoa, bạn có thể dễ dàng giúp chó mèo làm quen với chế độ thức ăn mới mà không lo các bé bị nghẹn.',
        'price' => 28000,
        'image' => 'gs://new_petshop_bucket/products/221/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát ăn chó mèo hình lục giác nhiều màu, chất liệu nhựa cao cấp',
        'description' => 'Bát ăn chó mèo hình lục giác với thiết kế lòng bát sâu ngăn thức ăn vương vãi, nhiều kích thước đa dạng, làm từ chất liệu nhựa cao cấp an toàn với thú cưng.',
        'price' => 12000,
        'image' => 'gs://new_petshop_bucket/products/222/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đơn hình bắp đựng thức ăn nước uống chó mèo',
        'description' => 'Bát đơn hình bắp ngô với kiểu dáng dễ thương, đẹp mắt cùng chất liệu cao cấp siêu bền chắc chắn sẽ làm hài lòng người nuôi.',
        'price' => 28000,
        'image' => 'gs://new_petshop_bucket/products/223/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi mèo inox có ngăn đổ nước tự động',
        'description' => 'Bát đôi hình mèo inox là một trong những sản phẩm có thiết kế thông minh và đẹp mắt. Chất liệu làm từ inox cao cấp không phải lo sợ bát bị va đập, xước xát.',
        'price' => 119000,
        'image' => 'gs://new_petshop_bucket/products/224/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi tròn nhựa có ngăn đổ nước tự động',
        'description' => 'Bát đôi tròn nhựa kiểu dáng hình xương đem đến những tiện lợi trong việc chăm sóc nuôi dưỡng thú cưng trong việc ăn uống.',
        'price' => 34000,
        'image' => 'gs://new_petshop_bucket/products/225/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi bầu dục đựng thức ăn và nước uống chó mèo',
        'description' => 'Bát đôi bầu dục là sự lựa chọn hoàn hảo trong việc chăm sóc thú cưng trong những bữa ăn hàng ngày với kểu dáng màu sắc đẹp mắt và chất liệu cao cấp.',
        'price' => 25000,
        'image' => 'gs://new_petshop_bucket/products/226/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi hình bí ngô đựng thức ăn chó mèo',
        'description' => 'Bát đôi hình bí ngô với tạo dáng dễ thương, đẹp mắt đồng thời có ngăn đổ nước tự động sẽ giúp việc chăm sóc thú cưng trở nên dễ dàng.',
        'price' => 30000,
        'image' => 'gs://new_petshop_bucket/products/227/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi nhựa hình thang',
        'description' => 'Bát đôi nhựa hình thang với thiết kế hai ngăn riêng biệt thông minh, thuận tiện giúp thú cưng nhà bạn có thể dễ dàng trong việc ăn uống hàng ngày.',
        'price' => 23000,
        'image' => 'gs://new_petshop_bucket/products/228/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi hình gấu có ngăn bơm nước tự động',
        'description' => 'Bát đôi hình gấu có gắn bình nước tự động với thiết kế 2 ngăn thông minh, giúp thú cưng có thể thoải mái trong việc ăn uống hàng ngày.',
        'price' => 45000,
        'image' => 'gs://new_petshop_bucket/products/229/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi và bình nước tự động thiết kế thông minh',
        'description' => 'Bát đôi và bình nước tự động có thiết kế vô cùng thông minh, tiện dụng. Ngăn nước uống có cơ chế tự làm đầy, giúp các bé chó mèo luôn có đủ nước sạch.',
        'price' => 65000,
        'image' => 'gs://new_petshop_bucket/products/230/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát ăn cho mèo bằng nhựa thiết kế dễ thương, nhiều màu sắc',
        'description' => 'Bát ăn cho mèo giá rẻ nhưng lại có chất lượng cực kỳ tuyệt vời. Thiết kế bát cực kỳ bắt mắt với nhiều hình miu miu đáng yêu và màu sắc đa dạng.',
        'price' => 40000,
        'image' => 'gs://new_petshop_bucket/products/231/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát gắn chuồng cho chó mèo tiện lợi, dễ vệ sinh',
        'description' => 'Bát gắn chuồng cho chó mèo với thiết kế thông minh, có thể dùng với tất cả các loại chuồng phổ biến, dễ dàng vệ sinh, chất liệu an toàn với thú nuôi.',
        'price' => 60000,
        'image' => 'gs://new_petshop_bucket/products/232/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát ăn hình vuông cho chó mèo chất liệu nhựa - inox',
        'description' => 'Bát ăn hình vuông cho chó mèo sở hữu thiết kế thông minh, thẩm mỹ và có độ an toàn cao, được chia hai lớp nhựa và inox, có thể dễ dàng tháo ra vệ sinh.',
        'price' => 75000,
        'image' => 'gs://new_petshop_bucket/products/233/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát ăn cao cấp cho chó mèo nhiều hình ngộ nghĩnh',
        'description' => 'Bát ăn cao cấp cho chó mèo là vật dụng cực kỳ cần thiết với các sen. Sản phẩm được chế tác từ chất liệu Inox chống gỉ cao cấp, dễ vệ sinh đảm bảo an toàn cho thú cưng. Khác với các loại bát nhựa rẻ tiền, bát Inox cực kỳ chắc chắn, có khả năng chống va đập tuyệt vời.',
        'price' => 105000,
        'image' => 'gs://new_petshop_bucket/products/234/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát đôi cao cấp cho chó mèo bằng chất liệu Inox chống gỉ',
        'description' => 'Bát đôi cao cấp cho chó mèo dùng để đựng cả thức ăn và nước uống, nhiều hình thù ngộ nghĩnh đa dạng, chất liệu inox chống gỉ dễ vệ sinh.',
        'price' => 117000,
        'image' => 'gs://new_petshop_bucket/products/235/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát nhựa tròn đựng thức ăn chó mèo đường kính 20cm',
        'description' => 'Bát nhựa tròn đựng thức ăn chó mèo với chất liệu nhựa tổng hợp siêu nhẹ, dễ dàng di chuyển và sử dụng. Sản phẩm cao cấp nhưng giá thành lại cực kỳ hợp lí.',
        'price' => 34000,
        'image' => 'gs://new_petshop_bucket/products/236/',
        'status' => true,
        'product_category_id' => 11,
      ],
      [
        'name' => 'Bát Inox cho chó mèo dễ thương, đa dạng kiểu dáng',
        'description' => 'Bát Inox cho chó mèo với chất liệu chống gỉ, chống va đập tốt, an toàn tuyệt đối khi sử dụng. Sản phẩm đa dạng về kiểu dáng, nhiều hình kute dễ thương.',
        'price' => 120000,
        'image' => 'gs://new_petshop_bucket/products/237/',
        'status' => true,
        'product_category_id' => 11,
      ],
    ];

    $dog_grooming_brush_products = [
      [
        'name' => 'Găng chải lông chó mèo',
        'description' => 'Găng chải lông hỗ trợ đắc lực cho người nuôi trong vấn đề vệ sinh lông rụng, làm mượt lông cho thú cưng hàng ngày, chất liệu vải thô thoáng khí chống mùi',
        'price' => 23000,
        'image' => 'gs://new_petshop_bucket/products/238/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Găng tay chải lông có hộp',
        'description' => 'Găng tay chải lông Pet Gloves hỗ trợ người nuôi hiệu quả trong việc làm mượt lông, chải lông và vệ sinh massage cho thú cưng khi tắm rửa.',
        'price' => 28000,
        'image' => 'gs://new_petshop_bucket/products/239/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Găng tay tắm nhựa dẻo trong suốt',
        'description' => 'Găng tay tắm nhựa dẻo trong suốt cao cấp không gây độc hại, không làm đau thú cưng, có gắn thêm phần gai nhỏ giúp masage, làm sạch cơ thể chó mèo',
        'price' => 12000,
        'image' => 'gs://new_petshop_bucket/products/240/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Bàn chải tắm silicon tạo bọt',
        'description' => 'Bàn chải tắm silicon tạo bọt mang kiểu dáng nhỏ gọn, thiết kế tinh tế thông minh. Sản phẩm giúp làm sạch và massage cơ thể cho thú cưng khi tắm rửa vệ sinh.',
        'price' => 34000,
        'image' => 'gs://new_petshop_bucket/products/241/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược trắng chấm bi',
        'description' => 'Lược trắng chấm bi với chất liệu làm bằng nhựa dẻo an toàn, thân thiện với sức khoẻ thú cưng. Đầu lược được làm bằng sợi kẽm chắc chắn, chống han gỉ tốt.',
        'price' => 25000,
        'image' => 'gs://new_petshop_bucket/products/242/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược chải lông gỡ rối cho mèo cao cấp',
        'description' => 'Lược chải lông gỡ rối cho mèo cao cấp với kiểu dáng bắt mắt, gọn nhẹ phần tay cầm chắc chắn không sợ gây mỏi, dầu gai lược được làm bằng inox siêu bền',
        'price' => 64000,
        'image' => 'gs://new_petshop_bucket/products/243/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược chải tròn',
        'description' => 'Lược chải tròn nhỏ gọn với đầu lược làm bằng inox chống han gỉ, có độ bền cơ học cao giúp làm sạch lông rụng trên da và cơ thể chó mèo',
        'price' => 45000,
        'image' => 'gs://new_petshop_bucket/products/244/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược chải 2 mặt',
        'description' => 'Lược chải 2 mặt tiện lợi với một mặt làm mượt lông, mặt còn lại giúp loại bỏ phần lông rụng xơ rối, hỗ trợ đắc lực chăm sóc và vệ sinh lông da cho thú cưng.',
        'price' => 38000,
        'image' => 'gs://new_petshop_bucket/products/245/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược bấm Pakeway T10',
        'description' => 'Lược bấm Pakeway T10 xuất xứ từ thương hiệu nội địa Trung cao cấp, giúp người nuôi xoa tan đi nỗi lo về vấn đề vệ sinh lông rụng trên người chó mèo.',
        'price' => 112000,
        'image' => 'gs://new_petshop_bucket/products/246/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược chải Peto cho chó cỡ lớn, thiết kế thông minh, chắc chắn',
        'description' => 'Lược chải Peto cho chó cỡ lớn và lông dày như Alaska, Golden, Husky, Samoyed, được làm từ inox chống gỉ, đảm bảo an toàn tối đa cho chó.',
        'price' => 75000,
        'image' => 'gs://new_petshop_bucket/products/247/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược chải chấy rận Pet Comb cho chó mèo',
        'description' => 'Lược chải chấy rận Pet Comb sẽ giúp quét sạch những ký sinh bám trên lông và da các bé thú cưng, giúp hạn chế ngứa ngáy và ngăn các mầm bệnh lây cho người.',
        'price' => 29000,
        'image' => 'gs://new_petshop_bucket/products/248/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược chải lông Peto dành cho chó mèo full box',
        'description' => 'Lược chải lông chó mèo full box của thương hiệu Peto. Hàng chính hãng siêu đẹp, thiết kế chắc chắn, dùng bao năm chưa thấy hỏng.',
        'price' => 96000,
        'image' => 'gs://new_petshop_bucket/products/249/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược chải lông Taotaopet dành cho chó mèo loại to',
        'description' => 'Lược chải lông chó mèo loại to Taotaopet với kích thước lớn, kết cấu chắc chắn phù hợp với các dòng chó lớn nhiều lông như Golden Retriever, Husky, Alaska.',
        'price' => 72000,
        'image' => 'gs://new_petshop_bucket/products/250/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược chải lông PetGrooming cho chó mèo có nút ấn loại bỏ lông rụng',
        'description' => 'Lược chải lông chó mèo PetGrooming với nút ấn giúp loại bỏ lông rụng tiện lợi. Giờ đây chúng ta không còn phải đối mặt với cảnh lông chó mèo rụng đầy nhà nữa.',
        'price' => 39000,
        'image' => 'gs://new_petshop_bucket/products/251/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược chải lông chó mèo có nút ấn giúp loại bỏ lông dễ dàng',
        'description' => 'Lược chải lông chó mèo có nút ấn giúp loại bỏ lông dễ dàng, bạn sẽ không còn phải dùng tay để gỡ lông khỏi răng lược một cách vất vả như trước.',
        'price' => 49000,
        'image' => 'gs://new_petshop_bucket/products/252/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược chải lông Pet Grooming dành cho chó mèo (tặng kèm lược nhỏ)',
        'description' => 'Lược chải lông chó mèo Pet Grooming giúp vệ sinh lông rụng một cách dễ dàng, vệ sinh. Sản phẩm có tặng kèm lược nhỏ đi kèm.',
        'price' => 38000,
        'image' => 'gs://new_petshop_bucket/products/253/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Lược Key Pet Comb chuyên dụng chải lông chó mèo hàng fullbox',
        'description' => 'Lược chải lông chó mèo Key Pet Comb với nút bấm tiện dụng, dễ dàng vệ sinh lông chó mèo một cách nhanh chóng. Có video hướng dẫn sử dụng.',
        'price' => 59000,
        'image' => 'gs://new_petshop_bucket/products/254/',
        'status' => true,
        'product_category_id' => 12,
      ],
      [
        'name' => 'Tông đơ chó mèo Pet Electric - Fullbox 4 lưỡi cạo',
        'description' => 'Tông đơ cạo cắt lông chó mèo Pet Electric thiết kế nhỏ gọn, an toàn, dễ sử dụng, có nhiều nấc để phù hợp với độ dài lông chó mèo.',
        'price' => 159000,
        'image' => 'gs://new_petshop_bucket/products/255/',
        'status' => true,
        'product_category_id' => 12,
      ],
    ];

    $dog_shampoo_products = [
      [
        'name' => 'Dầu tắm trị nấm cho chó mèo Fungamyl 200ml',
        'description' => 'Dầu tắm Fungamyl 200ml là dòng sản phẩm giúp ngăn ngừa bệnh ve rận, kí sinh cùng các bệnh tróc vảy, rụng lông do vi khuẩn nấm gây nên.',
        'price' => 186000,
        'image' => 'gs://new_petshop_bucket/products/256/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Tinh dầu dưỡng lông SH Relax Pet Essential 80ml',
        'description' => 'Tinh dầu dưỡng lông SH Relax Pet Essential là sự lựa chọn hoàn hảo khi thú cưng gặp tình trạng lông xơ rối, dễ gãy.',
        'price' => 134000,
        'image' => 'gs://new_petshop_bucket/products/257/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Phấn khử mùi Fay Puppy tắm khô cho chó con',
        'description' => 'Phấn khử mùi Fay Puppy là dòng bột tắm khô với công dụng làm sạch bụi bẩn trên da và lông cún cưng vô cùng hiệu quả, phù hợp cho mùa đông.',
        'price' => 51000,
        'image' => 'gs://new_petshop_bucket/products/258/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Bột tắm khô Bio Silky 200g',
        'description' => 'Bột tắm khô Bio Silky với công dụng làm sạch sâu các vết bẩn trên cơ thể cún cưng, công thức tạo bọt giúp người nuôi có thể dễ dàng vệ sinh cho thú cưng.',
        'price' => 115000,
        'image' => 'gs://new_petshop_bucket/products/259/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Phấn tắm khô Hello Boss dành cho chó mèo',
        'description' => 'Phấn tắm khô Hello Boss là sự lựa chọn hoàn hảo giúp đánh tan bụi bẩn, mùi hôi trên cơ thể chó mèo, thích hợp cho các boss nhỏ lười tắm rửa, da nhạy cảm.',
        'price' => 64000,
        'image' => 'gs://new_petshop_bucket/products/260/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Xịt tắm khô chó mèo Vemedim Smooth and Sweet 100ml',
        'description' => 'Xịt tắm khô chó mèo Vemedim Smooth and Sweet với công thức nano bạc hà thơm mát giúp làm sạch sâu da và cơ thể cún cưng, đánh tan mùi hôi khó chịu.',
        'price' => 86000,
        'image' => 'gs://new_petshop_bucket/products/261/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Phấn tắm khô Shu Nai Pet hương hoa hồng, bạc hà, chanh',
        'description' => 'Phấn tắm khô Shu Nai Pet giúp loại bỏ mùi hôi, chống viêm nhiễm, nấm mốc ve rận hiệu quả, giúp người nuôi vệ sinh và chăm sóc cơ thể thú cưng dễ dàng.',
        'price' => 53000,
        'image' => 'gs://new_petshop_bucket/products/262/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Bột tắm khô Magic hương đu đủ cho chó mèo 200g',
        'description' => 'Bột tắm khô Magic hương đu đủ là giải pháp hoàn hảo cho mùa đông giá lạnh ở nước ta, giúp vệ sinh thú cưng dễ dàng mà không cần sử dụng nước.',
        'price' => 83000,
        'image' => 'gs://new_petshop_bucket/products/263/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm khô SOS cho chó 500ml chai màu vàng',
        'description' => 'Sữa tắm khô SOS cho chó là sự lựa chọn hoàn hảo cho mùa đông giá lạnh. Bên cạnh đó, sản phẩm cũng rất phù hợp với những bé cún ngại nước.',
        'price' => 102000,
        'image' => 'gs://new_petshop_bucket/products/264/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Dầu xả King Pet mùi hương thảo cho chó mèo 250ml',
        'description' => 'Dầu xả King Pet mùi hương thảo nhẹ nhàng sẽ đem đến sự thoải mái tối đa cho thú cưng. Chỉ sau vài lần dùng, bộ lông của các bé sẽ bóng mượt như đi Spa.',
        'price' => 137000,
        'image' => 'gs://new_petshop_bucket/products/265/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm King Pet cho chó mùi hương thảo 250ml',
        'description' => 'Sữa tắm King Pet cho chó có thành phần tinh chất lá hương thảo, đem đến mùi thơm nhẹ nhàng và dễ chịu, có khả năng làm sạch sâu và cực kỳ hiệu quả.',
        'price' => 113000,
        'image' => 'gs://new_petshop_bucket/products/266/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm Spirit cho chó mèo nắp gỗ lưu hương bền lâu',
        'description' => 'Sữa tắm Spirit cho chó mèo có thiết kế rất ấn tượng với nắp gỗ tự nhiên là dòng sản phẩm cao cấp với hương thơm bền lâu, cuốn hút.',
        'price' => 102000,
        'image' => 'gs://new_petshop_bucket/products/267/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo Fruits Perfume 250ml',
        'description' => 'Sữa tắm chó mèo Fruits Perfume là dòng sản phẩm chăm sóc lông và da với thành phần thiên nhiên an toàn, nhiều dưỡng chất phù hợp cho mọi giống chó mèo.',
        'price' => 57000,
        'image' => 'gs://new_petshop_bucket/products/268/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo 6Series (1K-6K)',
        'description' => 'Sữa tắm chó mèo 6Series tích hợp nhiều công dụng tuyệt vời, phát huy tác dụng ngay trong lần đầu sử dụng, có công thức lưu hương làm mềm mượt hiệu quả.',
        'price' => 120000,
        'image' => 'gs://new_petshop_bucket/products/269/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo Endi Essential chuyên dụng cho từng dòng thú cưng',
        'description' => 'Sữa tắm chó mèo Endi Essential được chia thành nhiều dòng sản phẩm, phù hợp với từng nhu cầu, có ngọc trai mini giúp lưu hương và tạo độ phồng cho lông.',
        'price' => 102000,
        'image' => 'gs://new_petshop_bucket/products/270/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo Hello Boss 500ml',
        'description' => 'Sữa tắm chó mèo Hello Boss có tác dụng chăm sóc lông và da thú cưng cực kỳ hiệu quả, có hàm lượng Pectin cao vượt trội, giúp làm da lông thú cưng mềm mịn.',
        'price' => 90000,
        'image' => 'gs://new_petshop_bucket/products/271/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo Sentee 500ml',
        'description' => 'Sữa tắm chó mèo Sentee là dòng sản phẩm cao cấp với nhiều công dụng tuyệt vời cho thú cưng, mỗi loại sản phẩm sẽ phù hợp với từng trường hợp cụ thể.',
        'price' => 74000,
        'image' => 'gs://new_petshop_bucket/products/272/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm Diva dưỡng lông da cho chó, khử mùi hiệu quả 400ml',
        'description' => 'Sữa tắm Diva dưỡng lông da có rất nhiều dưỡng chất giúp da cún mềm mịn, lông bóng mượt, được thiết kế riêng cho giống chó Poodle với bộ lông đặc biệt.',
        'price' => 235000,
        'image' => 'gs://new_petshop_bucket/products/273/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm Diva cho chó giảm rụng lông, kích thích mọc lông mới 400ml',
        'description' => 'Sữa tắm Diva giảm rụng lông là dòng sản phẩm tuyệt vời cho các dòng chó lông dài như Husky hay Alaska giúp ngăn rụng lông và kích thích mọc lông mới.',
        'price' => 235000,
        'image' => 'gs://new_petshop_bucket/products/274/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo Bio Care diệt ve rận, khử mùi hôi hiệu quả 200ml',
        'description' => 'Sữa tắm chó mèo Bio Care là dòng sản phẩm ưu việt với nhiều công dụng như diệt ve rận, làm mượt lông, có thành phần từ thiên nhiên an toàn với thú cưng.',
        'price' => 89000,
        'image' => 'gs://new_petshop_bucket/products/275/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo Bio Jolie khử mùi hôi, lông siêu mượt 450ml',
        'description' => 'Sữa tắm chó mèo Bio Jolie cao cấp là sự lựa chọn hoàn hảo dành cho thú cứng nhà bạn, giúp dưỡng lông, làm mượt, đánh tan mùi hôi vô cùng hiệu quả.',
        'price' => 118000,
        'image' => 'gs://new_petshop_bucket/products/276/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo Bio-Skin đặc trị ve, rận, bọ chét, nấm da chai 450ml',
        'description' => 'Sữa tắm chó mèo Bio-Skin là dòng sản phẩm đa năng với 3 công dụng loại bỏ ve, rận, chữa trị nấm, viêm da và dưỡng da khỏe mạnh, lông bóng mượt.',
        'price' => 118000,
        'image' => 'gs://new_petshop_bucket/products/277/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo Bio Lovely 450ml',
        'description' => 'Sữa tắm chó mèo Bio Lovely sở hữu thành phần từ tự nhiên 100%, đảm bảo an toàn cho sức khỏe thú cưng, có khả năng khử mùi hiệu quả lên tới 7 ngày.',
        'price' => 123000,
        'image' => 'gs://new_petshop_bucket/products/278/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Xịt tắm khô Fay Groom for Dog 350ml',
        'description' => 'Xịt tắm khô Fay Groom for Dog là giải pháp hoàn hảo trong mùa lạnh. Không cần tiếp xúc với nước, các bé vẫn sẽ được tắm sạch sẽ, không còn mùi hôi khó chịu.',
        'price' => 79000,
        'image' => 'gs://new_petshop_bucket/products/279/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm cho chó Palma Act 300ml',
        'description' => 'Sữa tắm cho chó Palma Act là dòng sản phẩm chăm sóc da chuyên dụng với khả năng trị ve, bọ chét, đảm bảo an toàn với da người và vật nuôi.',
        'price' => 68000,
        'image' => 'gs://new_petshop_bucket/products/280/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm cho chó Fay 5 sao chai 300ml',
        'description' => 'Sữa tắm cho chó Fay 5 sao là dòng sản phẩm chăm sóc toàn diện cho cún cưng, giúp lông cún mềm mịn, làn da khỏe mạnh không bị nấm, viêm.',
        'price' => 94000,
        'image' => 'gs://new_petshop_bucket/products/281/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm cho chó Fay 4 sao chai 300ml',
        'description' => 'Sữa tắm cho chó Fay 4 sao là sự lựa chọn hoàn hảo đối với nhiều người nuôi chó. Đây là giải pháp hoàn hảo cho vấn đề ve rận trên cơ thể thú cưng.',
        'price' => 95000,
        'image' => 'gs://new_petshop_bucket/products/282/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo Fay Medicare chuyên trị rụng lông, viêm da',
        'description' => 'Sữa tắm chó mèo Fay Medicare là dòng thuốc đặc trị da liễu cho chó mèo. Sản phẩm giúp hạn chế đáng kể các tình trạng rụng lông, ngứa ngáy viêm da do ghẻ.',
        'price' => 99000,
        'image' => 'gs://new_petshop_bucket/products/283/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo Snappy Tom chai 500ml',
        'description' => 'Sữa tắm chó mèo Snappy Tom là sản phẩm vệ sinh được rất nhiều sen tin dùng, giúp làm sạch cơ thể, khử mùi hôi hiệu quả ngay trong những lần đầu sử dụng.',
        'price' => 108000,
        'image' => 'gs://new_petshop_bucket/products/284/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo ZO8 làm sạch hiệu quả, giữ mùi lâu xuất xứ Thái Lan',
        'description' => 'Sữa tắm chó mèo ZO8 là dòng sản phẩm cao cấp từ Thái Lan với thiết kế sang trọng, khả năng làm sạch hiệu quả cùng mùi hương thơm mát, dễ chịu.',
        'price' => 62000,
        'image' => 'gs://new_petshop_bucket/products/285/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm Bioline cho chó mèo chai 250ml',
        'description' => 'Sữa tắm Bioline có nhiều loại phù hợp với mọi nhu cầu của chủ và thú cưng, dùng được cho cả chó mèo con, giúp ngăn rụng lông, chữa trị các bệnh ngoài da.',
        'price' => 58000,
        'image' => 'gs://new_petshop_bucket/products/286/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm Fay cho chó chai 800ml dưỡng da, trị bệnh ngoài da',
        'description' => 'Sữa tắm Fay cho chó giúp dưỡng da, lông và đem đến mùi hương thơm nhẹ nhàng dễ chịu. Ngoài ra, sản phẩm còn giúp chữa một số bệnh ngoài da như nấm, vảy nến',
        'price' => 168000,
        'image' => 'gs://new_petshop_bucket/products/287/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm Plush Puppy cho chó hương nhân sâm chai 250ml',
        'description' => 'Sữa tắm Plush Puppy với tinh chất nhân sâm sẽ giúp các bé có da và lông khỏe mạnh, có mùi hương thơm nhẹ nhàng dễ chịu. Sản phẩm được đóng chai 1l tiết kiệm',
        'price' => 420000,
        'image' => 'gs://new_petshop_bucket/products/288/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm Trixie cho chó mèo ngăn ngừa rụng lông 250ml',
        'description' => 'Sữa tắm Trixie cho chó mèo với nhiều dòng sản phẩm đa dạng sẽ giúp các bé cưng luôn thơm tho, ngăn ngừa rụng lông cực kỳ hiệu quả.',
        'price' => 89000,
        'image' => 'gs://new_petshop_bucket/products/289/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm YÚ cho chó mèo hương hoa tự nhiên 400ml',
        'description' => 'Sữa tắm YÚ cho chó mèo có nhiều lựa chọn hương hoa tự nhiên và thảo mộc phương đông, giúp da và lông thú mềm mượt, khỏe mạnh, ngừa mùi hôi.',
        'price' => 370000,
        'image' => 'gs://new_petshop_bucket/products/290/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo SOS chính hãng nhiều màu, đa chức năng',
        'description' => 'Sữa tắm chó mèo SOS với nhiều dòng đa dạng, giúp lông mềm mượt, giữ độ ẩm cho da, kháng khuẩn hiệu quả, phòng chống các bệnh về da.',
        'price' => 119000,
        'image' => 'gs://new_petshop_bucket/products/291/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm khử mùi Plotoon DeoDor cho chó cảnh, loại mùi hôi hiệu quả',
        'description' => 'Sữa tắm khử mùi Plotoon DeoDor có chứa Free Sulfate chuyên khử mùi, làm sạch hiệu quả cùng tinh chất bạc hà và trà xanh đem tới cảm giác thoải mái.',
        'price' => 90000,
        'image' => 'gs://new_petshop_bucket/products/292/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm nước hoa chó mèo Showqueen lưu hương 7-10 ngày',
        'description' => 'Sữa tắm nước hoa chó mèo Showqueen là dòng sản phẩm cao cấp dành cho thú cưng, giúp cho các bé có bộ lông mềm mịn, suôn mượt và mùi thơm dễ chịu.',
        'price' => 180000,
        'image' => 'gs://new_petshop_bucket/products/293/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm chó mèo Joyce&Doll hương hoa giữ mùi 7 ngày',
        'description' => 'Sữa tắm chó mèo Joyce&Doll có khả năng tạo hương thơm nước hoa đặc biệ, có khả năng lưu hương tới 7 ngày, khử mùi hôi cực kỳ hiệu quả.',
        'price' => 225000,
        'image' => 'gs://new_petshop_bucket/products/294/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm nước hoa chó mèo M Royal Care Smooth - 500ml',
        'description' => 'Sữa tắm nước hoa chó mèo M Royal Care giúp làm mượt, chăm sóc dưỡng lông chó mèo hiệu quả, giúp hạn chế mùi hôi trong thời gian dài.',
        'price' => 96000,
        'image' => 'gs://new_petshop_bucket/products/295/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm diệt nấm ghẻ Hantox cho chó mèo, phòng ngừa ve rận',
        'description' => 'Sữa tắm diệt nấm ghẻ Hantox đặc biệt hiệu quả trong việc trị ve chó, diệt bọ chét, rận, ghẻ, giúp làm sạch cơ thể thú cưng.',
        'price' => 65000,
        'image' => 'gs://new_petshop_bucket/products/296/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm trị ghẻ và nấm Bio Derma cho chó mèo cực kỳ hiệu quả',
        'description' => 'Sữa tắm trị ghẻ và nấm Bio Derma là kẻ thù của các bệnh về da hay gặp ở cún cưng, hiệu quả ngay lập tức, giúp phục hồi, vệ sinh lông và da của chó cảnh.',
        'price' => 100000,
        'image' => 'gs://new_petshop_bucket/products/297/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Sữa tắm tinh dầu Olive Essence cho chó mèo',
        'description' => 'Sữa tắm tinh dầu Olive Essence cho chó mèo chứa thành phần chưa tinh dầu Oliu tự nhiên, giúp lông chó mèo mềm mượt, cơ thể sạch sẽ, thơm tho.',
        'price' => 60000,
        'image' => 'gs://new_petshop_bucket/products/298/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Tinh dầu dưỡng lông chó mèo Showqueen mềm mượt, bóng khỏe',
        'description' => 'Tinh dầu dưỡng lông chó mèo Showqueen là giải pháp tối ưu giúp đem lại một bộ lông mềm mượt, thơm tho, hiện được rất nhiều người tin dùng.',
        'price' => 190000,
        'image' => 'gs://new_petshop_bucket/products/299/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Bột tắm khô chó mèo Magic 100g tiện dụng, làm sạch dễ dàng',
        'description' => 'Bột tắm khô chó mèo Magic là sản phẩm nổi tiếng uy tín và chất lượng, giúp thú cưng có một cơ thể sạch sẽ, thơm tho, phù hợp với những bé chó mèo sợ nước.',
        'price' => 68000,
        'image' => 'gs://new_petshop_bucket/products/300/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Bột tắm khô chó mèo Bioline 100g',
        'description' => 'Bột tắm khô chó mèo Bioline 100g giúp cơ thể thú cưng luôn được sạch sẽ, thơm tho mà không cần dùng nước, vô cùng tiện lợi và dễ dàng sử dụng.',
        'price' => 65000,
        'image' => 'gs://new_petshop_bucket/products/301/',
        'status' => true,
        'product_category_id' => 13,
      ],
      [
        'name' => 'Xịt diệt khuẩn khử mùi hôi cho chó mèo Bioline',
        'description' => 'Xịt diệt khuẩn khử mùi hôi cho chó mèo Bioline với công dụng khử mùi hôi, diệt khuẩn, diệt mầm bệnh, đảm bảo không gian sạch sẽ, bảo vệ sức khỏe.',
        'price' => 118000,
        'image' => 'gs://new_petshop_bucket/products/302/',
        'status' => true,
        'product_category_id' => 13,
      ],
    ];

    $dog_towel_products = [
      [
        'name' => 'Khăn tắm chó mèo Clean Cham size to có hộp',
        'description' => 'Khăn tắm chó mèo Clean Cham được làm từ sợi tổng hợp siêu thấm hút, cực kỳ mềm mại và an toàn với làn da thú cưng, phù hợp với các bé lông dài.',
        'price' => 50000,
        'image' => 'gs://new_petshop_bucket/products/303/',
        'status' => true,
        'product_category_id' => 14,
      ],
      [
        'name' => 'Kem đánh răng cho chó Bioline kèm bàn chải',
        'description' => 'Kem đánh răng cho chó Bioline được chế tạo với thành phần tự nhiên, đảm bảo an toàn tuyệt đối với thú cưng, đánh tan mùi hôi, loại bỏ mảng bám.',
        'price' => 78000,
        'image' => 'gs://new_petshop_bucket/products/304/',
        'status' => true,
        'product_category_id' => 14,
      ],
    ];

    $dog_perfumes_products = [
      [
        'name' => 'Nước hoa Luxury Seduisant 250ml cho chó mèo',
        'description' => 'Nước hoa Luxury Seduisant là dòng nước hoa thương hiệu cao cấp chuyên dụng dành cho thú cưng chắc chắn sẽ làm hài lòng người nuôi và các boss nhỏ.',
        'price' => 203000,
        'image' => 'gs://new_petshop_bucket/products/305/',
        'status' => true,
        'product_category_id' => 15,
      ],
      [
        'name' => 'Nước hoa chó mèo SH cao cấp khử mùi hiệu quả',
        'description' => 'Nước hoa chó mèo SH là dòng nước hoa thương hiệu cao cấp với thành phần hương liệu hoàn toàn từ thiên nhiên, mang lại những mùi hương thơm mát.',
        'price' => 197000,
        'image' => 'gs://new_petshop_bucket/products/306/',
        'status' => true,
        'product_category_id' => 15,
      ],
      [
        'name' => 'Nước hoa chó mèo Bioline 207ml',
        'description' => 'Nước hoa chó mèo Bioline với công thức tạo hương nhẹ nhàng dễ chịu, giúp đem đến những trải nghiệm thoải mái, thư giãn đến với thú cưng.',
        'price' => 79000,
        'image' => 'gs://new_petshop_bucket/products/307/',
        'status' => true,
        'product_category_id' => 15,
      ],
      [
        'name' => 'Nước hoa Fay Pleasure cho chó mèo',
        'description' => 'Nước hoa Fay Pleasure với thành phần từ thiên nhiên với tinh dầu tạo mùi hương thơm mát,loại bỏ đi mùi hôi khó chịu bám trên cơ thể thú cưng.',
        'price' => 72000,
        'image' => 'gs://new_petshop_bucket/products/308/',
        'status' => true,
        'product_category_id' => 15,
      ],
      [
        'name' => 'Nước hoa Fay En-Rosely cho chó mèo 10ml',
        'description' => 'Nước hoa Fay En-Rosely sẽ giúp các bé thú cưng có mùi hương thơm nhẹ nhàng dễ chịu, có chứa công thức khử mùi Zinc Diricinoleate hiệu quả rõ rệt.',
        'price' => 72000,
        'image' => 'gs://new_petshop_bucket/products/309/',
        'status' => true,
        'product_category_id' => 15,
      ],
      [
        'name' => 'Nước hoa King Pet mùi hương thảo 100ml',
        'description' => 'Ngoài dòng sản phẩm sữa tắm cùng kem xả đang hot trên thị trường, chúng ta còn có nước hoa King Pet với mùi hương cuốn hút.',
        'price' => 125000,
        'image' => 'gs://new_petshop_bucket/products/310/',
        'status' => true,
        'product_category_id' => 15,
      ],
      [
        'name' => 'Nước hoa chó mèo Magic khử mùi hôi, không gây kích ứng',
        'description' => 'Nước hoa chó mèo Magic là dòng nước hoa chuyên trị mùi hôi hiệu quả ở thú nuôi. Nước hoa Magic đem lại mùi hương thoáng mát dễ chịu trên các bé cún và miu.',
        'price' => 59000,
        'image' => 'gs://new_petshop_bucket/products/311/',
        'status' => true,
        'product_category_id' => 15,
      ],
      [
        'name' => 'Nước hoa Freshy cho chó mèo lưu hương 7 ngày',
        'description' => 'Nước hoa Freshy cho chó mèo là dòng nước hoa nổi tiếng, được nhiều người yêu thích. Sản phẩm đem đến mùi mùi hương dịu nhẹ và đa dạng cho các boss.',
        'price' => 160000,
        'image' => 'gs://new_petshop_bucket/products/312/',
        'status' => true,
        'product_category_id' => 15,
      ],
    ];

    $dog_diapers_products = [
      [
        'name' => 'Tã lót vệ sinh',
        'description' => 'Tã lót vệ sinh dùng để lót khay vệ sinh, đáy chuồng chó mèo đảm bảo được vệ sinh nơi ở cho thú cưng. Sản phẩm có kích thước rộng rãi, khoá mùi tốt.',
        'price' => 120000,
        'image' => 'gs://new_petshop_bucket/products/313/',
        'status' => true,
        'product_category_id' => 16,
      ],
      [
        'name' => 'Bỉm quần đực Soft Pet - XS',
        'description' => 'Găng tay chải lông Pet Gloves hỗ trợ người nuôi hiệu quả trong việc làm mượt lông, chải lông và vệ sinh massage cho thú cưng khi tắm rửa.',
        'price' => 28000,
        'image' => 'gs://new_petshop_bucket/products/314/',
        'status' => true,
        'product_category_id' => 16,
      ],
      [
        'name' => 'Bỉm quần DOOG&CAAT',
        'description' => 'Bỉm quần DOOG&CAAT với tinh chất kháng khuẩn khử mùi hôi hiệu quả, đảm bảo được vấn đề vệ sinh khi nuôi thú cưng trong gia đình nhà bạn.',
        'price' => 76000,
        'image' => 'gs://new_petshop_bucket/products/315/',
        'status' => true,
        'product_category_id' => 16,
      ],
      [
        'name' => 'Tã Dono Pad For Pet',
        'description' => 'Tã Dono Pad for Pet được sản xuất theo công nghệ Nhật Bản có độ thấm hút cao lên đến 600ml - 800ml nước, khoá mùi hiệu quả, chống tràn ngăn ngừa vi khuẩn',
        'price' => 140000,
        'image' => 'gs://new_petshop_bucket/products/316/',
        'status' => true,
        'product_category_id' => 16,
      ],
      [
        'name' => 'Bỉm quần cái Soft Pet - XS',
        'description' => 'Bỉm quần cái Soft Pet với chất liệu mềm mịn, dịu nhẹ đem lại cảm giác thoải mái cho thú cưng khi sử dụng, có nhiều size kích thước đa dạng',
        'price' => 103000,
        'image' => 'gs://new_petshop_bucket/products/317/',
        'status' => true,
        'product_category_id' => 16,
      ],
      [
        'name' => 'Bỉm cho chó đực Senfee',
        'description' => 'Bỉm cho chó đực Senfee được sản xuất dành riêng cho các bé cún đực giúp người nuôi có thể giải quyết được các vấn đề về vệ sinh của thú cưng.',
        'price' => 115000,
        'image' => 'gs://new_petshop_bucket/products/318/',
        'status' => true,
        'product_category_id' => 16,
      ],
      [
        'name' => 'Tã than hoạt tính Dono size L',
        'description' => 'Tã than hoạt tính Dono với tinh chất cacbon hoạt tính có khả năng khử mùi nhanh chóng, thấm hút hiệu quả. Chất liệu tự nhiên an toàn với sức khoẻ thú cưng',
        'price' => 200000,
        'image' => 'gs://new_petshop_bucket/products/319/',
        'status' => true,
        'product_category_id' => 16,
      ],
      [
        'name' => 'Bỉm cái Tom Cat\'s',
        'description' => 'Bỉm cái Tom Cat\'s dùng cho thú cưng vào các giai đoạn nhạy cảm như động dục, bị bệnh đường ruột đi ngoài...',
        'price' => 107000,
        'image' => 'gs://new_petshop_bucket/products/320/',
        'status' => true,
        'product_category_id' => 16,
      ],
      [
        'name' => 'Bỉm đực Tom Cat\'s',
        'description' => 'Bỉm đực Tom Cat\'s được thiết kế đặc biệt bên trong chứa chất kháng khuẩn giúp duy trì vệ sinh, tạo mùi hương dễ chịu khi sử dụng.',
        'price' => 115000,
        'image' => 'gs://new_petshop_bucket/products/321/',
        'status' => true,
        'product_category_id' => 16,
      ],
      [
        'name' => 'Bỉm Dono cho chó mèo cái',
        'description' => 'Bỉm Dono cho chó mèo với thiết kế đặc biệt bên trong chứa chất kháng chuẩn, đảm bảo được vệ sinh trong nhà được sạch sẽ trong vấn đề thú cưng phóng uế',
        'price' => 97000,
        'image' => 'gs://new_petshop_bucket/products/322/',
        'status' => true,
        'product_category_id' => 16,
      ],
      [
        'name' => 'Túi vệ sinh hình khúc xương',
        'description' => 'Hộp túi vệ sinh hình khúc xương là dụng cụ tiện lợi hỗ trợ người nuôi trong việc chăm sóc thú cưng, đựng giấy nilon đựng chất thải của thú cưng thuận tiện.',
        'price' => 10000,
        'image' => 'gs://new_petshop_bucket/products/323/',
        'status' => true,
        'product_category_id' => 16,
      ],
      [
        'name' => 'Kẹp dọn phân chó kiểu dáng bò sữa',
        'description' => 'Kẹp dọn phân chó kiểu dáng bò sữa giúp dọn dẹp phần chất thải khi thú cưng nhà bạn phóng uế ra ngoài không đúng chỗ, đảm bảo vệ sinh khi dắt cún đi dạo',
        'price' => 28000,
        'image' => 'gs://new_petshop_bucket/products/324/',
        'status' => true,
        'product_category_id' => 16,
      ],
    ];

    $dog_tray_products = [
      [
        'name' => 'Khay vệ sinh hình chữ nhật cao thành 53*45*15cm',
        'description' => 'Khay hình chữ nhật thành lượn sóng với công dụng làm nơi vệ sinh cho thú cưng, giúp cún đi vệ sinh đúng chỗ, có đáy dạng lưới dễ dàng tháo lắp, vệ sinh.',
        'price' => 89000,
        'image' => 'gs://new_petshop_bucket/products/325/',
        'status' => true,
        'product_category_id' => 17,
      ],
      [
        'name' => 'Khay vệ sinh thành cao lượn sóng size S 38*38*12',
        'description' => 'Khay vệ sinh thành cao lượn sóng có thiết kế thông minh giúp hạn chế phân và nước tiểu bắn ra khi cún đi vệ sinh, được làm từ nhựa PP siêu bền.',
        'price' => 96000,
        'image' => 'gs://new_petshop_bucket/products/326/',
        'status' => true,
        'product_category_id' => 17,
      ],
      [
        'name' => 'Khay vệ sinh cho chó đực hình gấu 63*47*5.5',
        'description' => 'Khay vệ sinh cho chó đực hình gấu có thiết kế hết sức đáng yêu, dễ thương phù hợp với những người yêu thích sự cute, giúp cún đi vệ sinh đúng chỗ.',
        'price' => 235000,
        'image' => 'gs://new_petshop_bucket/products/327/',
        'status' => true,
        'product_category_id' => 17,
      ],
      [
        'name' => 'Khay vệ sinh cho chó đực vuông nhỏ 45*35*5cm',
        'description' => 'Khay vệ sinh cho chó đực vuông hỗ trợ hiệu quả cho người nuôi trong việc chăm sóc nuôi dưỡng thú cưng, giúp cún dễ dàng định vị, đi vệ sinh đúng nơi.',
        'price' => 128000,
        'image' => 'gs://new_petshop_bucket/products/328/',
        'status' => true,
        'product_category_id' => 17,
      ],
      [
        'name' => 'Khay vệ sinh Pakeway size S',
        'description' => 'Khay vệ sinh Pakeway size S có kích thước gọn gàng, phù hợp với các bé cún dưới 5kg, được làm từ chất liệu nhựa cao cấp đem lại cảm giác an tâm với sức khỏe thú cưng.',
        'price' => 218000,
        'image' => 'gs://new_petshop_bucket/products/329/',
        'status' => true,
        'product_category_id' => 17,
      ],
      [
        'name' => 'Chậu cát vệ sinh cho mèo hình chữ nhật 45*30*16cm',
        'description' => 'Chậu cát vệ sinh cho mèo hình chữ nhật 45*30*16cm có thiết kế sâu lòng đem lại cảm giác rộng rãi, thoải mái cho các boss nhỏ. Thành khay cao cũng sẽ giúp cát không bị vương vãi ra sàn.',
        'price' => 115000,
        'image' => 'gs://new_petshop_bucket/products/330/',
        'status' => true,
        'product_category_id' => 17,
      ],
      [
        'name' => 'Khay vệ sinh chữ nhật có bàn đạp 40*28*14cm',
        'description' => 'Khay vệ sinh chữ nhật có bàn đạp với nhiều lỗ hổng để cát có thể dễ dàng rơi xuống lòng khay, được làm từ nhựa cao cấp, dễ tháo lắp lau chùi.',
        'price' => 145000,
        'image' => 'gs://new_petshop_bucket/products/331/',
        'status' => true,
        'product_category_id' => 17,
      ],
      [
        'name' => 'Khay vệ sinh cho mèo 50*34*18cm',
        'description' => 'Khay vệ sinh cho mèo 50*34*18cm là một thiết kế khá đặc biệt và đẹp mắt, có thiết kến viền bằng nhựa kính trong suốt hiện đại, dễ dàng vệ sinh, lau chùi.',
        'price' => 175000,
        'image' => 'gs://new_petshop_bucket/products/332/',
        'status' => true,
        'product_category_id' => 17,
      ],
      [
        'name' => 'Khay vệ sinh tròn thành cao',
        'description' => 'Khay vệ sinh tròn thành cao với thiết kế đẹp mắt, nhỏ gọn giúp tiết kiệm diện tích sinh hoạt trong hộ gia đình',
        'price' => 120000,
        'image' => 'gs://new_petshop_bucket/products/333/',
        'status' => true,
        'product_category_id' => 17,
      ],
      [
        'name' => 'Khay vệ sinh F 50*36*18',
        'description' => 'Khay vệ sinh F 50*36*18 với thiết kế đẹp mắt, nhỏ gọn đem lại sự thoải mái, dễ chịu cho các boss nhỏ khi sử dụng, có nhiều màu sắc và cách phối.',
        'price' => 195000,
        'image' => 'gs://new_petshop_bucket/products/334/',
        'status' => true,
        'product_category_id' => 17,
      ],
    ];

    $dog_house_products = [
      [
        'name' => 'Chuồng cho chó mèo bằng nhựa',
        'description' => 'Thiết kế từ nhựa ABS chắc chắn có độ bền đẹp cao, thú cưng của bạn có thể tha hồ quậy phá bên trong nhà của chúng. Với không gian thoải mái cho bé, đặt được nhiều vật dụng chăm sóc. Có bánh bi ở dưới rất thuận tiện cho việc di chuyển, vệ sinh',
        'price' => 2000000,
        'image' => 'gs://new_petshop_bucket/products/335/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Quây cho chó bằng nhựa nan nhỏ',
        'description' => 'Là sản phẩm có tính an toàn, không gây độc hại tới sức khỏe cún cưng cũng như không ảnh hưởng tới môi trường. Dễ dàng tháo rời, lắp đặt bởi được làm từ các miếng khác nhau cùng với màu sắc thu hút là những ưu điểm nổi bật.',
        'price' => 1500000,
        'image' => 'gs://new_petshop_bucket/products/336/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Chuồng gỗ cho chó mèo 2 tầng',
        'description' => 'Sản phẩm xuất xứ từ Nhật Bản với chất liệu khung gỗ tự nhiên chắc chắn, hàng rào thép sơn tĩnh điện, độ bền cao chống rỉ sét và dễ dàng làm sạch. Thiết kế 2 tầng rộng rãi nhưng cũng rất tiện lợi để gập lại khi di chuyển. Khóa cửa chắc chắn và dễ dàng vệ sinh, làm sạch.',
        'price' => 4600000,
        'image' => 'gs://new_petshop_bucket/products/337/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Chuồng chó mèo bằng sắt sàn nhựa',
        'description' => 'Chuồng được vây quanh bởi sắt sơn tĩnh điện chống xước có độ chắc ổn cùng với nhựa PP. Chuồng có cấu trúc đơn giản dễ làm sạch và thiết kế bánh xe tiện lợi cho việc di chuyển. Sản phẩm có nhiều màu sắc và kích cỡ cho bạn lựa chọn.',
        'price' => 1350000,
        'image' => 'gs://new_petshop_bucket/products/338/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Chuồng vải bông cho chó',
        'description' => 'Chuồng được làm từ vải bông nên giữ ấm được cho cún những ngày đông lạnh. Với các khóa kéo dễ dàng vệ sinh cùng khả năng thuận tiện khi đem đi ra ngoài là những đặc điểm nổi bật của sản phẩm này. Sản phẩm này rất đa dạng mẫu mã và vô cùng bắt mắt',
        'price' => 500000,
        'image' => 'gs://new_petshop_bucket/products/339/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Chuồng nhựa cho chó cao cấp',
        'description' => 'Là chuồng làm từ nhựa dẻo đem tới độ bền tốt, thấm nước và dễ vệ sinh. Được thiết kế tự lắp nên rất đơn giản, có lỗ thông gió với tấm lưới tạo cảm giác thoáng mát, dễ chịu cho cún cưng. Với đa dạng màu sắc và tiện ích, đây đang là sản phẩm rất được ưa dùng',
        'price' => 1000000,
        'image' => 'gs://new_petshop_bucket/products/340/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Nhà gỗ ngoài trời cho chó mèo',
        'description' => 'Nhà gỗ cho chó mèo đang rất được yêu thích bởi độ thẩm mỹ cao cũng như tính bền chắc của nó. Được sử dụng và rất phổ biến hiện nay bởi phong cách sang trọng hiện đại phù hợp ngay với cả những căn hộ chung cư cũng như khả năng dễ lắp ráp, tháo dời, di chuyển.',
        'price' => 2000000,
        'image' => 'gs://new_petshop_bucket/products/341/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Chuồng thép 6 mảnh cho chó',
        'description' => 'Chuồng thép 6 mảnh cho chó với ưu điểm lớn nhất là khả năng tháo lắp dễ dàng, chỉnh đổi sang nhiều kích cỡ như rộng, vừa, hẹp để phù hợp, tạo không gian thoải mái, hợp lý nhất cho bé cún nhà bạn.',
        'price' => 700000,
        'image' => 'gs://new_petshop_bucket/products/342/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Chuồng chó sắt sơn tĩnh điện',
        'description' => 'Mẫu chuỗng với đa dạng mẫu mã và màu sắc, có nhiều kích thước cho các dòng chó khác nhau. Với tính chắc chắn, đồ bền cao cũng như những tiện ích mà loại chuồng này mang lại thì đây là sự lựa chọn hàng đầu cho các bạn nuôi chó.',
        'price' => 2000000,
        'image' => 'gs://new_petshop_bucket/products/343/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Chuồng sắt mạ kẽm cho chó mèo',
        'description' => 'Chuồng làm bằng hộp sắt mạ kẽm được tịn dùng bởi sự chắc chắn. Sản phẩm này có độ bền cao, sắt đã được mạ kẽm cũng rất bền lâu bị ăn mòn bởi thời tiết. Có khay vệ sinh và chỗ để bát thức ăn, nước cho thú cưng rất tiện lợi.',
        'price' => 2800000,
        'image' => 'gs://new_petshop_bucket/products/344/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Khay vệ sinh F 50*36*18',
        'description' => 'Khay vệ sinh F 50*36*18 với thiết kế đẹp mắt, nhỏ gọn đem lại sự thoải mái, dễ chịu cho các boss nhỏ khi sử dụng, có nhiều màu sắc và cách phối.',
        'price' => 1200000,
        'image' => 'gs://new_petshop_bucket/products/345/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Nhà chó bằng nhựa',
        'description' => 'Nhà chó bằng nhựa với chất liệu cao cấp chịu lực, chịu nắng gió. Đây là tổ ấm tuyệt vời dành cho cún cưng, tạo cảm giác thoải mái và an toàn.',
        'price' => 1250000,
        'image' => 'gs://new_petshop_bucket/products/346/',
        'status' => true,
        'product_category_id' => 18,
      ],
      [
        'name' => 'Lồng sơn tĩnh điện cao cấp, có thể gấp gọn',
        'description' => 'Lồng sơn tĩnh điện với thiết kế chắc chắn, chịu đựng tốt, có lớp sơn an toàn. Lồng có thể được gấp gọn để dễ dàng vệ sinh, vận chuyển.',
        'price' => 500000,
        'image' => 'gs://new_petshop_bucket/products/347/',
        'status' => true,
        'product_category_id' => 18,
      ],
    ];

    $dog_backpack_products = [
      [
        'name' => 'Balo vuông nhựa dẻo',
        'description' => 'Balo vuông nhựa dẻo được thiết kể với kiểu dáng bán cầu rộng rãi, thoáng mát dành cho thú cưng. Balo được làm bằng chất liệu chắc chắn, dày dặn',
        'price' => 234000,
        'image' => 'gs://new_petshop_bucket/products/348/',
        'status' => true,
        'product_category_id' => 19,
      ],
      [
        'name' => 'Địu vận chuyển',
        'description' => 'Địu với vải lưới thoáng khí giúp dễ dàng vận chuyển, mang thú cưng ra bên ngoài mà không gây khó chịu tới các boss nhỏ.',
        'price' => 100000,
        'image' => 'gs://new_petshop_bucket/products/349/',
        'status' => true,
        'product_category_id' => 19,
      ],
      [
        'name' => 'Túi kính hình hoạt hình nhiều màu',
        'description' => 'Túi kính hình hoạt hình nhiều màu với kiểu dáng thời trang, sang trọng. Kết cấu không gian bên trong túi rộng rãi, thoải mái',
        'price' => 280000,
        'image' => 'gs://new_petshop_bucket/products/350/',
        'status' => true,
        'product_category_id' => 19,
      ],
      [
        'name' => 'Balo kính xuất khẩu',
        'description' => 'Balo kính xuất khẩu dành cho thú cưng giúp cho mọi người có thể dễ dàng vận chuyển mang thú cưng đi chơi, ra ngoài cùng bản thân vô cùng tiện lợi',
        'price' => 270000,
        'image' => 'gs://new_petshop_bucket/products/351/',
        'status' => true,
        'product_category_id' => 19,
      ],
      [
        'name' => 'Balo cao cấp Pakeway có quạt',
        'description' => 'Balo cao cấp Pakeway có quạt mát giúp làm mát không khí bên trong giúp thú cưng thoải mái.',
        'price' => 1010000,
        'image' => 'gs://new_petshop_bucket/products/352/',
        'status' => true,
        'product_category_id' => 19,
      ],
    ];

    $dog_carrying_bag_products = [
      [
        'name' => 'Set túi lưới 3 size',
        'description' => 'Set túi lưới 3 size được thiết kế chắc chắn, bắt mắt có nhiều màu sắc cho người nuôi lựa chọn, có thiết kế bán kín với vải lưới giúp lưu thông không khí',
        'price' => 510000,
        'image' => 'gs://new_petshop_bucket/products/353/',
        'status' => true,
        'product_category_id' => 20,
      ],
      [
        'name' => 'Lồng hàng không 54*36*37 cm',
        'description' => 'Khi vận chuyển thú cưng trên một hành trình dài có thể bị xổng chuồng gây nguy hiểm. Vậy hãy sử dụng lồng hàng không với kiểu dáng nhỏ gọn, thông minh',
        'price' => 378000,
        'image' => 'gs://new_petshop_bucket/products/354/',
        'status' => true,
        'product_category_id' => 20,
      ],
      [
        'name' => 'Giỏ xách hàng không',
        'description' => 'Giỏ xách hàng không với thiết kế nhỏ gọn, thời trang cùng kết cấu vô cùng chắc chắn, thích hợp trong việc vận chuyển thú cưng trong các chuyến đi dài.',
        'price' => 270000,
        'image' => 'gs://new_petshop_bucket/products/355/',
        'status' => true,
        'product_category_id' => 20,
      ],
      [
        'name' => 'Túi xách gấp gọn tiện lợi',
        'description' => 'Túi xách gấp gọn vận chuyển thú cưng có thể gấp gọn tiện lợi được nhiều người nuôi tin dùng và sử dụng cho thú cưng hết sức tiện lợi.',
        'price' => 616000,
        'image' => 'gs://new_petshop_bucket/products/356/',
        'status' => true,
        'product_category_id' => 20,
      ],
      [
        'name' => 'Túi vải hoạ tiết size M',
        'description' => 'Túi vải hoạ tiết 3 size đa dạng màu sắc, hoạ tiết bắt mắt với kích thước khác nhau phù hợp với từng giống thú cưng, có nhiều họa tiết đa dạng.',
        'price' => 1010000,
        'image' => 'gs://new_petshop_bucket/products/357/',
        'status' => true,
        'product_category_id' => 20,
      ],
      [
        'name' => 'Lồng vận chuyển du lịch',
        'description' => 'Lồng vận chuyển du lịch dành cho thú cưng với thiết kế đặc biệt đáp ứng được yêu cầu khi cho lên khoang máy bay mà vẫn đem lại sự thoải mái cho thú cưng.',
        'price' => 340000,
        'image' => 'gs://new_petshop_bucket/products/358/',
        'status' => true,
        'product_category_id' => 20,
      ],
      [
        'name' => 'Túi nhựa kính con bọ',
        'description' => 'Túi nhựa kính con bọ dành cho chó mèo là phụ kiện không thể thiếu đối với người nuôi thú cưng. Túi mang kiểu dáng độc lạ, bắt mắt cùng chất liệu cao cấp.',
        'price' => 540000,
        'image' => 'gs://new_petshop_bucket/products/359/',
        'status' => true,
        'product_category_id' => 20,
      ],
    ];

    $dog_mattresses_bedding_products = [
      [
        'name' => 'Tấm lót chuồng loại tốt dành cho cún cưng',
        'description' => 'Tấm lót chuồng loại tốt sẽ giúp nền sàn chỗ ở của thú cưng được sạch sẽ, thông thoáng. Việc dọn vệ sinh cũng trở nên dễ dàng hơn rất nhiều.',
        'price' => 18000,
        'image' => 'gs://new_petshop_bucket/products/360/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Đệm cho chó mèo kèm chiếu điều hòa hình tròn',
        'description' => 'Đệm cho chó mèo kèm chiếu điều hoà hình trọn với kiểu dáng thanh lịch, sang trọng với không gian nằm nghỉ cho thú cưng thoải mái.',
        'price' => 175000,
        'image' => 'gs://new_petshop_bucket/products/361/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Thảm da lộn mát mẻ 80x60cm',
        'description' => 'Thảm da lộn mát mẻ 80*60 cm là sự lựa chọn hoàn hảo giúp đem đến những giấc ngủ ngon lành đến cho thú cưng nhà bạn, có độ bền cao, chống thấm, giữ nhiệt tốt',
        'price' => 235000,
        'image' => 'gs://new_petshop_bucket/products/362/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Set 2 nệm dù lưới hình lục giác và chữ nhật M & L',
        'description' => 'Set 2 nệm dù lưới hình lục giác và chữ nhật M & L được làm bằng chất liệu vải dù cao cấp, siêu bền ở bên ngoài, phía trong nệm được nhồi bông PP',
        'price' => 231000,
        'image' => 'gs://new_petshop_bucket/products/363/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Set nệm hình hoạt hình',
        'description' => 'Set nệm hình hoạt hình được thiết kết liền, không tháo rời ruột được lót bông mềm mại thích hợp cho thú cưng nô đùa, nghỉ ngơi.',
        'price' => 620000,
        'image' => 'gs://new_petshop_bucket/products/364/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Thảm gòn 40x60cm',
        'description' => 'Thảm gòn cao cấp êm ái giúp đem lại những giây phút thử giãn cùng giấc ngủ ngon lành đối với thú cưng nhà bạn, làm từ chất liệu PP 70%',
        'price' => 85000,
        'image' => 'gs://new_petshop_bucket/products/365/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Nệm xuất khẩu vuông, tròn',
        'description' => 'Nệm xuất khẩu vuông, tròn với kiểu dáng nhỏ gọn, tinh tế thanh lịch với phần thành nệm cao êm ái cho thú cưng tựa cổ, chống lăn khỏi nệm khi ngủ say.',
        'price' => 200000,
        'image' => 'gs://new_petshop_bucket/products/366/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Nệm hình chữ nhật',
        'description' => 'Nệm hình chữ nhật với nhiều kích thước, size khác nhau cho người nuôi lựa chọn, đem đến những giây phút nghỉ ngơi thư giãn đến thú cưng.',
        'price' => 128000,
        'image' => 'gs://new_petshop_bucket/products/367/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Nệm điều hòa cho chó mèo',
        'description' => 'Nệm điều hoà với thiết kế nhỏ gọn, thanh lịch tinh tế. Sản phẩm có thể điều chỉnh được nhiệt độ thích hợp, sử dụng được trong bốn mùa đem lại sự thuân tiện',
        'price' => 180000,
        'image' => 'gs://new_petshop_bucket/products/368/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Nệm họa hình mới dành cho chó mèo',
        'description' => 'Nệm họa hình mới với kích thước rộng rãi tạo không gian thư giãn, thoải mái cho thú cưng, nhiều kiểu dáng, màu sắc bắt mắt',
        'price' => 135000,
        'image' => 'gs://new_petshop_bucket/products/369/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Set nệm vải lưới 2 size',
        'description' => 'Set nệm vải lưới 2 size với độ dày lớn, được nhồi bông dày dặn tạo cảm giác bồng bềnh, êm ái khi nằm cho thú cưng, có kiểu dáng nhỏ gọn, thẩm mĩ',
        'price' => 196000,
        'image' => 'gs://new_petshop_bucket/products/370/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Nệm dành cho chó mèo giá rẻ',
        'description' => 'Nệm dành cho chó mèo giá rẻ với hoa văn đẹp mắt nổi bật nhiều màu sắc. sản phẩm được làm từ sợi vải mềm mịn có độ bền cao giúp giữ ấm cho cơ thể thú cưng',
        'price' => 98000,
        'image' => 'gs://new_petshop_bucket/products/371/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Set nệm hình hoạt hình dành cho chó mèo',
        'description' => 'Set nệm hình hoạt hình kiểu dáng cute, dễ thương với vòng vành tròn cao giúp thú cưng không bị lăn khỏi nệm khi ngủ say. Chất liệu vải cao cấp, nhồi bông',
        'price' => 196000,
        'image' => 'gs://new_petshop_bucket/products/372/',
        'status' => true,
        'product_category_id' => 21,
      ],
      [
        'name' => 'Ổ đệm êm ái dành cho chó mèo - size M 40x46cm',
        'description' => 'Ổ đệm êm ái dành cho chó mèo size M với kiểu dáng thời trang êm ái, ấm áp thoải mái với chất liệu sợi bông tổng hợp dày dặn cao cấp, bền đẹp.',
        'price' => 280000,
        'image' => 'gs://new_petshop_bucket/products/373/',
        'status' => true,
        'product_category_id' => 21,
      ],
    ];

    $dog_veterinary_medicine_products = [
      [
        'name' => 'Thuốc sát khuẩn Virkon',
        'description' => 'Thuốc sát khuẩn Virkon giúp ngăn chặn, tiêu diệt các mầm bệnh, chống lây nhiễm chéo an toàn không độc hại đối với sức khoẻ người nuôi và thú cưng.',
        'price' => 32000,
        'image' => 'gs://new_petshop_bucket/products/374/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc xổ giun cho chó Endogard',
        'description' => 'Thuốc xổ giun cho chó Endogard với các thành phần an toàn, đặc trị giúp tiêu diệt giun tròn, sán lá, sán dây,… bên trong cơ thể thú cưng hiệu quả.',
        'price' => 120000,
        'image' => 'gs://new_petshop_bucket/products/375/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc xổ giun cho chó Drontal - 1 viên',
        'description' => 'Thuốc xổ giun cho chó Drontal - 24 viên là sản phẩm giúp điều trị và phòng ngừa các loại giun tròn, sán dây gây ảnh hưởng đến đường ruột của thú cưng.',
        'price' => 36000,
        'image' => 'gs://new_petshop_bucket/products/376/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Trị viêm tai và rận tai Dexoryl 10g cho chó mèo',
        'description' => 'Trị viêm tai và vận tai Dexoryl 10g là thuốc nhỏ tai dạng huyền dịch dầu, giúp thuốc thẩm thấu tốt hơn, thuốc đem lại sự dễ chịu thoải mái đến cho các boss',
        'price' => 240000,
        'image' => 'gs://new_petshop_bucket/products/377/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Alkin Omnix nhỏ mắt',
        'description' => 'Alkin Omnix nhỏ mắt là sản phẩm giúp điều trị các vấn đề về mắt an toàn, hiệu quả đối với thú cưng, giúp xua tan đi các triệu chứng viêm giác mạc, khô mắt',
        'price' => 117000,
        'image' => 'gs://new_petshop_bucket/products/378/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Nước Rửa Tai Trị Viêm Bioline 50ml',
        'description' => 'Nước rửa tai trị viêm Bioline 50ml là sản phẩm dành cho chó mèo giúp vệ sinh làm sạch tai, ngăn ngừa các bệnh viêm tai khử mùi hôi hiệu quả.',
        'price' => 90000,
        'image' => 'gs://new_petshop_bucket/products/379/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Dung dịch nhỏ vệ sinh mắt Eye Care Bioline',
        'description' => 'Dung dịch nhỏ vệ sinh mắt Eye Care Bioline đến từ thương hiệu Bioline nổi tiếng đảm bảo về độ an toàn, giúp làm sạch mắt, điều trị triệu chứng viêm',
        'price' => 80000,
        'image' => 'gs://new_petshop_bucket/products/380/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc nhỏ mắt Bio Gentadrop - Hộp 1 chai 10ml',
        'description' => 'Thuốc nhỏ mắt Bio Gentadrop giúp điều trị các trường hợp viêm mắt do nhiễm trùng gây ra các triệu chứng mắt đỏ, chảy nhiều nước mắt, đục giác mạc',
        'price' => 128000,
        'image' => 'gs://new_petshop_bucket/products/381/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Alkin Otoklen nhỏ tai 20ml',
        'description' => 'Nhỏ rận viêm nấm cho chó mèo Alkin Otoklen phù hợp với tất cả các giống thú cưng chó mèo đem lại hiệu quả nhanh chóng, lâu dài',
        'price' => 127000,
        'image' => 'gs://new_petshop_bucket/products/382/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Alkin Otoclean vệ sinh tai 85ml',
        'description' => 'Alkin Otoclean giúp vệ sinh sạch sẽ tai thú cưng sạch sẽ loại bỏ mùi hôi khó chịu ở tai, giúp dễ dàng lấy đi cặn bẩn trong tai.',
        'price' => 119000,
        'image' => 'gs://new_petshop_bucket/products/383/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Oridermyl - Thuốc bôi trị viêm tai, ve, rận, nấm, ghẻ 10g',
        'description' => 'Oridermyl là thuốc trị viêm tai, ve rận, nấm ghẻ dưới dạng tuýt là sản phẩm đặc trị hiệu quả, an toàn các bệnh viêm tai ngoài và viêm tai giữa.',
        'price' => 196000,
        'image' => 'gs://new_petshop_bucket/products/384/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc phòng và trị ve, ghẻ, bọ chét NexGard - 1 viên',
        'description' => 'Viên nhai NexGard là thuốc dạng viên nhai trực tiếp với công dụng giúp đánh tan ve rận bọ chét và ghẻ Demodex, Srcoptes,… có tác dụng nhanh chóng,',
        'price' => 154000,
        'image' => 'gs://new_petshop_bucket/products/385/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Alkin Mitecyn xịt ghẻ nấm viêm da',
        'description' => 'Thuốc xịt trị ghẻ nấm viêm da Alkin Mitecyn giúp điều trị nhanh chóng và hiệu quả các triệu chứng nấm, ghẻ cục bộ các bệnh viêm da trên cơ thể thú cưng.',
        'price' => 123000,
        'image' => 'gs://new_petshop_bucket/products/386/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc nhỏ gáy trị ve rận hộp vàng',
        'description' => 'Thuốc nhỏ gáy trị ve rận hộp vàng huyền thoại, với thành phần từ thiên nhiên an toàn, dịu nhẹ với da thú cưng, tan đi nỗi lo về các vấn đề bệnh ngoài da.',
        'price' => 16000,
        'image' => 'gs://new_petshop_bucket/products/387/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc diệt bọ chét Alkin Topline cho chó từ 10-25kg',
        'description' => 'Thuốc diệt bọ chét Akin Topline với công dụng đánh tan các loại kí sinh bọ chét, ve rận nhanh chóng hiệu quả ngay trong lần đầu sử dụng.',
        'price' => 120000,
        'image' => 'gs://new_petshop_bucket/products/388/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc phun chữa ngứa, da có mủ Fabricil 50ml',
        'description' => 'Sản phẩm phun chữa ngứa, da có mủ Fabricl 50ml của thương hiệu Alkin nổi tiếng với ưu điểm vượt trội, nhanh chóng, chữa trị hoàn toàn cho thú cưng',
        'price' => 124000,
        'image' => 'gs://new_petshop_bucket/products/389/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc xịt diệt ve bọ chét Vime-Frondog',
        'description' => 'Thuốc xịt diệt ve bọ chét Vime-Frondog là dòng sản phẩm giúp phòng trị ve rận, bọ chét ở trên cơ thể thú cưng, đảm bảo triệt sạch các loại ký sinh',
        'price' => 125000,
        'image' => 'gs://new_petshop_bucket/products/390/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Dầu tắm trị nấm cho chó mèo Fungamyl 200ml',
        'description' => 'Dầu tắm Fungamyl 200ml là dòng sản phẩm giúp ngăn ngừa bệnh ve rận, kí sinh cùng các bệnh tróc vảy, rụng lông do vi khuẩn nấm gây nên.',
        'price' => 186000,
        'image' => 'gs://new_petshop_bucket/products/391/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Dầu tắm trị gầu da, nấm mủ Dermatic 200ml',
        'description' => 'Dầu tắm Dermatic 200ml chứa các tinh chất giúp hỗ trợ mọc lông, ngăn ngừa vi khuẩn nấm giảm thiểu gàu da nhanh chóng, an toàn.',
        'price' => 173000,
        'image' => 'gs://new_petshop_bucket/products/392/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Xịt sạch ve rận FAY Power 100ml',
        'description' => 'Thuốc xịt trị ve rận FAY Power 100ml của thương hiệu Fay là dòng sản phẩm chuyên trị, chất lượng với công thức diệt kí sinh, vi khuẩn hiệu quả vượt trội.',
        'price' => 107000,
        'image' => 'gs://new_petshop_bucket/products/393/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Xịt sạch ve rận Fay 100x 100ml',
        'description' => 'Xịt sạch ve rận Fay 100x 100ml có tác dụng làm sạch ve rận, bọ chét hiệu quả nhanh chóng dành cho thú cưng, có thành phần an toàn.',
        'price' => 54000,
        'image' => 'gs://new_petshop_bucket/products/394/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Bravecto trị ghẻ, viêm da, ve rận cho chó từ 2-4.5kg',
        'description' => 'Viên nhai Bravecto là sản phẩm giúp điều trị ghẻ, ve rận và các bệnh viêm da hiệu quả ở cún cưng trong ngay lần đầu sử dụng.',
        'price' => 620000,
        'image' => 'gs://new_petshop_bucket/products/395/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc viên Simparica trị ghẻ, viêm da, ve rận cho chó',
        'description' => 'Viên nhai Simparica trị ghẻ là sản phẩm đặc trị chuyên điều trị ve rận, ghẻ các bệnh viêm da ở cún cưng, được làm từ các thành phần an toàn, tác dụng nhanh',
        'price' => 540000,
        'image' => 'gs://new_petshop_bucket/products/396/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc nhỏ tai Tukono',
        'description' => 'Thuốc nhỏ tai Tukono giúp điều trị dứt điểm vấn đề viêm tại ngoài cấp tĩnh hoặc mãn tĩnh ở thú cưng, có thành phần an toàn, dịu nhẹ giúp làm giảm sưng tấy',
        'price' => 95000,
        'image' => 'gs://new_petshop_bucket/products/397/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc tẩy giun Bio dạng viên dành cho chó mèo',
        'description' => 'Thuốc tẩy giun Bio giúp loại bỏ hoàn toàn các ký sinh gây hại trong đường ruột thú cưng như giun đũa, giun tóc, giun móc và các loại sán dây.',
        'price' => 80000,
        'image' => 'gs://new_petshop_bucket/products/398/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc tẩy giun Drontal dạng nước dành cho chó mèo',
        'description' => 'Thuốc tẩy giun Drontal dạng nước sẽ làm cho việc cho chó mèo uống thuốc trở nên dễ dàng hơn nhiều. Sản phẩm không có vị đắng, phù hợp mọi thể trạng.',
        'price' => 315000,
        'image' => 'gs://new_petshop_bucket/products/399/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc tẩy giun Exotral dành cho chó mèo',
        'description' => 'Thuốc tẩy giun Exotral giúp điều trị các loài ký sinh trùng đường ruột của chó mèo cực kỳ hiệu quả, tẩy sạch giun đũa, giun móc cùng các loại sán dây có hại',
        'price' => 65000,
        'image' => 'gs://new_petshop_bucket/products/400/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Sữa tắm diệt nấm ghẻ Hantox cho chó mèo, phòng ngừa ve rận',
        'description' => 'Sữa tắm diệt nấm ghẻ Hantox đặc biệt hiệu quả trong việc trị ve chó, diệt bọ chét, rận, ghẻ, giúp làm sạch cơ thể thú cưng.',
        'price' => 65000,
        'image' => 'gs://new_petshop_bucket/products/401/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Sữa tắm trị ghẻ và nấm Bio Derma cho chó mèo cực kỳ hiệu quả',
        'description' => 'Sữa tắm trị ghẻ và nấm Bio Derma là kẻ thù của các bệnh về da hay gặp ở cún cưng, hiệu quả ngay lập tức, giúp phục hồi, vệ sinh lông và da của chó cảnh.',
        'price' => 101000,
        'image' => 'gs://new_petshop_bucket/products/402/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc nhỏ gáy trị ve rận chó mèo Fronil Spot',
        'description' => 'Thuốc nhỏ gáy trị ve rận chó mèo Fronil Spot là dòng sản phẩm đặc biệt giúp tiêu diệt và xua đuổi ve rận, bọ chét có tác dụng nhanh chóng, hiệu quả lâu dài.',
        'price' => 50000,
        'image' => 'gs://new_petshop_bucket/products/403/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc xịt trị nấm Fungikur Alkin 50ml',
        'description' => 'Thuốc xịt trị nấm Fungikur Alkin 50ml là sản phẩm đặc trị các vấn đề liên quan đến bệnh nấm, viêm da hay trên thú nuôi, có tác dụng chỉ sau một lần sử dụng.',
        'price' => 128000,
        'image' => 'gs://new_petshop_bucket/products/404/',
        'status' => true,
        'product_category_id' => 22,
      ],
      [
        'name' => 'Thuốc tẩy giun sán chó mèo Sanpet',
        'description' => 'Thuốc tẩy giun sán chó mèo Sanpet giúp tẩy sạch giun sán, kí sinh trùng bên trong chó mèo.,giúp hệ tiêu hóa của các bé khỏe mạnh hơn, phát triển tốt hơn.',
        'price' => 65000,
        'image' => 'gs://new_petshop_bucket/products/405/',
        'status' => true,
        'product_category_id' => 22,
      ],
    ];

    $dog_functional_food_products = [
      [
        'name' => 'Gel dinh dưỡng Bioline dành cho chó',
        'description' => 'Gel dinh dưỡng Bioline là sản phẩm dinh dưỡng dành cho các bé cún biếng ăn hay bỏ bữa, mệt mỏi sau khi vừa khỏi bệnh, giúp cún nhanh khỏe',
        'price' => 135000,
        'image' => 'gs://new_petshop_bucket/products/406/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'BIOVIT PLUS - Bổ sung dinh dưỡng tăng cường sức đề kháng',
        'description' => 'Biovit Plus là sản phẩm bổ sung vitamin, dưỡng chất và các chất điện giải góp phàna tăng sức đề kháng, giúp thú cưng hồi phục nhanh sau khi khỏi bệnh.',
        'price' => 224000,
        'image' => 'gs://new_petshop_bucket/products/407/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Viên bổ sung Canxi Calcium Phosphorus cho thú cưng',
        'description' => 'Viên bổ sung Canxi Calcium Phophorus là dạng viên nén với công thức kết hợp cân bằng tỉ lệ canxi và photpho bổ sung thêm vitamin D3 cho cơ thể của thú cưng',
        'price' => 338000,
        'image' => 'gs://new_petshop_bucket/products/408/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'VITA HEM - Hỗ trợ bổ máu và kích thích ăn cho chó mèo 100ml',
        'description' => 'Vita Hem là dung dịch bổ sung vào mỗi bữa ăn giúp bổ máu, tăng sự thèm ăn kích thích sự tăng trưởng của thú cưng được nhiều người nuôi tin dùng.',
        'price' => 215000,
        'image' => 'gs://new_petshop_bucket/products/409/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Giải độc gan VB Livo Plus',
        'description' => 'Giải độc gan VB Livo Plus với công dụng bảo vệ gan, thận ngăn ngừa các bệnh như kí sinh trùng đường máu, cầu trùng... đem lại sức khoẻ ổn định, thanh lọc cơ thể cho thú cưng.',
        'price' => 70000,
        'image' => 'gs://new_petshop_bucket/products/410/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Bio-Scour WSP đặc trị tiêu chảy chó mèo - 50 gói/hộp',
        'description' => 'Bio-Scour WSP đặc trị tiêu chảy chó mèo giúp sức khoẻ của các bé được ổn định, khoẻ mạnh, trị ói mửa, cải thiện sức khỏe cho thú cưng',
        'price' => 300000,
        'image' => 'gs://new_petshop_bucket/products/411/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Men tiêu hóa Biotic cho chó mèo gói 5g - 50 gói/hộp',
        'description' => 'Men hỗ trợ đường tiêu hoá Biotic là sản phẩm giúp cung cấp các vitamin và nhiều lợi khuẩn có lợi cho hệ tiêu hoá của thú cưng',
        'price' => 350000,
        'image' => 'gs://new_petshop_bucket/products/412/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Men tiêu hóa Probisol - Hộp 100 gói 5g',
        'description' => 'Men tiêu hoá Probisol chứa các vi sinh vật hữu ích và enzim tiêu hoá giúp cho đường ruột của thú cưng luôn được khoẻ mạnh và ổn định.',
        'price' => 584000,
        'image' => 'gs://new_petshop_bucket/products/413/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Men hỗ trợ đường tiêu hóa Clostop SP 20g',
        'description' => 'Men hỗ trợ tiêu hoá Clostop SP 20g chứa các lợi khuẩn nổi bật là Bacillus subtilis giúp tăng khả năng hấp thụ cho đường tiêu hoá',
        'price' => 35000,
        'image' => 'gs://new_petshop_bucket/products/414/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Bột nhổ lông tai cho chó mèo Pettis',
        'description' => 'Lông mọc trong tai thú cưng là lí do khiến tai các boss luôn bị ẩm ướt, dễ sinh bệnh. Vì vậy, bạn cần vệ sinh với bột nhổ lông tai cho chó mèo Pettis.',
        'price' => 49000,
        'image' => 'gs://new_petshop_bucket/products/415/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Xịt thơm miệng Bioline 175ml - khử mùi chống viêm',
        'description' => 'Xịt thơm miệng Bioline là giải pháp tối ưu giúp xoa tan đi vấn đề hôi miệng, loại bỏ các mảng bám trên răng của thú cưng.',
        'price' => 70000,
        'image' => 'gs://new_petshop_bucket/products/416/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Gel dinh dưỡng Bio Nutri Care',
        'description' => 'Gel dinh dưỡng Bio Nutri Care dang gel đậm đực có mùi vị ngon miệng, dễ dàng tiêu hoá cung cấp cá dưỡng chất thiết kiếu có lợi cho sức khoẻ thú cưng.',
        'price' => 165000,
        'image' => 'gs://new_petshop_bucket/products/417/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Nutri Plus Gel cho thú cưng',
        'description' => 'Kem ăn dinh dưỡng Nutri Plus Gel là thực phẩm chức năng giàu chất dinh dưỡng, cung cấp nguồn năng lượng cần thiết cho hoạt động, sinh hoạt của thú cưng.',
        'price' => 255000,
        'image' => 'gs://new_petshop_bucket/products/418/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Gel dinh dưỡng Nuvita Gel 120g',
        'description' => 'Gel dinh dưỡng Nuvita Gel là thực phẩm bố sung vitamin, khoáng chất cho chó mèo, giúp cho thú cưng được phát triển khoẻ mạnh và ổn định.',
        'price' => 102000,
        'image' => 'gs://new_petshop_bucket/products/419/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Canxi Nutrical hộp 30 viên',
        'description' => 'Canxi Nutrical hộp 30 viên giúp cung cấp nguồn canxi, photpho ở dạng Mono-Dicalcium Phosphat và calcium lactate giúp tạo nên khung xương và răng chắc khoẻ.',
        'price' => 145000,
        'image' => 'gs://new_petshop_bucket/products/420/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Tinh dầu dưỡng lông SH Relax Pet Essential 80ml',
        'description' => 'Tinh dầu dưỡng lông SH Relax Pet Essential là sự lựa chọn hoàn hảo khi  thú cưng gặp tình trạng lông xơ rối, dễ gãy.',
        'price' => 134000,
        'image' => 'gs://new_petshop_bucket/products/421/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Sữa tắm chó mèo Fay Medicare chuyên trị rụng lông, viêm da',
        'description' => 'Sữa tắm chó mèo Fay Medicare là dòng thuốc đặc trị da liễu cho chó mèo. Sản phẩm giúp hạn chế đáng kể các tình trạng rụng lông, ngứa ngáy viêm da do ghẻ.',
        'price' => 99000,
        'image' => 'gs://new_petshop_bucket/products/422/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Bột khoáng dinh dưỡng Chibi Powder cho chó giúp phát triển khung xương',
        'description' => 'Bột khoáng dinh dưỡng Chibi Powder cho chó là thực phẩm chức năng hỗ trợ cung cấp dinh dưỡng cho cún, giúp cún tăng khả năng vận động, ổn định tiêu hóa.',
        'price' => 335000,
        'image' => 'gs://new_petshop_bucket/products/423/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Viên Canxi Sleeky Thái cho chó mèo giúp phát triển khung xương to đẹp',
        'description' => 'Viên Canxi Sleeky Thái là dòng sản phẩm giúp bổ sung Canxi và khoáng chất cần thiết cho sự phát triển của cún. Cún sẽ phát triển tốt hơn, khỏe mạnh hơn.',
        'price' => 72000,
        'image' => 'gs://new_petshop_bucket/products/424/',
        'status' => true,
        'product_category_id' => 23,
      ],
      [
        'name' => 'Canxi nano cho chó hỗ trợ tăng cường canxi (chống hạ bàn)',
        'description' => 'Canxi nano giúp bổ sung, tăng cường canxi cho chó, giúp xương phát triển tốt hơn. Sản phẩm này sẽ giúp phòng chống các tật liên quan tới thiếu hụt canxi như chậm lớn, hạ bàn… Một lọ 150 viên',
        'price' => 263000,
        'image' => 'gs://new_petshop_bucket/products/425/',
        'status' => true,
        'product_category_id' => 23,
      ],
    ];

    $shop_ids = Shop::pluck('id')->toArray();

    // ================================     FOOD AND NUTRITION     ================================
    foreach ($dog_food_products as $dog_food_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_food_product['quantity'] = rand(100, 150);
      $dog_food_product['sold_quantity'] = 0;
      $dog_food_product['shop_id'] = $random_shop_id;
      $dog_food_product['created_at'] = $created_at;
      $dog_food_product['updated_at'] = $created_at;

      Product::create($dog_food_product);
    }

    foreach ($dog_milk_products as $dog_milk_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_milk_product['quantity'] = rand(100, 150);
      $dog_milk_product['sold_quantity'] = 0;
      $dog_milk_product['shop_id'] = $random_shop_id;
      $dog_milk_product['created_at'] = $created_at;
      $dog_milk_product['updated_at'] = $created_at;

      Product::create($dog_milk_product);
    }

    foreach ($dog_pate_sauce_products as $dog_pate_sauce_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_pate_sauce_product['quantity'] = rand(100, 150);
      $dog_pate_sauce_product['sold_quantity'] = 0;
      $dog_pate_sauce_product['shop_id'] = $random_shop_id;
      $dog_pate_sauce_product['created_at'] = $created_at;
      $dog_pate_sauce_product['updated_at'] = $created_at;

      Product::create($dog_pate_sauce_product);
    }

    foreach ($dog_treats_and_chew_products as $dog_treats_and_chew_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_treats_and_chew_product['quantity'] = rand(100, 150);
      $dog_treats_and_chew_product['sold_quantity'] = 0;
      $dog_treats_and_chew_product['shop_id'] = $random_shop_id;
      $dog_treats_and_chew_product['created_at'] = $created_at;
      $dog_treats_and_chew_product['updated_at'] = $created_at;

      Product::create($dog_treats_and_chew_product);
    }

    // ================================     ACCESSORIES AND TOYS     ================================
    foreach ($dog_clothing_products as $dog_clothing_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_clothing_product['quantity'] = rand(100, 150);
      $dog_clothing_product['sold_quantity'] = 0;
      $dog_clothing_product['shop_id'] = $random_shop_id;
      $dog_clothing_product['created_at'] = $created_at;
      $dog_clothing_product['updated_at'] = $created_at;

      Product::create($dog_clothing_product);
    }

    foreach ($dog_toy_products as $dog_toy_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_toy_product['quantity'] = rand(100, 150);
      $dog_toy_product['sold_quantity'] = 0;
      $dog_toy_product['shop_id'] = $random_shop_id;
      $dog_toy_product['created_at'] = $created_at;
      $dog_toy_product['updated_at'] = $created_at;

      Product::create($dog_toy_product);
    }

    foreach ($dog_collar_products as $dog_collar_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_collar_product['quantity'] = rand(100, 150);
      $dog_collar_product['sold_quantity'] = 0;
      $dog_collar_product['shop_id'] = $random_shop_id;
      $dog_collar_product['created_at'] = $created_at;
      $dog_collar_product['updated_at'] = $created_at;

      Product::create($dog_collar_product);
    }

    foreach ($dog_leash_products as $dog_leash_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_leash_product['quantity'] = rand(100, 150);
      $dog_leash_product['sold_quantity'] = 0;
      $dog_leash_product['shop_id'] = $random_shop_id;
      $dog_leash_product['created_at'] = $created_at;
      $dog_leash_product['updated_at'] = $created_at;

      Product::create($dog_leash_product);
    }

    foreach ($dog_muzzles_products as $dog_muzzles_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_muzzles_product['quantity'] = rand(100, 150);
      $dog_muzzles_product['sold_quantity'] = 0;
      $dog_muzzles_product['shop_id'] = $random_shop_id;
      $dog_muzzles_product['created_at'] = $created_at;
      $dog_muzzles_product['updated_at'] = $created_at;

      Product::create($dog_muzzles_product);
    }

    foreach ($dog_water_bowls_products as $dog_water_bowls_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_water_bowls_product['quantity'] = rand(100, 150);
      $dog_water_bowls_product['sold_quantity'] = 0;
      $dog_water_bowls_product['shop_id'] = $random_shop_id;
      $dog_water_bowls_product['created_at'] = $created_at;
      $dog_water_bowls_product['updated_at'] = $created_at;

      Product::create($dog_water_bowls_product);
    }

    foreach ($dog_feeding_bowls_products as $dog_feeding_bowls_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_feeding_bowls_product['quantity'] = rand(100, 150);
      $dog_feeding_bowls_product['sold_quantity'] = 0;
      $dog_feeding_bowls_product['shop_id'] = $random_shop_id;
      $dog_feeding_bowls_product['created_at'] = $created_at;
      $dog_feeding_bowls_product['updated_at'] = $created_at;

      Product::create($dog_feeding_bowls_product);
    }

    foreach ($dog_grooming_brush_products as $dog_grooming_brush_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_grooming_brush_product['quantity'] = rand(100, 150);
      $dog_grooming_brush_product['sold_quantity'] = 0;
      $dog_grooming_brush_product['shop_id'] = $random_shop_id;
      $dog_grooming_brush_product['created_at'] = $created_at;
      $dog_grooming_brush_product['updated_at'] = $created_at;

      Product::create($dog_grooming_brush_product);
    }

    // ================================     HYGIENE AND CARE     ================================
    foreach ($dog_shampoo_products as $dog_shampoo_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_shampoo_product['quantity'] = rand(100, 150);
      $dog_shampoo_product['sold_quantity'] = 0;
      $dog_shampoo_product['shop_id'] = $random_shop_id;
      $dog_shampoo_product['created_at'] = $created_at;
      $dog_shampoo_product['updated_at'] = $created_at;

      Product::create($dog_shampoo_product);
    }

    foreach ($dog_towel_products as $dog_towel_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_towel_product['quantity'] = rand(100, 150);
      $dog_towel_product['sold_quantity'] = 0;
      $dog_towel_product['shop_id'] = $random_shop_id;
      $dog_towel_product['created_at'] = $created_at;
      $dog_towel_product['updated_at'] = $created_at;

      Product::create($dog_towel_product);
    }

    foreach ($dog_perfumes_products as $dog_perfumes_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_perfumes_product['quantity'] = rand(100, 150);
      $dog_perfumes_product['sold_quantity'] = 0;
      $dog_perfumes_product['shop_id'] = $random_shop_id;
      $dog_perfumes_product['created_at'] = $created_at;
      $dog_perfumes_product['updated_at'] = $created_at;

      Product::create($dog_perfumes_product);
    }

    foreach ($dog_diapers_products as $dog_diapers_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_diapers_product['quantity'] = rand(100, 150);
      $dog_diapers_product['sold_quantity'] = 0;
      $dog_diapers_product['shop_id'] = $random_shop_id;
      $dog_diapers_product['created_at'] = $created_at;
      $dog_diapers_product['updated_at'] = $created_at;

      Product::create($dog_diapers_product);
    }

    foreach ($dog_tray_products as $dog_tray_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_tray_product['quantity'] = rand(100, 150);
      $dog_tray_product['sold_quantity'] = 0;
      $dog_tray_product['shop_id'] = $random_shop_id;
      $dog_tray_product['created_at'] = $created_at;
      $dog_tray_product['updated_at'] = $created_at;

      Product::create($dog_tray_product);
    }

    // ================================     ACCOMMODATION     ================================
    foreach ($dog_house_products as $dog_house_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_house_product['quantity'] = rand(100, 150);
      $dog_house_product['sold_quantity'] = 0;
      $dog_house_product['shop_id'] = $random_shop_id;
      $dog_house_product['created_at'] = $created_at;
      $dog_house_product['updated_at'] = $created_at;

      Product::create($dog_house_product);
    }

    foreach ($dog_backpack_products as $dog_backpack_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_backpack_product['quantity'] = rand(100, 150);
      $dog_backpack_product['sold_quantity'] = 0;
      $dog_backpack_product['shop_id'] = $random_shop_id;
      $dog_backpack_product['created_at'] = $created_at;
      $dog_backpack_product['updated_at'] = $created_at;

      Product::create($dog_backpack_product);
    }

    foreach ($dog_carrying_bag_products as $dog_carrying_bag_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_carrying_bag_product['quantity'] = rand(100, 150);
      $dog_carrying_bag_product['sold_quantity'] = 0;
      $dog_carrying_bag_product['shop_id'] = $random_shop_id;
      $dog_carrying_bag_product['created_at'] = $created_at;
      $dog_carrying_bag_product['updated_at'] = $created_at;

      Product::create($dog_carrying_bag_product);
    }

    foreach ($dog_mattresses_bedding_products as $dog_mattresses_bedding_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_mattresses_bedding_product['quantity'] = rand(100, 150);
      $dog_mattresses_bedding_product['sold_quantity'] = 0;
      $dog_mattresses_bedding_product['shop_id'] = $random_shop_id;
      $dog_mattresses_bedding_product['created_at'] = $created_at;
      $dog_mattresses_bedding_product['updated_at'] = $created_at;

      Product::create($dog_mattresses_bedding_product);
    }

    // ================================     MEDICINE AND FUNCTIONAL FOODS     ================================
    foreach ($dog_veterinary_medicine_products as $dog_veterinary_medicine_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_veterinary_medicine_product['quantity'] = rand(100, 150);
      $dog_veterinary_medicine_product['sold_quantity'] = 0;
      $dog_veterinary_medicine_product['shop_id'] = $random_shop_id;
      $dog_veterinary_medicine_product['created_at'] = $created_at;
      $dog_veterinary_medicine_product['updated_at'] = $created_at;

      Product::create($dog_veterinary_medicine_product);
    }

    foreach ($dog_functional_food_products as $dog_functional_food_product) {
      $created_at = $faker->dateTimeBetween('-2 years', 'now');
      $random_shop_id = $shop_ids[array_rand($shop_ids)];

      $dog_functional_food_product['quantity'] = rand(100, 150);
      $dog_functional_food_product['sold_quantity'] = 0;
      $dog_functional_food_product['shop_id'] = $random_shop_id;
      $dog_functional_food_product['created_at'] = $created_at;
      $dog_functional_food_product['updated_at'] = $created_at;

      Product::create($dog_functional_food_product);
    }
  }
}
