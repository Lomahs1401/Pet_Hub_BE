<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shop;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // if ($request->has('sort')) {
        //     if ($request->sort == 'asc') {
        //         $query->orderBy('price', 'asc');
        //     } elseif ($request->sort == 'desc') {
        //         $query->orderBy('price', 'desc');
        //     }
        // }
    
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

    public function getProductByShopId($shop_id)
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

    public function getProductByCategoryId($product_category_id)
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

    public function getProductWithShopAndCategory($shop_id, $product_category_id)
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

    public function getNumberOfShopSellingCategory($product_category_id)
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
