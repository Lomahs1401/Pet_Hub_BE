<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
  public function createComment(Request $request)
  {
    $request->validate([
      'text' => 'required|string|max:500',
      'blog_id' => 'required|exists:blogs,id',
      'parent_comments_id' => 'nullable|exists:comments,id',
    ]);

    $blog = Blog::find($request->blog_id);

    if (!$blog) {
      return response()->json([
        'message' => 'Blog not found!',
        'status' => 404,
      ], 404);
    }

    $account_id = auth()->user()->id;

    $comment = Comment::create([
      'text' => $request->text,
      'account_id' => $account_id,
      'blog_id' => $request->blog_id,
      'parent_comments_id' => $request->parent_comments_id,
    ]);

    $totalComments = Comment::where('blog_id', $request->blog_id)->count();

    return response()->json([
      'message' => 'Comment created successfully!',
      'status' => 201,
      'data' => [
        'total_comments' => $totalComments,
        'comment' => $comment,
      ],
    ]);
  }

  public function updateComment(Request $request, $comment_id)
  {
    $comment = Comment::find($comment_id);

    if (!$comment) {
      return response()->json([
        'message' => 'Comment not found!',
        'status' => 404,
      ], 404);
    }

    $account_id = auth()->user()->id;

    // Kiểm tra quyền sở hữu comment
    if ($comment->account_id !== $account_id) {
      return response()->json([
        'message' => 'Unauthorized action!',
        'status' => 403,
      ], 403);
    }

    $validatedData = $request->validate([
      'text' => 'sometimes|string|max:500',
    ]);

    $comment->update($validatedData);

    return response()->json([
      'message' => 'Comment updated successfully!',
      'status' => 200,
      'data' => $comment,
    ]);
  }

  public function deleteComment($comment_id)
  {
    $comment = Comment::find($comment_id);

    if (!$comment) {
      return response()->json([
        'message' => 'Comment not found!',
        'status' => 404,
      ], 404);
    }

    // Kiểm tra quyền sở hữu comment
    $accountId = auth()->user()->id;
    if ($comment->account_id !== $accountId) {
      return response()->json([
        'message' => 'Unauthorized action!',
        'status' => 403,
      ], 403);
    }

    $comment->delete();

    $totalComments = Comment::where('blog_id', $comment->blog_id)->count();

    return response()->json([
      'message' => 'Comment deleted successfully!',
      'status' => 200,
      'total_comments' => $totalComments,
    ]);
  }
}
