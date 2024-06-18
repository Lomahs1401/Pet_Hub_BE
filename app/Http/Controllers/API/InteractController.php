<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Interact;
use Illuminate\Http\Request;

class InteractController extends Controller
{
  public function interactBlog(Request $request, $blog_id)
  {
    $account_id = auth()->user()->id;

    $type = strtolower($request->query('type'));

    // Kiểm tra xem người dùng đã có record tương tác với blog này chưa
    $interaction = Interact::withTrashed()
      ->where('account_id', $account_id)
      ->where('target_label', 'blogs')
      ->where('target_id', $blog_id)
      ->first();

    if ($interaction) {
      // Nếu người dùng ấn "Like" lần nữa
      if ($type === 'like') {
        if ($interaction->deleted_at) {
          // Record đã bị xóa, phục hồi và cập nhật target_type
          $interaction->restore();
          $interaction->update(['target_type' => 'like']);
        } else {
          // Xóa record
          if ($interaction->target_type === 'dislike') {
            // Thay đổi target_type thành like
            $interaction->update(['target_type' => 'like']);
          } else {
            // Xóa record
            $interaction->delete();
          }
        }
      } elseif ($type === 'dislike') {
        if ($interaction->deleted_at) {
          // Record đã bị xóa, phục hồi và cập nhật target_type
          $interaction->restore();
          $interaction->update(['target_type' => 'dislike']);
        } else {
          if ($interaction->target_type === 'like') {
            // Thay đổi target_type thành dislike
            $interaction->update(['target_type' => 'dislike']);
          } else {
            // Xóa record
            $interaction->delete();
          }
        }
      }
    } else {
      // Tạo record mới nếu chưa có
      $interaction = Interact::create([
        'account_id' => $account_id,
        'target_label' => 'blogs',
        'target_id' => $blog_id,
        'target_type' => $request->type,
      ]);
    }

    // Đếm số lượng like và dislike sau khi tương tác
    $likesCount = Interact::where('target_label', 'blogs')
      ->where('target_id', $blog_id)
      ->where('target_type', 'like')
      ->count();

    $dislikesCount = Interact::where('target_label', 'blogs')
      ->where('target_id', $blog_id)
      ->where('target_type', 'dislike')
      ->count();

    return response()->json([
      'message' => 'Interaction processed successfully!',
      'status' => 200,
      'likes_count' => $likesCount,
      'dislikes_count' => $dislikesCount,
    ]);
  }

  public function interactComment(Request $request, $comment_id)
  {
    $account_id = auth()->user()->id;

    $type = strtolower($request->query('type'));

    // Kiểm tra xem người dùng đã có record tương tác với comment này chưa
    $interaction = Interact::withTrashed()
      ->where('account_id', $account_id)
      ->where('target_label', 'comments')
      ->where('target_id', $comment_id)
      ->first();

    if ($interaction) {
      // Nếu người dùng ấn "Like" lần nữa
      if ($type === 'like') {
        if ($interaction->deleted_at) {
          // Record đã bị xóa, phục hồi và cập nhật target_type
          $interaction->restore();
          $interaction->update(['target_type' => 'like']);
        } else {
          if ($interaction->target_type === 'dislike') {
            // Thay đổi target_type thành like
            $interaction->update(['target_type' => 'like']);
          } else {
            // Xóa record
            $interaction->delete();
          }
        }
      } elseif ($type === 'dislike') {
        if ($interaction->deleted_at) {
          // Record đã bị xóa, phục hồi và cập nhật target_type
          $interaction->restore();
          $interaction->update(['target_type' => 'dislike']);
        } else {
          if ($interaction->target_type === 'like') {
            // Thay đổi target_type thành dislike
            $interaction->update(['target_type' => 'dislike']);
          } else {
            // Xóa record
            $interaction->delete();
          }
        }
      }
    } else {
      // Tạo record mới nếu chưa có
      $interaction = Interact::create([
        'account_id' => $account_id,
        'target_label' => 'comments',
        'target_id' => $comment_id,
        'target_type' => $type,
      ]);
    }

    // Đếm số lượng like và dislike sau khi tương tác
    $likesCount = Interact::where('target_label', 'comments')
      ->where('target_id', $comment_id)
      ->where('target_type', 'like')
      ->count();

    $dislikesCount = Interact::where('target_label', 'comments')
      ->where('target_id', $comment_id)
      ->where('target_type', 'dislike')
      ->count();

    return response()->json([
      'message' => 'Interaction processed successfully!',
      'status' => 200,
      'likes_count' => $likesCount,
      'dislikes_count' => $dislikesCount,
    ]);
  }
}
