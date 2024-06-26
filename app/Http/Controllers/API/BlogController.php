<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
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
    $result = $blogs->map(function ($blog) use ($accountId) {
      $commentsCount = Comment::where('blog_id', $blog->id)->count();
      $likesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'like')
        ->count();
      $dislikesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'dislike')
        ->count();
      // Kiểm tra người dùng hiện tại đã tương tác với bài viết này chưa và loại tương tác
      $userInteraction = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('account_id', $accountId)
        ->first();

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
        'interaction_type' => $userInteraction ? $userInteraction->target_type : null,
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
    $result = $blogs->map(function ($blog) use ($accountId) {
      $commentsCount = Comment::where('blog_id', $blog->id)->count();
      $likesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'like')
        ->count();
      $dislikesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'dislike')
        ->count();
      // Kiểm tra người dùng hiện tại đã tương tác với bài viết này chưa và loại tương tác
      $userInteraction = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('account_id', $accountId)
        ->first();

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
        'interaction_type' => $userInteraction ? $userInteraction->target_type : null,
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

    // Lấy danh sách comments của blog và sắp xếp theo chiều từ mới nhất đến cũ nhất
    $comments = Comment::where('blog_id', $blog_id)
      ->whereNull('parent_comments_id')
      ->with(['account', 'subComments.account'])
      ->orderBy('created_at', 'desc')
      ->get();

    // Đếm tổng số comments
    $totalComments = Comment::where('blog_id', $blog_id)->count();

    // Đếm số lượng like và dislike cho blog
    $likesCount = Interact::where('target_label', 'blogs')
      ->where('target_id', $blog_id)
      ->where('target_type', 'like')
      ->count();

    $dislikesCount = Interact::where('target_label', 'blogs')
      ->where('target_id', $blog_id)
      ->where('target_type', 'dislike')
      ->count();

    // Lấy id người dùng hiện tại
    $account_id = auth()->user()->id;

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
        'likes_count' => $likesCount,
        'dislikes_count' => $dislikesCount,
        'created_at' => $blog->created_at,
        'updated_at' => $blog->updated_at,
      ],
      'total_comments' => $totalComments,
      'comments' => $comments->map(function ($comment) use ($account_id) {
        // Đếm số lượng like và dislike cho comment
        $commentLikesCount = Interact::where('target_label', 'comments')
          ->where('target_id', $comment->id)
          ->where('target_type', 'like')
          ->count();

        $commentDislikesCount = Interact::where('target_label', 'comments')
          ->where('target_id', $comment->id)
          ->where('target_type', 'dislike')
          ->count();

        // Kiểm tra người dùng hiện tại đã tương tác với comment này chưa
        $userInteraction = Interact::where('target_label', 'comments')
          ->where('target_id', $comment->id)
          ->where('account_id', $account_id)
          ->first();

        return [
          'id' => $comment->id,
          'text' => $comment->text,
          'account_id' => $comment->account_id,
          'email' => $comment->account ? $comment->account->email : null,
          'username' => $comment->account ? $comment->account->username : null,
          'avatar' => $comment->account ? $comment->account->avatar : null,
          'likes_count' => $commentLikesCount,
          'dislikes_count' => $commentDislikesCount,
          'interaction_type' => $userInteraction ? $userInteraction->target_type : null,
          'created_at' => $comment->created_at,
          'updated_at' => $comment->updated_at,
          'sub_comments' => $comment->subComments->sortByDesc('created_at')->map(function ($subComment) use ($account_id) {
            // Đếm số lượng like và dislike cho sub-comment
            $subCommentLikesCount = Interact::where('target_label', 'comments')
              ->where('target_id', $subComment->id)
              ->where('target_type', 'like')
              ->count();

            $subCommentDislikesCount = Interact::where('target_label', 'comments')
              ->where('target_id', $subComment->id)
              ->where('target_type', 'dislike')
              ->count();

            // Kiểm tra người dùng hiện tại đã tương tác với sub-comment này chưa
            $userSubInteraction = Interact::where('target_label', 'comments')
              ->where('target_id', $subComment->id)
              ->where('account_id', $account_id)
              ->first();

            return [
              'id' => $subComment->id,
              'text' => $subComment->text,
              'account_id' => $subComment->account_id,
              'email' => $subComment->account ? $subComment->account->email : null,
              'username' => $subComment->account ? $subComment->account->username : null,
              'avatar' => $subComment->account ? $subComment->account->avatar : null,
              'likes_count' => $subCommentLikesCount,
              'dislikes_count' => $subCommentDislikesCount,
              'interaction_type' => $userSubInteraction ? $userSubInteraction->target_type : null,
              'created_at' => $subComment->created_at,
              'updated_at' => $subComment->updated_at,
            ];
          })->values(), // Đảm bảo rằng sub_comments luôn được trả về dưới dạng mảng
        ];
      })->values(), // Đảm bảo rằng comments luôn được trả về dưới dạng mảng
    ];

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $result,
    ]);
  }

  public function searchBlogByUsername(Request $request)
  {
    $accountId = auth()->user()->id;

    // Lấy tham số page_number và num_of_page từ request
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $username = $request->query('username', '');

    // Tìm tài khoản theo username
    $accounts = Account::where('username', 'like', '%' . $username . '%')->pluck('id');

    if ($accounts->isEmpty()) {
      return response()->json([
        'message' => 'Username not found!',
        'status' => 404,
      ], 404);
    }

    // Tìm kiếm blog theo account_id với phân trang
    $blogs = Blog::whereIn('account_id', $accounts)
      ->offset(($page_number - 1) * $num_of_page)
      ->limit($num_of_page)
      ->orderBy('created_at', 'desc')
      ->get();

    // Tính tổng số blogs và tổng số trang
    $total_blogs = Blog::whereIn('account_id', $accounts)->count();
    $total_pages = ceil($total_blogs / $num_of_page);

    // Tạo danh sách kết quả
    $result = $blogs->map(function ($blog) use ($accountId) {
      $commentsCount = Comment::where('blog_id', $blog->id)->count();
      $likesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'like')
        ->count();
      $dislikesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'dislike')
        ->count();
      $userInteraction = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('account_id', $accountId)
        ->first();

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
        'interaction_type' => $userInteraction ? $userInteraction->target_type : null,
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

  public function searchBlogByTitle(Request $request)
  {
    $accountId = auth()->user()->id;

    // Lấy tham số page_number và num_of_page từ request
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $title = $request->query('title', '');

    // Tìm kiếm blog theo title sử dụng tìm kiếm gần đúng với phân trang
    $blogs = Blog::where('title', 'like', '%' . $title . '%')
      ->offset(($page_number - 1) * $num_of_page)
      ->limit($num_of_page)
      ->orderBy('created_at', 'desc')
      ->get();

    // Tính tổng số blogs và tổng số trang
    $total_blogs = Blog::where('title', 'like', '%' . $title . '%')->count();
    $total_pages = ceil($total_blogs / $num_of_page);

    // Tạo danh sách kết quả
    $result = $blogs->map(function ($blog) use ($accountId) {
      $commentsCount = Comment::where('blog_id', $blog->id)->count();
      $likesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'like')
        ->count();
      $dislikesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'dislike')
        ->count();
      $userInteraction = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('account_id', $accountId)
        ->first();

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
        'interaction_type' => $userInteraction ? $userInteraction->target_type : null,
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


  public function searchProfile(Request $request)
  {
    $username = $request->input('username', '');
    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Tìm kiếm người dùng theo username (tìm kiếm gần đúng)
    $query = Account::where('username', 'like', '%' . $username . '%');

    // Lấy tổng số người dùng tìm thấy
    $total_users = $query->count();

    // Tính toán offset cho phân trang
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy danh sách người dùng với phân trang
    $accounts = $query->offset($offset)
      ->limit($num_of_page)
      ->get();

    if ($accounts->isEmpty()) {
      return response()->json(['message' => 'No accounts found.'], 404);
    }

    // Trả về thông tin cơ bản của người dùng
    $result = $accounts->map(function ($account) {
      return [
        'account_id' => $account->id,
        'username' => $account->username,
        'email' => $account->email,
        'avatar' => $account->avatar,
      ];
    });

    // Tính tổng số trang
    $total_pages = ceil($total_users / $num_of_page);

    return response()->json([
      'message' => 'Users found.',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_users' => $total_users,
      'data' => $result,
    ], 200);
  }

  public function showProfile($account_id)
  {
    // Tìm kiếm người dùng theo account_id
    $user = Account::find($account_id);

    if (!$user) {
      return response()->json(['message' => 'User not found.'], 404);
    }

    // Lấy các bài blog của người dùng
    $blogs = Blog::where('account_id', $account_id)->get();

    // Thêm thông tin likes, dislikes, comments_count, interaction_type vào từng blog
    $blogs_data = $blogs->map(function ($blog) use ($account_id) {
      $commentsCount = Comment::where('blog_id', $blog->id)->count();
      $likesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'like')
        ->count();
      $dislikesCount = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('target_type', 'dislike')
        ->count();
      $userInteraction = Interact::where('target_label', 'blogs')
        ->where('target_id', $blog->id)
        ->where('account_id', $account_id)
        ->first();

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
        'interaction_type' => $userInteraction ? $userInteraction->target_type : null,
        'created_at' => $blog->created_at,
        'updated_at' => $blog->updated_at,
      ];
    });

    // Trả về thông tin chi tiết của người dùng và các bài blog
    return response()->json([
      'message' => 'User profile retrieved successfully.',
      'data' => [
        'user_id' => $user->id,
        'username' => $user->username,
        'account_id' => $user->id,
        'email' => $user->email,
        'blogs' => $blogs_data
      ]
    ], 200);
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
