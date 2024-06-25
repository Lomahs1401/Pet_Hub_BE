<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Shop;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

  public function updateShop(Request $request)
  {
    $shop_id = auth()->user()->shop->id;

    try {
      // Lấy shop hiện tại
      $shop = Shop::findOrFail($shop_id);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    // Xác thực dữ liệu
    $validatedData = $request->validate([
      'name' => 'required|string',
      'description' => 'nullable|string',
      'image' => 'nullable|string',
      'phone' => 'required|string',
      'address' => 'required|string',
      'website' => 'nullable|string',
      'fanpage' => 'nullable|string',
      'work_time' => 'required|string',
      'establish_year' => 'required|string',
      'certificate' => 'nullable|string',
      'avatar' => 'nullable|string',
      'username' => 'required|string',
    ]);

    // Cập nhật shop
    $shop->update([
      'name' => $validatedData['name'],
      'description' => $validatedData['description'],
      'image' => $validatedData['image'],
      'phone' => $validatedData['phone'],
      'address' => $validatedData['address'],
      'website' => $validatedData['website'],
      'fanpage' => $validatedData['fanpage'],
      'work_time' => $validatedData['work_time'],
      'establish_year' => $validatedData['establish_year'],
      'certificate' => $validatedData['certificate'],
    ]);

    // Cập nhật thông tin tài khoản
    $account = Account::findOrFail($shop->account_id);
    $account->update([
      'username' => $validatedData['username'],
      'avatar' => $validatedData['avatar'],
    ]);

    return response()->json([
      'message' => 'Shop updated successfully.',
      'status' => 200,
      'data' => $shop
    ], 200);
  }

  public function getAddress() {
    $shop_id = auth()->user()->shop->id;

    try {
      // Lấy shop hiện tại
      $shop = Shop::findOrFail($shop_id);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Shop not found!',
        'status' => 404
      ], 404);
    }

    return response()->json([
      'message' => 'Get shop address successfully',
      'status' => 200,
      'data' => $shop->address
    ], 200);
  }
}
