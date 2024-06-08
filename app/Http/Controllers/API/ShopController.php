<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
  public function getProfileOfShop(Request $request)
  {
    $account_id = auth()->user()->id;

    $shop = Shop::where('account_id', $account_id)->first();

    // Kiểm tra xem shop có tồn tại không
    if (!$shop) {
      return response()->json(['message' => 'Shop not found'], 404);
    }

    $formattedShop = [
      'id' => $shop->id,
      'account_id' => $shop->account->id,
      'name' => $shop->name,
      'username' => $shop->account->username,
      'email' => $shop->account->email,
      'role' => $shop->account->role->role_name,
      'description' => $shop->description,
      'image' => $shop->image,
      'avatar' => $shop->account->avatar,
      'phone' => $shop->phone,
      'address' => $shop->address,
      'website' => $shop->website,
      'fanpage' => $shop->fanpage,
      'work_time' => $shop->work_time,
      'establish_year' => $shop->establish_year,
      'certificate' => $shop->certificate,
      'created_at' => $shop->created_at,
      'updated_at' => $shop->updated_at,
    ];

    return response()->json([
      'message' => 'Get shop profile successfully',
      'status' => 200,
      'data' => $formattedShop
    ], 200);
  }
}
