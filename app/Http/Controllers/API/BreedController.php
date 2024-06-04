<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BreedController extends Controller
{
  public function index(Request $request)
  {
    $target = strtolower($request->query('target')) ?? 'all';

    if ($target === 'all') {
      $breeds = Breed::select('id', 'name', 'type')->get();
    } else {
      // Nếu 'target' là 'dog' hoặc 'cat' thì query dữ liệu từ bảng 'product_categories'
      if ($target === 'dog' || $target === 'cat') {
        $breeds = Breed::select('id', 'name', 'type')->where('type', $target)->get();
      }

      if (empty($breeds)) {
        return response()->json([
          'message' => 'Query successfully!',
          'status' => 200,
          'data' => []
        ]);
      }
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $breeds,
    ], 200);
  }

  public function show($breed_id)
  {
    $breed = Breed::find($breed_id);

    if (!$breed) {
      return response()->json([
        'message' => 'Breed not found',
        'status' => 404
      ], 404);
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $breed,
    ], 200);
  }
}
