<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::all();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $categories,
        ], 200);
    }

    // Lấy số lượng các blog category hiện có
    public function countCategories()
    {
        $count = BlogCategory::count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $count,
        ], 200);
    }

    // Lấy số lượng từng loại bài blog thuộc từng category
    public function countBlogsByCategory()
    {
        $categories = BlogCategory::withCount('blogs')->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $categories,
        ], 200);
    }

    // Thêm mới blog category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:blog_categories',
        ]);

        $category = BlogCategory::create([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'message' => 'Blog category created successfully!',
            'status' => 201,
            'data' => $category,
        ], 201);
    }

    // Update blog category
    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:blog_categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'message' => 'Blog category updated successfully!',
            'status' => 200,
            'data' => $category,
        ], 200);
    }

    // Xoá blog category
    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Blog category deleted successfully!',
            'status' => 200,
        ], 200);
    }
}
