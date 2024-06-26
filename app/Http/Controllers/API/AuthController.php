<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountHasRole;
use App\Models\AidCenter;
use App\Models\Customer;
use App\Models\ExpoToken;
use App\Models\MedicalCenter;
use App\Models\Ranking;
use App\Models\Role;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:api', [
      'except' => [
        'login',
        'registerCustomer',
        'registerShop',
        'registerMedicalCenter',
        'registerAidCenter'
      ]
    ]);
  }

  public function registerCustomer(Request $request)
  {
    // validate form
    $validator = Validator::make($request->all(), [
      'username' => 'required',
      'email' => 'required|email|unique:accounts',
      'password' => 'required|min:6',
      'confirm_password' => 'required|same:password',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();

      $response = [
        'status_code' => 400,
        'message' => [
          'username' => $errors->first('username'),
          'email' => $errors->first('email'),
          'password' => $errors->first('password'),
          'confirm_password' => $errors->first('confirm_password'),
        ],
      ];

      return response()->json($response, 400);
    }

    $default_avatar = 'gs://new_petshop_bucket/avatars/customer/png-transparent-default-avatar-thumbnail.png';

    $role_customer_id = Role::where('role_name', 'ROLE_CUSTOMER')->value('id');

    // Create new account
    $account = Account::create([
      'username' => $request->username,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'avatar' => $default_avatar,
      'role_id' => $role_customer_id,
      'enabled' => $request->enabled ?? '1',
      'is_approved' => 1,
    ]);

    $bronze_ranking_id = Ranking::where('ranking_name', 'Bronze')->value('id');
    Customer::create([
      'ranking_point' => 0,
      'account_id' => $account->id,
      'ranking_id' => $bronze_ranking_id,
    ]);

    // Get a JWT
    $token = Auth::login($account);

    return response()->json([
      'status_code' => 201,
      'message' => 'User created successfully!',
      'user' => $account,
      'authorization' => [
        'token' => $token,
        'type' => 'bearer',
      ]
    ], 201);
  }

  public function registerShop(Request $request)
  {
    // validate form
    $validator = Validator::make($request->all(), [
      'username' => 'required',
      'email' => 'required|email|unique:accounts',
      'password' => 'required|min:6',
      'confirm_password' => 'required|same:password',
      'phone' => 'required|unique:shops',
      'website' => 'nullable|string',
      'fanpage' => 'nullable|string',
      'name' => 'required|string',
      'address' => 'required|string',
      'work_time' => 'required|string',
      'establish_year' => 'required|string',
    ], [
      'username.required' => 'Username is required',
      'email.required' => 'Email is required',
      'email.email' => 'Email must be a valid email address',
      'email.unique' => 'Email has already been taken',
      'password.required' => 'Password is required',
      'password.min' => 'Password must be at least 6 characters',
      'confirm_password.required' => 'Confirm password is required',
      'confirm_password.same' => 'Confirm password must match the password',
      'phone.required' => 'Phone number is required',
      'phone.unique' => 'Phone number has already been taken',
      'name.required' => 'Shop name is required',
      'address.required' => 'Address is required',
      'work_time.required' => 'Work time is required',
      'establish_year.required' => 'Established year is required',
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors()->first()], 400);
    }

    $default_avatar = 'gs://new_petshop_bucket/avatars/shop/default-user-image.png';

    $role_shop_id = Role::where('role_name', 'ROLE_SHOP')->value('id');

    // Create new account
    $account = Account::create([
      'username' => $request->username,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'avatar' => $default_avatar,
      'role_id' => $role_shop_id,
      'enabled' => 0,
      'is_approved' => 0, // Chưa được phê duyệt
    ]);

    Shop::create([
      'name' => $request->name,
      'phone' => $request->phone,
      'address' => $request->address,
      'website' => $request->website,
      'fanpage' => $request->fanpage,
      'work_time' => $request->work_time,
      'establish_year' => $request->establish_year,
      'account_id' => $account->id,
    ]);

    return response()->json([
      'status_code' => 201,
      'message' => 'Request has been sent to admin for approval!',
    ], 201);
  }

  public function registerMedicalCenter(Request $request)
  {
    // validate form
    $validator = Validator::make($request->all(), [
      'username' => 'required',
      'email' => 'required|email|unique:accounts',
      'password' => 'required|min:6',
      'confirm_password' => 'required|same:password',
      'phone' => 'required|unique:medical_centers',
      'website' => 'nullable|string',
      'fanpage' => 'nullable|string',
      'name' => 'required|string',
      'address' => 'required|string',
      'work_time' => 'required|string',
      'establish_year' => 'required|string',
    ], [
      'username.required' => 'Username is required',
      'email.required' => 'Email is required',
      'email.email' => 'Email must be a valid email address',
      'email.unique' => 'Email has already been taken',
      'password.required' => 'Password is required',
      'password.min' => 'Password must be at least 6 characters',
      'confirm_password.required' => 'Confirm password is required',
      'confirm_password.same' => 'Confirm password must match the password',
      'phone.required' => 'Phone number is required',
      'phone.unique' => 'Phone number has already been taken',
      'name.required' => 'Shop name is required',
      'address.required' => 'Address is required',
      'work_time.required' => 'Work time is required',
      'establish_year.required' => 'Established year is required',
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors()->first()], 400);
    }

    $default_avatar = 'gs://new_petshop_bucket/avatars/shop/default-user-image.png';

    $role_medical_center = Role::where('role_name', 'ROLE_MEDICAL_CENTER')->value('id');

    // Create new account
    $account = Account::create([
      'username' => $request->username,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'avatar' => $default_avatar,
      'role_id' => $role_medical_center,
      'enabled' => 0,
      'is_approved' => 0, // Chưa được phê duyệt
    ]);

    MedicalCenter::create([
      'name' => $request->name,
      'phone' => $request->phone,
      'address' => $request->address,
      'website' => $request->website,
      'fanpage' => $request->fanpage,
      'work_time' => $request->work_time,
      'establish_year' => $request->establish_year,
      'account_id' => $account->id,
    ]);

    return response()->json([
      'status_code' => 201,
      'message' => 'Request has been sent to admin for approval!',
    ], 201);
  }

  public function registerAidCenter(Request $request)
  {
    // validate form
    $validator = Validator::make($request->all(), [
      'username' => 'required',
      'email' => 'required|email|unique:accounts',
      'password' => 'required|min:6',
      'confirm_password' => 'required|same:password',
      'phone' => 'required|unique:aid_centers',
      'website' => 'nullable|string',
      'fanpage' => 'nullable|string',
      'name' => 'required|string',
      'address' => 'required|string',
      'work_time' => 'required|string',
      'establish_year' => 'required|string',
    ], [
      'username.required' => 'Username is required',
      'email.required' => 'Email is required',
      'email.email' => 'Email must be a valid email address',
      'email.unique' => 'Email has already been taken',
      'password.required' => 'Password is required',
      'password.min' => 'Password must be at least 6 characters',
      'confirm_password.required' => 'Confirm password is required',
      'confirm_password.same' => 'Confirm password must match the password',
      'phone.required' => 'Phone number is required',
      'phone.unique' => 'Phone number has already been taken',
      'name.required' => 'Shop name is required',
      'address.required' => 'Address is required',
      'work_time.required' => 'Work time is required',
      'establish_year.required' => 'Established year is required',
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors()->first()], 400);
    }

    $default_avatar = 'gs://new_petshop_bucket/avatars/aid_center/default-user-image.png';

    $role_aid_center = Role::where('role_name', 'ROLE_AID_CENTER')->value('id');

    // Create new account
    $account = Account::create([
      'username' => $request->username,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'avatar' => $default_avatar,
      'role_id' => $role_aid_center,
      'enabled' => 0,
      'is_approved' => 0, // Chưa được phê duyệt
    ]);

    AidCenter::create([
      'name' => $request->name,
      'phone' => $request->phone,
      'address' => $request->address,
      'website' => $request->website,
      'fanpage' => $request->fanpage,
      'work_time' => $request->work_time,
      'establish_year' => $request->establish_year,
      'account_id' => $account->id,
    ]);

    return response()->json([
      'status_code' => 201,
      'message' => 'Request has been sent to admin for approval!',
    ], 201);
  }

  /**
   * Get a JWT via given credentials.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|string|email',
      'password' => 'required|string',
      'expo_token' => 'nullable|string', // Thêm validation cho expo_token
    ]);

    if ($validator->fails()) {
      return response()->json([
        'status_code' => 400,
        'message' => 'Invalid form data',
        'error' => $validator->errors(),
      ], 400);
    }

    $credentials = request(['email', 'password']);
    $account = Account::where('email', $request->email)->first();

    if (!$account) {
      // Tài khoản không tồn tại
      return response()->json([
        'status_code' => 401,
        'message' => 'Login failed!',
        'error' => 'Email or password is incorrect',
      ], 401);
    }

    if ($account->enabled == 0) {
      // Tài khoản bị vô hiệu hóa
      return response()->json([
        'status_code' => 401,
        'message' => 'Login failed!',
        'error' => 'Account is disabled',
      ], 401);
    }

    if ($account->is_approved == 0) {
      // Tài khoản chưa được phê duyệt
      return response()->json([
        'status_code' => 401,
        'message' => 'Login failed!',
        'error' => 'Account is not approved',
      ], 401);
    }

    if (!$token = auth()->attempt($credentials)) {
      return response()->json([
        'status_code' => 401,
        'message' => 'Login failed!',
        'error' => 'Unauthorized',
      ], 401);
    }

    $account->expo_token = $request->expo_token;
    $account->save();

    return $this->respondWithToken($token);
  }

  /**
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function me()
  {
    return response()->json(auth()->user());
  }

  /**
   * Get the authenticated User.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function userProfile()
  {
    return response()->json(auth()->user());
  }

  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    auth()->logout();

    return response()->json([
      'status_code' => 200,
      'message' => 'Successfully logged out',
    ], 200);
  }

  /**
   * Refresh a token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh()
  {
    return $this->respondWithToken(auth()->refresh());
  }

  /**
   * Get the token array structure.
   *
   * @param  string $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithToken($token)
  {
    $role_user = DB::table('roles')->where('id', '=', auth()->user()->role_id)->value('role_name');
    if ($role_user === "ROLE_CUSTOMER") {
      $customer = Customer::where('account_id', auth()->user()->id)->first();
      return response()->json([
        'message' => 'Login successfully!',
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60,
        'user' => array_merge(auth()->user()->toArray(), [
          'role_name' => $role_user,
          'customer_id' => $customer->id,
          'full_name' => $customer->full_name
        ]),
      ]);
    } else {
      return response()->json([
        'message' => 'Login successfully!',
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60,
        'user' => array_merge(auth()->user()->toArray(), [
          'role_name' => $role_user,
        ]),
      ]);
    }
  }
}
