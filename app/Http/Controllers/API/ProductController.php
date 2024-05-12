<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
    
        $products = $query->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $products,
        ], 200);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found!',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $product,
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

        $products = Product::where('shop_id', $shop_id)->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $products,
        ], 200);
    }

    public function getNumberOfProductByShopId($shop_id)
    {
        if (!Shop::find($shop_id)) {
            return response()->json([
                'message' => 'Shop not found!',
                'status' => 404
            ], 404);
        }

        $number_of_products = Product::where('shop_id', $shop_id)->count();

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
                'message' => 'Category not found!',
                'status' => 404
            ], 404);
        }

        $products = Product::where('product_category_id', $product_category_id)->get();

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

        $number_of_products = Product::where('product_category_id', $product_category_id)->count();

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

        $number_of_products = Product::where('shop_id', $shop_id)
            ->where('product_category_id', $product_category_id)
            ->with('shop', 'category')
            ->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $number_of_products,
        ], 200);
    }

    public function getNumberOfProductWithShopAndCategory($shop_id, $product_category_id)
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

        $number_of_products = Product::where('shop_id', $shop_id)
            ->where('product_category_id', $product_category_id)
            ->count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $number_of_products,
        ], 200);
    }

    public function getNumberOfShopSellingByCategory($product_category_id)
    {
        $shops = Product::whereHas('category', function ($query) use ($product_category_id) {
            $query->where('id', $product_category_id);
        })
            ->distinct('shop_id')
            ->pluck('shop_id');

        $number_of_shops = $shops->count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $number_of_shops,
        ], 200);
    }

    public function paging(Request $request) {
        $categories = [];

        $page_number = $request->query('page_number') ?? 1;
        $num_of_page = $request->query('num_of_page') ?? 10;
        $target = $request->query('target') ?? 'all';

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
        $total_products_query = DB::table('products');

        // Nếu có danh mục, thêm điều kiện whereIn
        if (!empty($categories)) {
            $total_products_query->whereIn('product_category_id', $categories);
        }

        $total_products = $total_products_query->count();

        // Tính toán offset
        $offset = ($page_number - 1) * $num_of_page;

        // Lấy dữ liệu sản phẩm dựa trên trang hiện tại và số lượng sản phẩm trên mỗi trang
        $products = Product::with('shop') // Sử dụng eager loading để lấy thông tin cửa hàng
            ->whereIn('product_category_id', $categories)
            ->offset($offset)
            ->limit($num_of_page)
            ->get();

        $formatted_products = [];
        foreach ($products as $product) {
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
                'rating' => $product->calculateProductRating($product->id),
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
                'shop' => [
                    'id' => $product->shop->id,
                    'name' => $product->shop->name,
                    'email' => $product->shop->email,
                    'description' => $product->shop->description,
                    'image' => $product->shop->image,
                    'phone' => $product->shop->phone,
                    'address' => $product->shop->address,
                    'website' => $product->shop->website,
                    'fanpage' => $product->shop->fanpage,
                    'work_time' => $product->shop->work_time,
                    'establish_year' => $product->shop->establish_year,
                    'created_at' => $product->shop->created_at,
                    'updated_at' => $product->shop->updated_at,
                ],
            ];
        }

        // Trả về JSON response
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $formatted_products,
            'page_number' => $page_number,
            'num_of_page' => $num_of_page,
            'total_products' => $total_products,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|string|url',
            'status' => 'required|boolean',
            'product_category_id' => 'required|exists:product_categories,id',
        ]);

        $product = Product::create($data);

        return response()->json([
            'message' => 'Create product successfully!',
            'status' => 201,
            'data' => $product,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'string',
            'description' => 'string',
            'price' => 'numeric',
            'image' => 'nullable|string|url',
            'status' => 'boolean',
            'product_category_id' => 'exists:product_categories,id',
        ]);
    
        $product->update($request->all());

        return response()->json([
            'message' => 'Update product successfully!',
            'status' => 200,
            'data' => $product,
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json([
            'message' => 'Delete product successfully!',
            'status' => 204,
        ], 204);
    }

    public function sortProductsByPrice(Request $request, $order)
    {
        $orderDirection = $order === 'desc' ? 'desc' : 'asc';
        $products = Product::orderBy('price', $orderDirection)->get();
        return response()->json($products);
    }
}
