<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ResetCodeEmail;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AccountController extends Controller
{
  public function requestSendResetCode(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'message' => 'Invalid data',
          'errors' => $validator->errors()
        ],
        400
      );
    }

    // Kiểm tra email có tồn tại trong cơ sở dữ liệu
    $account = Account::where('email', $request->email)->first();

    if (!$account) {
      return response()->json(['message' => 'Email not founded'], 404);
    }

    $code = Str::random(6);
    $account->reset_code = $code;
    $account->reset_code_expires_at = Carbon::now()->addMinutes(5);
    $account->reset_code_attempts = 0;
    $account->save();

    // Gửi email chứa mã code đến người dùng
    Mail::to($account->email)->send(new ResetCodeEmail($account));

    return response()->json(['message' => 'Reset code has been sent to your email']);
  }

  public function resetVerifyCode(Request $request, $email)
  {
    $validator = Validator::make($request->all(), [
      'code' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'message' => 'Verify code is required',
          'errors' => $validator->errors()
        ],
        400
      );
    }

    // Tìm người dùng dựa trên mã code
    $account = Account::where('email', $email)
      ->where('reset_code_expires_at', '>', Carbon::now())
      ->first();

    if (!$account) {
      return response()->json(['message' => 'Invalid or expired reset code'], 400);
    }

    // Kiểm tra số lần nhập mã code sai
    if ($account->reset_code_attempts >= 5) {
      return response()->json(['message' => 'Reset code expired'], 400);
    }

    // Kiểm tra mã code nhập vào
    if ($request->code !== $account->reset_code) {
      $account->reset_code_attempts++;
      $account->save();
      $attemptsLeft = 5 - $account->reset_code_attempts;
      return response()->json([
        'message' => 'Invalid Reset Code',
        'Your remaining password attempts are' => $attemptsLeft
      ], 400);
    } else {
      return response()->json([
        'message' => 'Verify Code True',
        'status' => 200,
        'account' => $account,
      ], 200);
    }
  }

  public function resetPassword(Request $request, $email, $code)
  {
    $validator = Validator::make($request->all(), [
      'password' => 'required|min:6',
      'confirm_password' => 'required|same:password',
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          'message' => 'Invalid data',
          'errors' => $validator->errors()
        ],
        400
      );
    }

    // Tìm người dùng dựa trên mã code
    $account = Account::where('email', $email)
      ->where('reset_code', $code)
      ->first();

    if (!$account) {
      return response()->json(['message' => 'Invalid or expired reset code'], 400);
    }

    // Cập nhật mật khẩu mới và xóa reset_code, reset_code_expires_at, reset_code_attempts
    $account->password = Hash::make($request->password);
    $account->reset_code = null;
    $account->reset_code_expires_at = null;
    $account->reset_code_attempts = null;
    $account->save();

    return response()->json(['message' => 'Password reset successful']);
  }

  public function changePassword(Request $request)
  {
    $account = auth()->user();

    // Kiểm tra token hợp lệ và người dùng đã đăng nhập
    if (!$account) {
      return response()->json(['message' => 'Unauthorized'], 401);
    }

    $validator = Validator::make($request->all(), [
      'current_password' => 'required',
      'new_password' => 'required|min:6',
      'confirm_password' => 'required|same:new_password',
    ], [
      'current_password.required' => 'Current password is required.',
      'new_password.required' => 'New password is required.',
      'new_password.min' => 'New password must be at least 6 characters.',
      'confirm_password.required' => 'Confirm password is required.',
      'confirm_password.same' => 'Confirm password must match new password.',
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors()->first()], 400);
    }

    $data = Account::findOrFail($account->id);
    $currentPassword = $request->current_password;
    $newPassword = $request->new_password;

    // Kiểm tra mật khẩu hiện tại của người dùng
    if (!Hash::check($currentPassword, $data->password)) {
      return response()->json(['message' => 'Current password not match!'], 401);
    }

    $data->password = Hash::make($newPassword);
    $data->save();

    return response()->json([
      'message' => 'Change password successfully',
      'account' => $data,
    ], 200);
  }
}
