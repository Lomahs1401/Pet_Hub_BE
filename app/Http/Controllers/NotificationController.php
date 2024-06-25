<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
  public function sendTestNotification(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'account_id' => 'required|exists:accounts,id',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status_code' => 400,
        'message' => 'Invalid form data',
        'error' => $validator->errors(),
      ], 400);
    }

    $account = Account::find($request->account_id);

    if ($account) {
      $account->notify(new WelcomeNotification());

      return response()->json([
        'status_code' => 200,
        'message' => 'Notification sent successfully!',
      ]);
    } else {
      return response()->json([
        'status_code' => 404,
        'message' => 'Account not found',
      ], 404);
    }
  }
}
