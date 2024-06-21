<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
  public function getProfile()
  {
    // Xác thực người dùng là khách hàng
    $customer = auth()->user();

    // Lấy thông tin khách hàng từ cơ sở dữ liệu
    $customerProfile = Customer::with('account')->find($customer->id);

    if (!$customerProfile) {
      return response()->json([
        'message' => 'Customer not found',
        'status' => 404
      ], 404);
    }

    // Định dạng dữ liệu trả về
    $profileData = [
      'id' => $customerProfile->id,
      'full_name' => $customerProfile->full_name,
      'gender' => $customerProfile->gender,
      'birthdate' => $customerProfile->birthdate,
      'address' => $customerProfile->address,
      'phone' => $customerProfile->phone,
      'ranking_point' => $customerProfile->ranking_point,
      'account' => [
        'id' => $customerProfile->account->id,
        'username' => $customerProfile->account->username,
        'email' => $customerProfile->account->email,
        'avatar' => $customerProfile->account->avatar,
        'enabled' => $customerProfile->account->enabled,
        'is_approved' => $customerProfile->account->is_approved,
        'role_id' => $customerProfile->account->role_id,
        'created_at' => $customerProfile->account->created_at,
        'updated_at' => $customerProfile->account->updated_at,
      ],
      'created_at' => $customerProfile->created_at,
      'updated_at' => $customerProfile->updated_at,
    ];

    // Trả về JSON response
    return response()->json([
      'message' => 'Profile retrieved successfully!',
      'status' => 200,
      'data' => $profileData,
    ]);
  }

  public function updateCustomer(Request $request)
  {
    $customer_id = auth()->user()->customer->id;

    try {
      // Lấy customer hiện tại
      $customer = Customer::findOrFail($customer_id);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Customer not found!',
        'status' => 404
      ], 404);
    }

    // Xác thực dữ liệu
    $validatedData = $request->validate([
      'full_name' => 'required|string',
      'gender' => 'required|string',
      'birthdate' => 'required',
      'address' => 'required|string',
      'phone' => ['required', 'string', Rule::unique('customers')->ignore($customer->id)],
      'avatar' => 'nullable|string',
      'username' => 'required|string',
    ]);

    // Cập nhật shop
    $customer->update([
      'full_name' => $validatedData['full_name'],
      'gender' => $validatedData['gender'],
      'birthdate' => $validatedData['birthdate'],
      'address' => $validatedData['address'],
      'phone' => $validatedData['phone'],
    ]);

    // Cập nhật thông tin tài khoản
    $account = Account::findOrFail($customer->account_id);
    $account->update([
      'username' => $validatedData['username'],
      'avatar' => $validatedData['avatar'],
    ]);

    return response()->json([
      'message' => 'Customer updated successfully.',
      'status' => 200,
      'data' => $customer
    ], 200);
  }
}
