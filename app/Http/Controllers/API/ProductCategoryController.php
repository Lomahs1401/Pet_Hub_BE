<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{
  public function getProductCategories()
  {
    $categories = ProductCategory::all();

    // Nhóm các danh mục theo 'type' và 'target'
    $groupedCategories = $categories->groupBy(function ($item) {
      return $item->type . ' (' . ucfirst($item->target) . ')';
    })->map(function ($group) {
      return $group->map(function ($item) {
        return [
          'id' => $item->id,
          'name' => $item->name,
        ];
      })->values();
    });

    // Chuyển đổi từ collection sang mảng
    $groupedCategoriesArray = $groupedCategories->map(function ($group, $key) {
      return [
        'group' => $key,
        'categories' => $group,
      ];
    })->values()->toArray();

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $groupedCategoriesArray,
    ], 200);
  }

  public function getProductCountsByCategory($shop_id = null)
  {
    if (!Shop::find($shop_id) && $shop_id != null) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      // Lấy danh sách các category cùng với số lượng sản phẩm của mỗi category cho shop_id
      $categories = ProductCategory::withCount(['products' => function ($query) use ($auth_shop_id) {
        $query->where('shop_id', $auth_shop_id);
      }])->get();
    } else {
      // Lấy danh sách các category cùng với số lượng sản phẩm của mỗi category cho shop_id
      $categories = ProductCategory::withCount(['products' => function ($query) use ($shop_id) {
        $query->where('shop_id', $shop_id);
      }])->get();
    }

    $groupedByTarget = [
      'dog' => [],
      'cat' => []
    ];

    // Nhóm các danh mục theo target
    foreach ($categories as $category) {
      $groupedByTarget[$category->target][] = [
        'category_name' => $category->name,
        'product_count' => $category->products_count,
        'type' => $category->type,
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $groupedByTarget,
    ], 200);
  }

  public function getProductCountsByCategoryType($shop_id = null)
  {
    if (!Shop::find($shop_id) && $shop_id != null) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    // CHECK FOR ROLE_SHOP
    $user = auth()->user();
    $role_user = DB::table('roles')->where('id', '=', $user->role_id)->value('role_name');
    if ($role_user === 'ROLE_SHOP') {
      $auth_shop_id = DB::table('shops')->where('account_id', '=', $user->id)->value('id');
      // Lấy danh sách các category cùng với số lượng sản phẩm của mỗi category cho shop_id
      $categories = ProductCategory::withCount(['products' => function ($query) use ($auth_shop_id) {
        $query->where('shop_id', $auth_shop_id);
      }])->get();
    } else {
      // Lấy danh sách các category cùng với số lượng sản phẩm của mỗi category cho shop_id
      $categories = ProductCategory::withCount(['products' => function ($query) use ($shop_id) {
        $query->where('shop_id', $shop_id);
      }])->get();
    }

    // Tạo mảng để chứa dữ liệu đã nhóm
    $groupedByTarget = [
      'dog' => [],
      'cat' => []
    ];

    // Tạo mảng để chứa số lượng sản phẩm theo type và target
    $productCountsByType = [];

    // Lặp qua các danh mục và nhóm số lượng sản phẩm theo type và target
    foreach ($categories as $category) {
      $type = $category->type;
      $target = $category->target;

      if (!isset($productCountsByType[$target])) {
        $productCountsByType[$target] = [];
      }

      if (!isset($productCountsByType[$target][$type])) {
        $productCountsByType[$target][$type] = 0;
      }

      $productCountsByType[$target][$type] += $category->products_count;
    }

    foreach ($productCountsByType as $target => $types) {
      foreach ($types as $type => $count) {
        $groupedByTarget[$target][] = [
          'type' => $type,
          'product_count' => $count,
        ];
      }
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $groupedByTarget,
    ], 200);
  }

  public function getDistinctCategoryTypes()
  {
    $types = ProductCategory::distinct()->pluck('type');

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $types,
    ], 200);
  }

  public function getDistinctCategoryTypesByTarget($target)
  {
    $types = ProductCategory::where('target', $target)->distinct()->pluck('type');

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $types,
    ], 200);
  }
}
