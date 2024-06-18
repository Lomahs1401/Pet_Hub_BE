<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Models\Interact;
use Illuminate\Http\Request;

class BlogController extends Controller
{
  public function getBlogs(Request $request)
  {
    $accountId = auth()->user()->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Lấy số lượng blogs
    $blog_query = Blog::query()
      ->with('account')
      ->where('account_id', '!=', $accountId)
      ->whereNull('deleted_at');

    $total_blogs = $blog_query->count();
    $total_pages = ceil($total_blogs / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy danh sách các bài viết không thuộc về account đang đăng nhập, sắp xếp theo thời gian mới nhất đến cũ nhất
    $blogs = $blog_query->offset($offset)
      ->limit($num_of_page)
      ->orderBy('created_at', 'desc')
      ->get();

    // Tạo danh sách kết quả
    $result = $blogs->map(function ($blog) {
      $commentsCount = Comment::where('blog_id', $blog->id)->count();
      $likesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'like')
        ->count();
      $dislikesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'dislike')
        ->count();

      return [
        'id' => $blog->id,
        'title' => $blog->title,
        'text' => $blog->text,
        'image' => $blog->image,
        'account_id' => $blog->account_id,
        'email' => $blog->account->email,
        'username' => $blog->account->username,
        'avatar' => $blog->account->avatar,
        'comments_count' => $commentsCount,
        'likes_count' => $likesCount,
        'dislikes_count' => $dislikesCount,
        'created_at' => $blog->created_at,
        'updated_at' => $blog->updated_at,
      ];
    });

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_blogs' => $total_blogs,
      'data' => $result,
    ]);
  }

  public function getMyBlogs(Request $request)
  {
    $accountId = auth()->user()->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Lấy số lượng blogs
    $blog_query = Blog::query()
      ->with('account')
      ->where('account_id', '=', $accountId)
      ->whereNull('deleted_at');

    $total_blogs = $blog_query->count();
    $total_pages = ceil($total_blogs / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    $blogs = $blog_query->offset($offset)
      ->limit($num_of_page)
      ->orderBy('created_at', 'desc')
      ->get();

    // Tạo danh sách kết quả
    $result = $blogs->map(function ($blog) {
      $commentsCount = Comment::where('blog_id', $blog->id)->count();
      $likesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'like')
        ->count();
      $dislikesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'dislike')
        ->count();

      return [
        'id' => $blog->id,
        'title' => $blog->title,
        'text' => $blog->text,
        'image' => $blog->image,
        'account_id' => $blog->account_id,
        'email' => $blog->account->email,
        'username' => $blog->account->username,
        'avatar' => $blog->account->avatar,
        'comments_count' => $commentsCount,
        'likes_count' => $likesCount,
        'dislikes_count' => $dislikesCount,
        'created_at' => $blog->created_at,
        'updated_at' => $blog->updated_at,
      ];
    });

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_blogs' => $total_blogs,
      'data' => $result,
    ]);
  }

  public function getBlogDetail($blog_id)
  {
    // Tìm bài blog theo id
    $blog = Blog::with('account')->find($blog_id);

    // Kiểm tra nếu blog không tồn tại
    if (!$blog) {
      return response()->json([
        'message' => 'Blog not found!',
        'status' => 404,
      ], 404);
    }

    // Lấy danh sách comments của blog
    $comments = Comment::where('blog_id', $blog_id)
      ->whereNull('parent_comments_id')
      ->with('account', 'subComments.account')
      ->get();

    // Đếm tổng số comments
    $totalComments = Comment::where('blog_id', $blog_id)->count();

    // Chuẩn bị dữ liệu trả về
    $result = [
      'blog' => [
        'id' => $blog->id,
        'title' => $blog->title,
        'text' => $blog->text,
        'image' => $blog->image,
        'account_id' => $blog->account_id,
        'email' => $blog->account ? $blog->account->email : null,
        'username' => $blog->account ? $blog->account->username : null,
        'avatar' => $blog->account ? $blog->account->avatar : null,
        'created_at' => $blog->created_at,
        'updated_at' => $blog->updated_at,
      ],
      'total_comments' => $totalComments,
      'comments' => $comments->map(function ($comment) {
        return [
          'id' => $comment->id,
          'text' => $comment->text,
          'account_id' => $comment->account_id,
          'email' => $comment->account ? $comment->account->email : null,
          'username' => $comment->account ? $comment->account->username : null,
          'avatar' => $comment->account ? $comment->account->avatar : null,
          'created_at' => $comment->created_at,
          'updated_at' => $comment->updated_at,
          'sub_comments' => $comment->subComments->map(function ($subComment) {
            return [
              'id' => $subComment->id,
              'text' => $subComment->text,
              'account_id' => $subComment->account_id,
              'email' => $subComment->account ? $subComment->account->email : null,
              'username' => $subComment->account ? $subComment->account->username : null,
              'avatar' => $subComment->account ? $subComment->account->avatar : null,
              'created_at' => $subComment->created_at,
              'updated_at' => $subComment->updated_at,
            ];
          }),
        ];
      }),
    ];

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $result,
    ]);
  }

  public function createBlog(Request $request)
  {
    $account_id = auth()->user()->id;

    $validatedData = $request->validate([
      'title' => 'required|string|max:255',
      'text' => 'required|string',
      'image' => 'nullable|string',
    ]);

    $validatedData['account_id'] = $account_id;

    $blog = Blog::create($validatedData);

    return response()->json([
      'message' => 'Blog created successfully!',
      'status' => 201,
      'data' => $blog,
    ], 201);
  }

  public function updateBlog(Request $request, $blog_id)
  {
    $blog = Blog::find($blog_id);

    if (!$blog) {
      return response()->json([
        'message' => 'Blog not found!',
        'status' => 404,
      ], 404);
    }

    // Kiểm tra quyền sở hữu blog
    $accountId = auth()->user()->id;
    if ($blog->account_id !== $accountId) {
      return response()->json([
        'message' => 'Unauthorized action!',
        'status' => 403,
      ], 403);
    }

    $validatedData = $request->validate([
      'title' => 'sometimes|string|max:255',
      'text' => 'sometimes|string',
      'image' => 'nullable|string',
    ]);

    $blog->update($validatedData);

    return response()->json([
      'message' => 'Blog updated successfully!',
      'status' => 200,
      'data' => $blog,
    ]);
  }

  public function deleteBlog($blog_id)
  {
    $blog = Blog::find($blog_id);

    if (!$blog) {
      return response()->json([
        'message' => 'Blog not found!',
        'status' => 404,
      ], 404);
    }

    // Kiểm tra quyền sở hữu blog
    $accountId = auth()->user()->id;
    if ($blog->account_id !== $accountId) {
      return response()->json([
        'message' => 'Unauthorized action!',
        'status' => 403,
      ], 403);
    }

    $blog->delete();

    return response()->json([
      'message' => 'Blog deleted successfully!',
      'status' => 200,
    ]);
  }
}
