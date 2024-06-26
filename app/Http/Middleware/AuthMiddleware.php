<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
   */
  public function handle(Request $request, Closure $next)
  {
    try {
      $user = JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
      if ($e instanceof TokenInvalidException) {
        return response()->json(['error' => 'Token is invalid'], 401);
      } else if ($e instanceof TokenExpiredException) {
        $new_token = JWTAuth::parseToken()->refresh();

        return response()->json([
          'success' => false,
          'message' => 'Token is expired',
          'new_token' => $new_token,
        ], 401);
      } else {
        return response()->json(['error' => 'No token provided'], 401);
      }
    }

    return $next($request);
  }
}
