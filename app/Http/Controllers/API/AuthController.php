<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountHasRole;
use App\Models\Customer;
use App\Models\Ranking;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
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

        // Create new account
        $account = Account::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $request->avatar,
            'enabled' => $request->enabled | '1',
        ]);

        $bronze_ranking_id = Ranking::where('ranking_name', 'Bronze')->value('id');
        print($bronze_ranking_id);
        Customer::create([
            'ranking_point' => 0,
            'account_id' => $account->id,
            'ranking_id' => $bronze_ranking_id,
        ]);

        $role_customer_id = Role::where('role_name', 'Customer')->value('id');
        print($role_customer_id);
        AccountHasRole::created([
            'account_id' => $account->id,
            'role_id' => $role_customer_id,
            'licensed' => 1,
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
                'error' => 'Unauthorized',
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

        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'status_code' => 401,
                'message' => 'Login failed!',
                'error' => 'Unauthorized',
            ], 401);
        }

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
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
