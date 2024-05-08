<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductCategory::query();

        if ($request->has('sort')) {
            if ($request->sort == 'asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort == 'desc') {
                $query->orderBy('price', 'desc');
            }
        }
    
        $products = $query->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $products,
        ], 200);
    }
}
