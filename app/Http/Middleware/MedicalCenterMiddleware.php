<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicalCenterMiddleware
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
    $role_user = DB::table('roles')->where('id', '=', auth()->user()->role_id)->value('role_name');

    if ($role_user === 'ROLE_MEDICAL_CENTER') {
      return $next($request);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
  }
}
