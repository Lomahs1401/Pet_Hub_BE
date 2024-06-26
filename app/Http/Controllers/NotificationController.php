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
      $title = "Thông báo mới";
      $body = "Bạn có một thông báo mới.";
      $data = ["key1" => "value1", "key2" => "value2"];
      $sound = "default";

      $this->sendPushNotification($title, $body, $data, $sound);

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

  public static function sendPushNotification($title, $body, $data = [], $sound = 'default')
  {
    $old_recipients = ["ExponentPushToken[mN5oPEJnC3R13CiGr1YOQh]"];
    $recipients = ["ExponentPushToken[v6Pzj4GxkFIIuXqAsSDU95]"];

    $payload = [
      "to" => $recipients,
      "title" => $title,
      "body" => $body,
      "sound" => $sound, // Thêm trường sound
      "data" => $data    // Thêm trường data
    ];

    $response = Http::post("https://exp.host/--/api/v2/push/send", $payload)->json();
    Log::info($response);
  }
}
