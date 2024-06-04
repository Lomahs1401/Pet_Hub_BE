<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shop;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
  public function index()
  {
    $query = Product::query();

    $products = $query->whereNull('deleted_at')->get();

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $products,
    ], 200);
  }

  public function show($id)
  {
    $query = Product::query()->with('shop', 'category');

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $product = $query
        ->whereNull('deleted_at')
        ->where('shop_id', $shop_id)
        ->find($id);
    } else {
      $product = $query->whereNull('deleted_at')->find($id);
    }

    if (!$product) {
      return response()->json([
        'message' => 'Product not found!',
        'status' => 404
      ], 404);
    }

    $ratingData = $product->calculateProductRating();

    $formatted_product = [
      'id' => $product->id,
      'name' => $product->name,
      'description' => $product->description,
      'price' => $product->price,
      'image' => $product->image,
      'quantity' => $product->quantity,
      'sold_quantity' => $product->sold_quantity,
      'status' => $product->status,
      'shop_id' => $product->shop_id,
      'product_category_id' => $product->product_category_id,
      "rating" => $ratingData['average'],
      "rating_count" => $ratingData['count'],
      'created_at' => $product->created_at,
      'updated_at' => $product->updated_at,
      'deleted_at' => $product->deleted_at,
      'shop' => [
        'id' => $product->shop->id,
        'name' => $product->shop->name,
        'description' => $product->shop->description,
        'image' => $product->shop->image,
        'phone' => $product->shop->phone,
        'address' => $product->shop->address,
        'website' => $product->shop->website,
        'fanpage' => $product->shop->fanpage,
        'work_time' => $product->shop->work_time,
        'establish_year' => $product->shop->establish_year,
        'account_id' => $product->shop->account_id,
        'created_at' => $product->shop->created_at,
        'updated_at' => $product->shop->updated_at,
      ],
      'category' => [
        'id' => $product->category->id,
        'name' => $product->category->name,
        'target' => $product->category->target,
        'type' => $product->category->type,
        'created_at' => $product->category->created_at,
        'updated_at' => $product->category->updated_at,
      ],
    ];

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $formatted_product,
    ], 200);
  }

  public function getListProductByShopId($shop_id)
  {
    if (!Shop::find($shop_id)) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    $products = Product::where('shop_id', $shop_id)
      ->whereNull('deleted_at')
      ->get();

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $products,
    ], 200);
  }

  public function getNumberOfProductByShopId($shop_id = null)
  {
    $query = Product::query();

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $number_of_products = $query
        ->where('shop_id', $auth_shop_id)
        ->whereNull('deleted_at')
        ->count();
    } else {
      if (!Shop::find($shop_id) && $shop_id != null) {
        return response()->json([
          'message' => 'Shop not found!',
          'status' => 404
        ], 404);
      }
      $number_of_products = $query
        ->whereNull('deleted_at')
        ->where('shop_id', $shop_id)
        ->count();
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $number_of_products,
    ], 200);
  }

  public function getListProductByCategoryId($product_category_id)
  {
    if (!ProductCategory::find($product_category_id)) {
      return response()->json([
        'message' => 'Product category not found!',
        'status' => 404
      ], 404);
    }

    $products = Product::where('product_category_id', $product_category_id)
      ->whereNull('deleted_at')
      ->get();

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $products,
    ], 200);
  }

  public function getNumberOfProductByCategoryId($product_category_id)
  {
    if (!ProductCategory::find($product_category_id)) {
      return response()->json([
        'message' => 'Category not found!',
        'status' => 404
      ], 404);
    }

    $number_of_products = Product::where('product_category_id', $product_category_id)
      ->whereNull('deleted_at')
      ->count();

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $number_of_products,
    ], 200);
  }

  public function getListProductWithShopAndCategory($shop_id, $product_category_id)
  {
    $shop = Shop::find($shop_id);
    if (!$shop) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    $category = ProductCategory::find($product_category_id);
    if (!$category) {
      return response()->json([
        'message' => 'Category not found!',
        'status' => 404
      ], 404);
    }

    $products = Product::where('shop_id', $shop_id)
      ->whereNull('deleted_at')
      ->where('product_category_id', $product_category_id)
      ->with('shop', 'category')
      ->get();

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $products,
    ], 200);
  }

  public function getNumberOfProductWithShopAndCategory($product_category_id, $shop_id = null)
  {
    $query = Product::query();

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $number_of_products = $query->where('shop_id', $auth_shop_id)
        ->whereNull('deleted_at')
        ->where('product_category_id', $product_category_id)
        ->count();
    } else {
      $shop = Shop::find($shop_id);
      if (!$shop && $shop_id != null) {
        return response()->json([
          'message' => 'Shop not found!',
          'status' => 404
        ], 404);
      }

      $category = ProductCategory::find($product_category_id);
      if (!$category) {
        return response()->json([
          'message' => 'Category not found!',
          'status' => 404
        ], 404);
      }

      $number_of_products = $query->where('shop_id', $shop_id)
        ->whereNull('deleted_at')
        ->where('product_category_id', $product_category_id)
        ->count();
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $number_of_products,
    ], 200);
  }

  public function getNumberOfShopSellingByCategory($product_category_id)
  {
    $category = ProductCategory::find($product_category_id);
    if (!$category) {
      return response()->json([
        'message' => 'Product Category not found!',
        'status' => 404
      ], 404);
    }

    $shops = Product::whereHas('category', function ($query) use ($product_category_id) {
      $query->where('id', $product_category_id);
    })
      ->distinct('shop_id')
      ->pluck('shop_id')
      ->whereNull('deleted_at');

    $number_of_shops = $shops->count();

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $number_of_shops,
    ], 200);
  }

  public function searchProduct(Request $request)
  {
    $name = $request->query('name');

    // Phân trang mặc định
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 8));
    $category_type = $request->query('category') ?? 'all';
    $target = strtolower($request->query('target')) ?? 'all';

    if ($target === 'all') {
      // Nếu không có field 'target' được gửi đến, lấy tất cả các danh mục
      if ($category_type === 'all') {
        // Nếu category type cũng là 'all', lấy tất cả danh mục
        $categories = DB::table('product_categories')
          ->pluck('id')
          ->toArray();
      } else {
        // Lọc theo category type
        $categories = DB::table('product_categories')
          ->where('type', $category_type)
          ->pluck('id')
          ->toArray();
      }
    } else {
      // Nếu 'target' là 'dog' hoặc 'cat' thì query dữ liệu từ bảng 'product_categories'
      if ($target === 'dog' || $target === 'cat') {
        if ($category_type === 'all') {
          // Nếu category_type là 'all', chỉ lọc theo target
          $categories = DB::table('product_categories')
            ->where('target', $target)
            ->pluck('id')
            ->toArray();
        } else {
          // Lọc danh mục theo cả hai điều kiện $target và $category_type
          $categories = DB::table('product_categories')
            ->where('target', $target)
            ->where('type', $category_type)
            ->pluck('id')
            ->toArray();
        }

        // Nếu không có danh mục nào phù hợp thì trả về mảng rỗng
        if (empty($categories)) {
          return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => []
          ]);
        }
      }
    }

    $query = Product::query()
      ->whereNull('deleted_at')
      ->with(['shop.account', 'category']);

    // Nếu có danh mục, thêm điều kiện whereIn
    if (!empty($categories)) {
      $query->whereIn('product_category_id', $categories);
    }

    if ($name) {
      $query->where('name', 'like', '%' . $name . '%');
    }

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $products = $query->where('shop_id', $shop_id)
        ->paginate($num_of_page, ['*'], 'page', $page_number);
    } else {
      $products = $query->paginate($num_of_page, ['*'], 'page', $page_number);
    }

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $formatted_products[] = [
        "id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "image" => $product->image,
        "quantity" => $product->quantity,
        "sold_quantity" => $product->sold_quantity,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        "status" => $product->status,
        "shop_id" => $product->shop_id,
        "product_category_id" => $product->product_category_id,
        "created_at" => $product->created_at,
        "updated_at" => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        "shop" => [
          "id" => $product->shop->id,
          "name" => $product->shop->name,
          "email" => $product->shop->account->email,
          "avatar" => $product->shop->account->avatar,
          "description" => $product->shop->description,
          "image" => $product->shop->image,
          "phone" => $product->shop->phone,
          "address" => $product->shop->address,
          "website" => $product->shop->website,
          "fanpage" => $product->shop->fanpage,
          "work_time" => $product->shop->work_time,
          "establish_year" => $product->shop->establish_year,
          "account_id" => $product->shop->account->id,
          "created_at" => $product->shop->created_at,
          "updated_at" => $product->shop->updated_at,
        ],
        "category" => [
          "id" => $product->category->id,
          "name" => $product->category->name,
          "target" => $product->category->target,
          "type" => $product->category->type,
          "created_at" => $product->category->created_at,
          "updated_at" => $product->category->updated_at,
        ]
      ];
    }

    return response()->json([
      'message' => 'Search products successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $products->total(),
        'per_page' => $products->perPage(),
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'from' => $products->firstItem(),
        'to' => $products->lastItem(),
      ],
      'data' => $formatted_products,
    ], 200);
  }

  public function searchDeletedProduct(Request $request)
  {
    $name = $request->query('name');

    // Phân trang mặc định
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 8));
    $category_type = $request->query('category') ?? 'all';
    $target = strtolower($request->query('target')) ?? 'all';

    if ($target === 'all') {
      // Nếu không có field 'target' được gửi đến, lấy tất cả các danh mục
      if ($category_type === 'all') {
        // Nếu category type cũng là 'all', lấy tất cả danh mục
        $categories = DB::table('product_categories')
          ->pluck('id')
          ->toArray();
      } else {
        // Lọc theo category type
        $categories = DB::table('product_categories')
          ->where('type', $category_type)
          ->pluck('id')
          ->toArray();
      }
    } else {
      // Nếu 'target' là 'dog' hoặc 'cat' thì query dữ liệu từ bảng 'product_categories'
      if ($target === 'dog' || $target === 'cat') {
        if ($category_type === 'all') {
          // Nếu category_type là 'all', chỉ lọc theo target
          $categories = DB::table('product_categories')
            ->where('target', $target)
            ->pluck('id')
            ->toArray();
        } else {
          // Lọc danh mục theo cả hai điều kiện $target và $category_type
          $categories = DB::table('product_categories')
            ->where('target', $target)
            ->where('type', $category_type)
            ->pluck('id')
            ->toArray();
        }

        // Nếu không có danh mục nào phù hợp thì trả về mảng rỗng
        if (empty($categories)) {
          return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => []
          ]);
        }
      }
    }

    $query = Product::query()
      ->onlyTrashed('deleted_at')
      ->with(['shop.account', 'category']);

    // Nếu có danh mục, thêm điều kiện whereIn
    if (!empty($categories)) {
      $query->whereIn('product_category_id', $categories);
    }

    if ($name) {
      $query->where('name', 'like', '%' . $name . '%');
    }

    $user = auth()->user();
    $shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
    $products = $query
      ->where('shop_id', $shop_id)
      ->paginate($num_of_page, ['*'], 'page', $page_number);

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $formatted_products[] = [
        "id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "image" => $product->image,
        "quantity" => $product->quantity,
        "sold_quantity" => $product->sold_quantity,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        "status" => $product->status,
        "shop_id" => $product->shop_id,
        "product_category_id" => $product->product_category_id,
        "created_at" => $product->created_at,
        "updated_at" => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        "shop" => [
          "id" => $product->shop->id,
          "name" => $product->shop->name,
          "email" => $product->shop->account->email,
          "avatar" => $product->shop->account->avatar,
          "description" => $product->shop->description,
          "image" => $product->shop->image,
          "phone" => $product->shop->phone,
          "address" => $product->shop->address,
          "website" => $product->shop->website,
          "fanpage" => $product->shop->fanpage,
          "work_time" => $product->shop->work_time,
          "establish_year" => $product->shop->establish_year,
          "account_id" => $product->shop->account->id,
          "created_at" => $product->shop->created_at,
          "updated_at" => $product->shop->updated_at,
        ],
        "category" => [
          "id" => $product->category->id,
          "name" => $product->category->name,
          "target" => $product->category->target,
          "type" => $product->category->type,
          "created_at" => $product->category->created_at,
          "updated_at" => $product->category->updated_at,
        ]
      ];
    }

    return response()->json([
      'message' => 'Search products successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $products->total(),
        'per_page' => $products->perPage(),
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'from' => $products->firstItem(),
        'to' => $products->lastItem(),
      ],
      'data' => $formatted_products,
    ], 200);
  }

  public function paging(Request $request)
  {
    $categories = [];

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $target = strtolower($request->query('target')) ?? 'all';

    if ($target === 'all') {
      // Nếu không có field 'target' được gửi đến, lấy tất cả các danh mục
      $categories = DB::table('product_categories')
        ->pluck('id')
        ->toArray();
    } else {
      // Nếu 'target' là 'dog' hoặc 'cat' thì query dữ liệu từ bảng 'product_categories'
      if ($target === 'dog' || $target === 'cat') {
        $categories = DB::table('product_categories')
          ->where('target', $target)
          ->pluck('id')
          ->toArray();

        // Nếu không có danh mục nào phù hợp thì trả về mảng rỗng
        if (empty($categories)) {
          return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => []
          ]);
        }
      }
    }

    // Lấy số lượng sản phẩm
    $total_products_query = Product::query()->whereNull('deleted_at');

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $total_products_query->where('shop_id', $shop_id);
    }

    // Nếu có danh mục, thêm điều kiện whereIn
    if (!empty($categories)) {
      $total_products_query->whereIn('product_category_id', $categories);
    }

    // Tính tổng số trang
    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy dữ liệu sản phẩm dựa trên trang hiện tại và số lượng sản phẩm trên mỗi trang
    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category']) // Sử dụng eager loading để lấy thông tin cửa hàng
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    // Trả về JSON response
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getBestSellingProduct(Request $request)
  {
    $categories = [];

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $target = strtolower($request->query('target')) ?? 'all';

    if ($target === 'all') {
      // Nếu không có field 'target' được gửi đến, lấy tất cả các danh mục
      $categories = DB::table('product_categories')
        ->pluck('id')
        ->toArray();
    } else {
      // Nếu 'target' là 'dog' hoặc 'cat' thì query dữ liệu từ bảng 'product_categories'
      if ($target === 'dog' || $target === 'cat') {
        $categories = DB::table('product_categories')
          ->where('target', $target)
          ->pluck('id')
          ->toArray();

        // Nếu không có danh mục nào phù hợp thì trả về mảng rỗng
        if (empty($categories)) {
          return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => []
          ]);
        }
      }
    }

    // Lấy số lượng sản phẩm
    $total_products_query = Product::query()->whereNull('deleted_at');

    // Nếu có danh mục, thêm điều kiện whereIn
    if (!empty($categories)) {
      $total_products_query->whereIn('product_category_id', $categories);
    }

    // Tính tổng số trang
    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy dữ liệu sản phẩm dựa trên trang hiện tại và số lượng sản phẩm trên mỗi trang
    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category']) // Sử dụng eager loading để lấy thông tin cửa hàng
      ->orderBy('sold_quantity', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    // Trả về JSON response
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getBestSellingProductByShop(Request $request, $shop_id = null)
  {
    // Kiểm tra sự tồn tại của shop
    if (!Shop::find($shop_id) && $shop_id != null) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    $categories = [];

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $is_deleted = $request->query('deleted', false);

    $target = strtolower($request->query('target')) ?? 'all';

    if ($target === 'all') {
      // Nếu không có field 'target' được gửi đến, lấy tất cả các danh mục
      $categories = DB::table('product_categories')
        ->pluck('id')
        ->toArray();
    } else {
      // Nếu 'target' là 'dog' hoặc 'cat' thì query dữ liệu từ bảng 'product_categories'
      if ($target === 'dog' || $target === 'cat') {
        $categories = DB::table('product_categories')
          ->where('target', $target)
          ->pluck('id')
          ->toArray();

        // Nếu không có danh mục nào phù hợp thì trả về mảng rỗng
        if (empty($categories)) {
          return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => []
          ]);
        }
      }
    }

    if ($is_deleted) {
      $total_products_query = Product::query()->onlyTrashed();
    } else {
      $total_products_query = Product::query()->whereNull('deleted_at');
    }

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $total_products_query->where('shop_id', $auth_shop_id);
    } else {
      $total_products_query->where('shop_id', $shop_id);
    }

    // Nếu có danh mục, thêm điều kiện whereIn
    if (!empty($categories)) {
      $total_products_query->whereIn('product_category_id', $categories);
    }

    // Tính tổng số trang
    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy dữ liệu sản phẩm dựa trên trang hiện tại và số lượng sản phẩm trên mỗi trang
    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category']) // Sử dụng eager loading để lấy thông tin cửa hàng->whereIn('product_category_id', $categories)
      ->orderBy('sold_quantity', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    // Trả về JSON response
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getBestSellingProductByCategory(Request $request, $product_category_id)
  {
    if (!ProductCategory::find($product_category_id)) {
      return response()->json([
        'message' => 'Product category not found!',
        'status' => 404
      ], 404);
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Lấy số lượng sản phẩm
    $total_products_query = Product::query()
      ->whereNull('deleted_at')
      ->where('product_category_id', $product_category_id);

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $total_products_query->where('shop_id', $auth_shop_id);
    }

    // Tính tổng số trang
    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy dữ liệu sản phẩm dựa trên trang hiện tại và số lượng sản phẩm trên mỗi trang
    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category']) // Sử dụng eager loading để lấy thông tin cửa hàng
      ->orderBy('sold_quantity', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    // Trả về JSON response
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getBestSellingProductWithShopAndCategoryType(Request $request, $shop_id)
  {
    if (!Shop::find($shop_id)) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $category_type = $request->query('category_type', '');

    // Lấy danh sách các ID của các category có cùng type
    $category_ids = ProductCategory::where('type', $category_type)->pluck('id')->toArray();

    if (empty($category_ids)) {
      return response()->json([
        'message' => 'No categories found for the given type!',
        'status' => 404
      ], 404);
    }

    $total_products_query = Product::query()
      ->whereNull('deleted_at')
      ->where('shop_id', $shop_id)
      ->whereIn('product_category_id', $category_ids);

    // Tính tổng số trang
    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy dữ liệu sản phẩm dựa trên trang hiện tại và số lượng sản phẩm trên mỗi trang
    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category']) // Sử dụng eager loading để lấy thông tin cửa hàng
      ->orderBy('sold_quantity', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    // Trả về JSON response
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_page' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getBestSellingProductWithShopAndCategory(Request $request, $product_category_id, $shop_id)
  {
    if (!Shop::find($shop_id)) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    if (!ProductCategory::find($product_category_id)) {
      return response()->json([
        'message' => 'Product category not found!',
        'status' => 404
      ], 404);
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Lấy số lượng sản phẩm
    $total_products_query = Product::query()
      ->whereNull('deleted_at')
      ->where('shop_id', $shop_id)
      ->where('product_category_id', $product_category_id);

    // Tính tổng số trang
    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy dữ liệu sản phẩm dựa trên trang hiện tại và số lượng sản phẩm trên mỗi trang
    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category']) // Sử dụng eager loading để lấy thông tin cửa hàng
      ->orderBy('sold_quantity', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    // Trả về JSON response
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_page' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getHighestRatingProduct(Request $request)
  {
    $categories = [];

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $target = strtolower($request->query('target')) ?? 'all';

    if ($target === 'all') {
      $categories = DB::table('product_categories')
        ->pluck('id')
        ->toArray();
    } else {
      if ($target === 'dog' || $target === 'cat') {
        $categories = DB::table('product_categories')
          ->where('target', $target)
          ->pluck('id')
          ->toArray();

        if (empty($categories)) {
          return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => []
          ]);
        }
      }
    }

    $total_products_query = Product::query()->whereNull('deleted_at');

    if (!empty($categories)) {
      $total_products_query->whereIn('product_category_id', $categories);
    }

    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);
    $offset = ($page_number - 1) * $num_of_page;

    $averageRatings = DB::table('rating_products')
      ->select('product_id', DB::raw('AVG(rating) as average_rating'))
      ->groupBy('product_id');

    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category'])
      ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
        $join->on('products.id', '=', 'average_ratings.product_id');
      })
      ->select('products.*', 'average_ratings.average_rating')
      ->whereIn('product_category_id', $categories)
      ->orderBy('average_rating', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getHighestRatingProductByShop(Request $request, $shop_id = null)
  {
    // Kiểm tra sự tồn tại của shop
    if (!Shop::find($shop_id) && $shop_id != null) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    $categories = [];

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $is_deleted = $request->query('deleted', false);
    $target = strtolower($request->query('target')) ?? 'all';

    if ($target === 'all') {
      $categories = DB::table('product_categories')
        ->pluck('id')
        ->toArray();
    } else {
      if ($target === 'dog' || $target === 'cat') {
        $categories = DB::table('product_categories')
          ->where('target', $target)
          ->pluck('id')
          ->toArray();

        if (empty($categories)) {
          return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => []
          ]);
        }
      }
    }

    if ($is_deleted) {
      $total_products_query = Product::query()->onlyTrashed();
    } else {
      $total_products_query = Product::query()->whereNull('deleted_at');
    }

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $total_products_query->where('shop_id', $auth_shop_id);
    } else {
      $total_products_query->where('shop_id', $shop_id);
    }

    if (!empty($categories)) {
      $total_products_query->whereIn('product_category_id', $categories);
    }

    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);
    $offset = ($page_number - 1) * $num_of_page;

    $averageRatings = DB::table('rating_products')
      ->select('product_id', DB::raw('AVG(rating) as average_rating'))
      ->groupBy('product_id');

    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category'])
      ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
        $join->on('products.id', '=', 'average_ratings.product_id');
      })
      ->select('products.*', 'average_ratings.average_rating')
      ->whereIn('product_category_id', $categories)
      ->orderBy('average_rating', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getHighestRatingProductByCategory(Request $request, $product_category_id)
  {
    if (!ProductCategory::find($product_category_id)) {
      return response()->json([
        'message' => 'Product category not found!',
        'status' => 404
      ], 404);
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $total_products_query = Product::query()
      ->whereNull('deleted_at')
      ->where('product_category_id', $product_category_id);

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $total_products_query->where('shop_id', $auth_shop_id);
    }

    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);
    $offset = ($page_number - 1) * $num_of_page;

    $averageRatings = DB::table('rating_products')
      ->select('product_id', DB::raw('AVG(rating) as average_rating'))
      ->groupBy('product_id');

    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category'])
      ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
        $join->on('products.id', '=', 'average_ratings.product_id');
      })
      ->select('products.*', 'average_ratings.average_rating')
      ->orderBy('average_rating', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getHighestRatingProductWithShopAndCategory(Request $request, $shop_id, $product_category_id)
  {
    if (!Shop::find($shop_id)) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    if (!ProductCategory::find($product_category_id)) {
      return response()->json([
        'message' => 'Product category not found!',
        'status' => 404
      ], 404);
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $total_products_query = Product::query()
      ->whereNull('deleted_at')
      ->where('shop_id', $shop_id)
      ->where('product_category_id', $product_category_id);

    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);
    $offset = ($page_number - 1) * $num_of_page;

    $averageRatings = DB::table('rating_products')
      ->select('product_id', DB::raw('AVG(rating) as average_rating'))
      ->groupBy('product_id');

    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category'])
      ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
        $join->on('products.id', '=', 'average_ratings.product_id');
      })
      ->select('products.*', 'average_ratings.average_rating')
      ->orderBy('average_rating', 'desc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getLowestRatingProduct(Request $request)
  {
    $categories = [];

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $target = strtolower($request->query('target')) ?? 'all';

    if ($target === 'all') {
      $categories = DB::table('product_categories')
        ->pluck('id')
        ->toArray();
    } else {
      if ($target === 'dog' || $target === 'cat') {
        $categories = DB::table('product_categories')
          ->where('target', $target)
          ->pluck('id')
          ->toArray();

        if (empty($categories)) {
          return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => []
          ]);
        }
      }
    }

    $total_products_query = Product::query()->whereNull('deleted_at');

    if (!empty($categories)) {
      $total_products_query->whereIn('product_category_id', $categories);
    }

    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);
    $offset = ($page_number - 1) * $num_of_page;

    $averageRatings = DB::table('rating_products')
      ->select('product_id', DB::raw('AVG(rating) as average_rating'))
      ->groupBy('product_id');

    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category'])
      ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
        $join->on('products.id', '=', 'average_ratings.product_id');
      })
      ->select('products.*', 'average_ratings.average_rating')
      ->whereIn('product_category_id', $categories)
      ->orderBy('average_rating', 'asc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getLowestRatingProductByShop(Request $request, $shop_id = null)
  {
    // Kiểm tra sự tồn tại của shop
    if (!Shop::find($shop_id) && $shop_id != null) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    $categories = [];

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $is_deleted = $request->query('deleted', false);
    $target = strtolower($request->query('target')) ?? 'all';

    if ($target === 'all') {
      $categories = DB::table('product_categories')
        ->pluck('id')
        ->toArray();
    } else {
      if ($target === 'dog' || $target === 'cat') {
        $categories = DB::table('product_categories')
          ->where('target', $target)
          ->pluck('id')
          ->toArray();

        if (empty($categories)) {
          return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => []
          ]);
        }
      }
    }

    if ($is_deleted) {
      $total_products_query = Product::query()->onlyTrashed();
    } else {
      $total_products_query = Product::query()->whereNull('deleted_at');
    }

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $total_products_query->where('shop_id', $auth_shop_id);
    } else {
      $total_products_query->where('shop_id', $shop_id);
    }

    if (!empty($categories)) {
      $total_products_query->whereIn('product_category_id', $categories);
    }

    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);
    $offset = ($page_number - 1) * $num_of_page;

    $averageRatings = DB::table('rating_products')
      ->select('product_id', DB::raw('AVG(rating) as average_rating'))
      ->groupBy('product_id');

    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category'])
      ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
        $join->on('products.id', '=', 'average_ratings.product_id');
      })
      ->select('products.*', 'average_ratings.average_rating')
      ->whereIn('product_category_id', $categories)
      ->orderBy('average_rating', 'asc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getLowestRatingProductByCategory(Request $request, $product_category_id)
  {
    if (!ProductCategory::find($product_category_id)) {
      return response()->json([
        'message' => 'Product category not found!',
        'status' => 404
      ], 404);
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $total_products_query = Product::query()
      ->whereNull('deleted_at')
      ->where('product_category_id', $product_category_id);

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $total_products_query->where('shop_id', $auth_shop_id);
    }

    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);
    $offset = ($page_number - 1) * $num_of_page;

    $averageRatings = DB::table('rating_products')
      ->select('product_id', DB::raw('AVG(rating) as average_rating'))
      ->groupBy('product_id');

    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category'])
      ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
        $join->on('products.id', '=', 'average_ratings.product_id');
      })
      ->select('products.*', 'average_ratings.average_rating')
      ->orderBy('average_rating', 'asc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getLowestRatingProductWithShopAndCategory(Request $request, $shop_id, $product_category_id)
  {
    if (!Shop::find($shop_id)) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    if (!ProductCategory::find($product_category_id)) {
      return response()->json([
        'message' => 'Product category not found!',
        'status' => 404
      ], 404);
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $total_products_query = Product::query()
      ->whereNull('deleted_at')
      ->where('shop_id', $shop_id)
      ->where('product_category_id', $product_category_id);

    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);
    $offset = ($page_number - 1) * $num_of_page;

    $averageRatings = DB::table('rating_products')
      ->select('product_id', DB::raw('AVG(rating) as average_rating'))
      ->groupBy('product_id');

    $products = $total_products_query->with(['shop' => function ($query) {
      $query->withCount('ratingShop')->withAvg('ratingShop', 'rating');
    }, 'category'])
      ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
        $join->on('products.id', '=', 'average_ratings.product_id');
      })
      ->select('products.*', 'average_ratings.average_rating')
      ->orderBy('average_rating', 'asc')
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $shopRating = $product->shop->rating_shop_avg_rating ?? 0;
      $shopRatingCount = $product->shop->rating_shop_count ?? 0;

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'rating' => $shopRating,
          'rating_count' => $shopRatingCount,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function getSoldOutProducts(Request $request)
  {
    $categories = [];

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $target = strtolower($request->query('target')) ?? 'all';
    $is_deleted = $request->query('deleted', false);

    if ($target === 'all') {
      // Nếu không có field 'target' được gửi đến, lấy tất cả các danh mục
      $categories = DB::table('product_categories')
        ->pluck('id')
        ->toArray();
    } else {
      // Nếu 'target' là 'dog' hoặc 'cat' thì query dữ liệu từ bảng 'product_categories'
      if ($target === 'dog' || $target === 'cat') {
        $categories = DB::table('product_categories')
          ->where('target', $target)
          ->pluck('id')
          ->toArray();

        // Nếu không có danh mục nào phù hợp thì trả về mảng rỗng
        if (empty($categories)) {
          return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => []
          ]);
        }
      }
    }

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');

    $total_products_query = Product::query()
      ->where('quantity', 0)
      ->where('shop_id', $shop_id);

    if ($is_deleted) {
      $total_products_query->onlyTrashed();
    } else {
      $total_products_query->whereNull('deleted_at');
    }

    // Nếu có danh mục, thêm điều kiện whereIn
    if (!empty($categories)) {
      $total_products_query->whereIn('product_category_id', $categories);
    }

    // Tính tổng số trang
    $total_products = $total_products_query->count();
    $total_pages = ceil($total_products / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy dữ liệu sản phẩm dựa trên trang hiện tại và số lượng sản phẩm trên mỗi trang
    $products = $total_products_query->with(['shop', 'category']) // Sử dụng eager loading để lấy thông tin cửa hàng
      ->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $formatted_products[] = [
        'id' => $product->id,
        'name' => $product->name,
        'description' => $product->description,
        'price' => $product->price,
        'image' => $product->image,
        'quantity' => $product->quantity,
        'sold_quantity' => $product->sold_quantity,
        'status' => $product->status,
        'product_category_id' => $product->product_category_id,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        'shop' => [
          'id' => $product->shop->id,
          'name' => $product->shop->name,
          'email' => $product->shop->account->email,
          'avatar' => $product->shop->account->avatar,
          'description' => $product->shop->description,
          'image' => $product->shop->image,
          'phone' => $product->shop->phone,
          'address' => $product->shop->address,
          'website' => $product->shop->website,
          'fanpage' => $product->shop->fanpage,
          'work_time' => $product->shop->work_time,
          'establish_year' => $product->shop->establish_year,
          'account_id' => $product->shop->account->id,
          'created_at' => $product->shop->created_at,
          'updated_at' => $product->shop->updated_at,
        ],
        'category' => [
          'id' => $product->category->id,
          'name' => $product->category->name,
          'target' => $product->category->target,
          'type' => $product->category->type,
          'created_at' => $product->category->created_at,
          'updated_at' => $product->category->updated_at,
        ],
      ];
    }

    // Trả về JSON response
    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_products' => $total_products,
      'data' => $formatted_products,
    ]);
  }

  public function store(Request $request)
  {
    $data = $request->all();

    // Đảm bảo rằng sold_quantity là một số nguyên bằng 0 nếu không được cung cấp
    $data['sold_quantity'] = $data['sold_quantity'] ?? 0;

    $validatedData = $request->validate([
      'name' => 'required|string',
      'description' => 'required|string',
      'price' => 'required|numeric',
      'image' => 'nullable|string',
      'quantity' => 'required|numeric',
      'sold_quantity' => 'numeric',
      'status' => 'required|boolean',
      'product_category_id' => 'required|exists:product_categories,id',
      'shop_id' => 'required|exists:shops,id',
    ]);

    $product = Product::create($validatedData);

    return response()->json([
      'message' => 'Create product successfully!',
      'status' => 201,
      'data' => $product,
    ], 201);
  }

  public function update(Request $request, $id)
  {
    try {
      $product = Product::findOrFail($id);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Product not found!',
        'status' => 404
      ], 404);
    }

    $validatedData = $request->validate([
      'name' => 'required|string',
      'description' => 'required|string',
      'price' => 'required|numeric',
      'image' => 'nullable|string',
      'quantity' => 'required|numeric',
      'sold_quantity' => 'numeric',
      'status' => 'required|boolean',
      'product_category_id' => 'exists:product_categories,id',
    ]);

    $product->update($validatedData);

    return response()->json([
      'message' => 'Update product successfully!',
      'status' => 200,
      'data' => $product,
    ], 200);
  }

  public function destroy($id)
  {
    try {
      $product = Product::findOrFail($id);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Product not found!',
        'status' => 404
      ], 404);
    }

    $product->delete();

    return response()->json([
      'message' => 'Delete product successfully!',
      'status' => 200,
    ], 200);
  }

  public function restore($id)
  {
    try {
      $product = Product::onlyTrashed()->findOrFail($id);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Product not found!',
        'status' => 404
      ], 404);
    }

    $product->restore();

    return response()->json([
      'message' => 'Restore product successfully!',
      'status' => 200,
      'data' => $product,
    ], 200);
  }

  public function getDeletedProducts(Request $request)
  {
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $query = Product::query()->with(['shop.account', 'category']);

    $products = $query->onlyTrashed()->paginate($num_of_page, ['*'], 'page', $page_number);

    $formatted_products = [];
    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $formatted_products[] = [
        "id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "image" => $product->image,
        "quantity" => $product->quantity,
        "sold_quantity" => $product->sold_quantity,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        "status" => $product->status,
        "shop_id" => $product->shop_id,
        "product_category_id" => $product->product_category_id,
        "created_at" => $product->created_at,
        "updated_at" => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        "shop" => [
          "id" => $product->shop->id,
          "name" => $product->shop->name,
          "email" => $product->shop->account->email,
          "avatar" => $product->shop->account->avatar,
          "description" => $product->shop->description,
          "image" => $product->shop->image,
          "phone" => $product->shop->phone,
          "address" => $product->shop->address,
          "website" => $product->shop->website,
          "fanpage" => $product->shop->fanpage,
          "work_time" => $product->shop->work_time,
          "establish_year" => $product->shop->establish_year,
          "account_id" => $product->shop->account->id,
          "created_at" => $product->shop->created_at,
          "updated_at" => $product->shop->updated_at,
        ],
        "category" => [
          "id" => $product->category->id,
          "name" => $product->category->name,
          "target" => $product->category->target,
          "type" => $product->category->type,
          "created_at" => $product->category->created_at,
          "updated_at" => $product->category->updated_at,
        ]
      ];
    }

    return response()->json([
      'message' => 'Fetch deleted products successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $products->total(),
        'per_page' => $products->perPage(),
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'from' => $products->firstItem(),
        'to' => $products->lastItem(),
      ],
      'data' => $formatted_products,
    ], 200);
  }

  public function sortProductsByPrice(Request $request)
  {
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $is_deleted = $request->query('deleted', false);

    if ($is_deleted) {
      $query = Product::query()->onlyTrashed()->with(['shop.account', 'category']);
    } else {
      $query = Product::query()->whereNull('deleted_at')->with(['shop.account', 'category']);
    }

    $orderDirection = $request->query('order');

    if ($orderDirection == null) {
      $orderDirection = 'asc'; // Default Sort
    }

    if (!in_array($orderDirection, ['asc', 'desc'])) {
      return response()->json([
        'message' => 'Order direction must be "asc" or "desc".',
        'status' => 400,
      ], 400);
    }

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $products = $query->where('shop_id', $shop_id)
        ->orderBy('price', $orderDirection)
        ->paginate($num_of_page, ['*'], 'page', $page_number);
    } else {
      $products = $query
        ->orderBy('price', $orderDirection)
        ->paginate($num_of_page, ['*'], 'page', $page_number);
    }

    $formatted_products = [];

    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $formatted_products[] = [
        "id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "image" => $product->image,
        "quantity" => $product->quantity,
        "sold_quantity" => $product->sold_quantity,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        "status" => $product->status,
        "shop_id" => $product->shop_id,
        "product_category_id" => $product->product_category_id,
        "created_at" => $product->created_at,
        "updated_at" => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        "shop" => [
          "id" => $product->shop->id,
          "name" => $product->shop->name,
          "email" => $product->shop->account->email,
          "avatar" => $product->shop->account->avatar,
          "description" => $product->shop->description,
          "image" => $product->shop->image,
          "phone" => $product->shop->phone,
          "address" => $product->shop->address,
          "website" => $product->shop->website,
          "fanpage" => $product->shop->fanpage,
          "work_time" => $product->shop->work_time,
          "establish_year" => $product->shop->establish_year,
          "account_id" => $product->shop->account->id,
          "created_at" => $product->shop->created_at,
          "updated_at" => $product->shop->updated_at,
        ],
        "category" => [
          "id" => $product->category->id,
          "name" => $product->category->name,
          "target" => $product->category->target,
          "type" => $product->category->type,
          "created_at" => $product->category->created_at,
          "updated_at" => $product->category->updated_at,
        ]
      ];
    }

    return response()->json([
      'message' => 'Sort products successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $products->total(),
        'per_page' => $products->perPage(),
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'from' => $products->firstItem(),
        'to' => $products->lastItem(),
      ],
      'data' => $formatted_products,
    ], 200);
  }

  public function getProductsByRating(Request $request)
  {
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $rating = intval($request->query('rating', 5));
    $is_deleted = $request->query('deleted', false);

    // Xác định khoảng điểm rating dựa vào rating được chọn
    switch ($rating) {
      case 5:
        $minRating = 4.5;
        $maxRating = 5.0;
        break;
      case 4:
        $minRating = 3.5;
        $maxRating = 4.5;
        break;
      case 3:
        $minRating = 2.5;
        $maxRating = 3.5;
        break;
      case 2:
        $minRating = 1.5;
        $maxRating = 2.5;
        break;
      case 1:
        $minRating = 0.0;
        $maxRating = 1.5;
        break;
      default:
        return response()->json([
          'message' => 'Invalid rating value',
          'status' => 400,
        ], 400);
    }

    if ($is_deleted) {
      $query = Product::query()
        ->onlyTrashed()
        ->with(['shop.account', 'category'])
        ->whereHas('ratings', function ($q) use ($minRating, $maxRating) {
          $q->havingRaw('AVG(rating) > ? AND AVG(rating) <= ?', [$minRating, $maxRating]);
        });
    } else {
      $query = Product::query()
        ->whereNull('deleted_at')
        ->with(['shop.account', 'category'])
        ->whereHas('ratings', function ($q) use ($minRating, $maxRating) {
          $q->havingRaw('AVG(rating) > ? AND AVG(rating) <= ?', [$minRating, $maxRating]);
        });
    }

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      $products = $query->where('shop_id', $shop_id)
        ->paginate($num_of_page, ['*'], 'page', $page_number);
    } else {
      $products = $query->paginate($num_of_page, ['*'], 'page', $page_number);
    }

    $formatted_products = [];

    foreach ($products as $product) {
      $ratingData = $product->calculateProductRating();

      $formatted_products[] = [
        "id" => $product->id,
        "name" => $product->name,
        "description" => $product->description,
        "price" => $product->price,
        "image" => $product->image,
        "quantity" => $product->quantity,
        "sold_quantity" => $product->sold_quantity,
        "rating" => $ratingData['average'],
        "rating_count" => $ratingData['count'],
        "status" => $product->status,
        "shop_id" => $product->shop_id,
        "product_category_id" => $product->product_category_id,
        "created_at" => $product->created_at,
        "updated_at" => $product->updated_at,
        "deleted_at" => $product->deleted_at,
        "shop" => [
          "id" => $product->shop->id,
          "name" => $product->shop->name,
          "email" => $product->shop->account->email,
          "avatar" => $product->shop->account->avatar,
          "description" => $product->shop->description,
          "image" => $product->shop->image,
          "phone" => $product->shop->phone,
          "address" => $product->shop->address,
          "website" => $product->shop->website,
          "fanpage" => $product->shop->fanpage,
          "work_time" => $product->shop->work_time,
          "establish_year" => $product->shop->establish_year,
          "account_id" => $product->shop->account->id,
          "created_at" => $product->shop->created_at,
          "updated_at" => $product->shop->updated_at,
        ],
        "category" => [
          "id" => $product->category->id,
          "name" => $product->category->name,
          "target" => $product->category->target,
          "type" => $product->category->type,
          "created_at" => $product->category->created_at,
          "updated_at" => $product->category->updated_at,
        ]
      ];
    }

    return response()->json([
      'message' => 'Get products by rating successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $products->total(),
        'per_page' => $products->perPage(),
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'from' => $products->firstItem(),
        'to' => $products->lastItem(),
      ],
      'data' => $formatted_products,
    ], 200);
  }
}
