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

    // Kiểm tra riêng xem email có bị trùng lặp hay không
    $email = $request->input('email');
    $existingAccount = Account::where('email', $email)->where('id', '!=', $customer->account_id)->first();
    if ($existingAccount) {
      return response()->json([
        'message' => 'The email has already been taken.',
        'status' => 422
      ], 422);
    }

    // Xác thực dữ liệu
    $validatedData = $request->validate([
      'full_name' => 'required|string',
      'gender' => 'required|string',
      'birthdate' => 'required',
      'address' => 'required|string',
      'CMND' => ['required', 'string', Rule::unique('customers')->ignore($customer->id)],
      'phone' => ['required', 'string', Rule::unique('customers')->ignore($customer->id)],
      'avatar' => 'nullable|string',
      'username' => 'required|string',
      'email' => 'required|email',
    ]);

    // Cập nhật shop
    $customer->update([
      'full_name' => $validatedData['full_name'],
      'gender' => $validatedData['gender'],
      'birthdate' => $validatedData['birthdate'],
      'address' => $validatedData['address'],
      'CMND' => $validatedData['CMND'],
      'phone' => $validatedData['phone'],
    ]);

    // Cập nhật thông tin tài khoản
    $account = Account::findOrFail($customer->account_id);
    $account->update([
      'username' => $validatedData['username'],
      'email' => $validatedData['email'],
      'avatar' => $validatedData['avatar'],
    ]);

    return response()->json([
      'message' => 'Customer updated successfully.',
      'status' => 200,
      'data' => $customer
    ], 200);
  }
}
