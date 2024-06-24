<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class HomeController extends Controller
{
  public function index()
  {
    $user = auth()->user(); // Giả sử bạn đã xác thực người dùng và có thông tin người dùng

    // Nếu bạn có Expo Push Token, bạn cần phải chắc chắn rằng nó được lưu trữ trong bảng users
    $expoToken = $user->expo_token; // Cột expo_token chứa Expo Push Token của người dùng
    
    // Gửi thông báo
    Notification::send($user, new WelcomeNotification());
    return "Notification sent!";
  }
}
