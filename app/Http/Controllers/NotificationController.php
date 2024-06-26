<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
  public function index(Request $request)
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
      $this->sendPushNotification($account->device_token, 'Welcome', 'Welcome to our app!');

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

  public static function sendPushNotification($recipients, $title, $body)
  {
    $response = Http::post("https://exp.host/--/api/v2/push/send", [
      "to" => $recipients,
      "title" => $title,
      "body" => $body
    ])->json();
    Log::info($response);
  }
}
