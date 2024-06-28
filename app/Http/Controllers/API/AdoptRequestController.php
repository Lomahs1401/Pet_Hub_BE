<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AdoptRequest;
use App\Models\HistoryAdopt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdoptRequestController extends Controller
{
  public function getPendingAdoptRequest(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $page_number = $request->query('page_number', 1);
    $num_of_page = $request->query('num_of_page', 10);
    $filter = strtolower($request->query('filter', 'all'));

    // Lấy danh sách pet đã có yêu cầu nhận nuôi với trạng thái done
    $donePetIds = AdoptRequest::where('aid_center_id', $aidCenterId)
      ->where('status', 'Done')
      ->pluck('pet_id')
      ->toArray();

    $query = AdoptRequest::with(['pet', 'pet.breed', 'customer', 'customer.account'])
      ->where('aid_center_id', $aidCenterId)
      ->where('status', 'Pending')
      ->whereNotIn('pet_id', $donePetIds); // Loại trừ các pet đã được nhận nuôi

    if ($filter !== 'all') {
      $query->whereHas('pet', function ($petQuery) use ($filter) {
        $petQuery->where('type', $filter);
      });
    }

    $adoptRequests = $query->paginate($num_of_page, ['*'], 'page', $page_number);

    $formattedAdoptRequests = $adoptRequests->map(function ($adoptRequest) {
      return [
        'id' => $adoptRequest->id,
        'status' => $adoptRequest->status,
        'note' => $adoptRequest->note,
        'created_at' => $adoptRequest->created_at,
        'updated_at' => $adoptRequest->updated_at,
        'pet' => $adoptRequest->pet ? [
          'pet_id' => $adoptRequest->pet->id,
          'pet_name' => $adoptRequest->pet->name,
          'pet_image' => $adoptRequest->pet->image,
          'pet_type' => $adoptRequest->pet->type,
          'pet_gender' => $adoptRequest->pet->gender,
          'pet_breed' => $adoptRequest->pet->breed->name,
        ] : null,
        'customer' => $adoptRequest->customer ? [
          'customer_id' => $adoptRequest->customer->id,
          'full_name' => $adoptRequest->customer->full_name,
          'phone' => $adoptRequest->customer->phone,
          'username' => $adoptRequest->customer->account->username,
          'email' => $adoptRequest->customer->account->email,
          'avatar' => $adoptRequest->customer->account->avatar,
        ] : null,
      ];
    });

    return response()->json([
      'message' => 'Pending adopt request fetch successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $adoptRequests->total(),
        'per_page' => $adoptRequests->perPage(),
        'current_page' => $adoptRequests->currentPage(),
        'last_page' => $adoptRequests->lastPage(),
        'from' => $adoptRequests->firstItem(),
        'to' => $adoptRequests->lastItem(),
      ],
      'data' => $formattedAdoptRequests,
    ], 200);
  }

  public function getApproveAdoptRequest(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $page_number = $request->query('page_number', 1);
    $num_of_page = $request->query('num_of_page', 10);
    $filter = strtolower($request->query('filter', 'all'));

    // Lấy danh sách pet đã có yêu cầu nhận nuôi với trạng thái done
    $donePetIds = AdoptRequest::where('aid_center_id', $aidCenterId)
      ->where('status', 'Done')
      ->pluck('pet_id')
      ->toArray();

    $query = AdoptRequest::with(['pet', 'pet.breed', 'customer', 'customer.account'])
      ->where('aid_center_id', $aidCenterId)
      ->where('status', 'Approved')
      ->whereNotIn('pet_id', $donePetIds); // Loại trừ các pet đã được nhận nuôi

    if ($filter !== 'all') {
      $query->whereHas('pet', function ($petQuery) use ($filter) {
        $petQuery->where('type', $filter);
      });
    }

    $adoptRequests = $query->paginate($num_of_page, ['*'], 'page', $page_number);

    $formattedAdoptRequests = $adoptRequests->map(function ($adoptRequest) {
      return [
        'id' => $adoptRequest->id,
        'status' => $adoptRequest->status,
        'note' => $adoptRequest->note,
        'created_at' => $adoptRequest->created_at,
        'updated_at' => $adoptRequest->updated_at,
        'pet' => $adoptRequest->pet ? [
          'pet_id' => $adoptRequest->pet->id,
          'pet_name' => $adoptRequest->pet->name,
          'pet_image' => $adoptRequest->pet->image,
          'pet_type' => $adoptRequest->pet->type,
          'pet_gender' => $adoptRequest->pet->gender,
          'pet_breed' => $adoptRequest->pet->breed->name,
        ] : null,
        'customer' => $adoptRequest->customer ? [
          'customer_id' => $adoptRequest->customer->id,
          'full_name' => $adoptRequest->customer->full_name,
          'phone' => $adoptRequest->customer->phone,
          'username' => $adoptRequest->customer->account->username,
          'email' => $adoptRequest->customer->account->email,
          'avatar' => $adoptRequest->customer->account->avatar,
        ] : null,
      ];
    });

    return response()->json([
      'message' => 'Approve adopt request fetch successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $adoptRequests->total(),
        'per_page' => $adoptRequests->perPage(),
        'current_page' => $adoptRequests->currentPage(),
        'last_page' => $adoptRequests->lastPage(),
        'from' => $adoptRequests->firstItem(),
        'to' => $adoptRequests->lastItem(),
      ],
      'data' => $formattedAdoptRequests,
    ], 200);
  }

  public function getDoneAdoptRequest(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $page_number = $request->query('page_number', 1);
    $num_of_page = $request->query('num_of_page', 10);
    $filter = strtolower($request->query('filter', 'all'));

    $query = AdoptRequest::with(['pet', 'pet.breed', 'customer', 'customer.account'])
      ->where('aid_center_id', $aidCenterId)
      ->where('status', 'Done');

    if ($filter !== 'all') {
      $query->whereHas('pet', function ($petQuery) use ($filter) {
        $petQuery->where('type', $filter);
      });
    }

    $adoptRequests = $query->paginate($num_of_page, ['*'], 'page', $page_number);

    $formattedAdoptRequests = $adoptRequests->map(function ($adoptRequest) {
      return [
        'id' => $adoptRequest->id,
        'status' => $adoptRequest->status,
        'note' => $adoptRequest->note,
        'created_at' => $adoptRequest->created_at,
        'updated_at' => $adoptRequest->updated_at,
        'pet' => $adoptRequest->pet ? [
          'pet_id' => $adoptRequest->pet->id,
          'pet_name' => $adoptRequest->pet->name,
          'pet_image' => $adoptRequest->pet->image,
          'pet_type' => $adoptRequest->pet->type,
          'pet_gender' => $adoptRequest->pet->gender,
          'pet_breed' => $adoptRequest->pet->breed->name,
        ] : null,
        'customer' => $adoptRequest->customer ? [
          'customer_id' => $adoptRequest->customer->id,
          'full_name' => $adoptRequest->customer->full_name,
          'phone' => $adoptRequest->customer->phone,
          'username' => $adoptRequest->customer->account->username,
          'email' => $adoptRequest->customer->account->email,
          'avatar' => $adoptRequest->customer->account->avatar,
        ] : null,
      ];
    });

    return response()->json([
      'message' => 'Pending adopt request fetch successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $adoptRequests->total(),
        'per_page' => $adoptRequests->perPage(),
        'current_page' => $adoptRequests->currentPage(),
        'last_page' => $adoptRequests->lastPage(),
        'from' => $adoptRequests->firstItem(),
        'to' => $adoptRequests->lastItem(),
      ],
      'data' => $formattedAdoptRequests,
    ], 200);
  }

  public function searchPendingAdoptRequest(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $searchTerm = $request->query('search_term', '');

    $donePetIds = AdoptRequest::where('aid_center_id', $aidCenterId)
      ->where('status', 'Done')
      ->pluck('pet_id')
      ->toArray();

    $query = AdoptRequest::with(['pet', 'pet.breed', 'customer', 'customer.account'])
      ->where('aid_center_id', $aidCenterId)
      ->where('status', 'Pending')
      ->whereNotIn('pet_id', $donePetIds)
      ->where(function ($query) use ($searchTerm) {
        $query->orWhereHas('customer', function ($customerQuery) use ($searchTerm) {
          $customerQuery->where('full_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
            ->orWhereHas('account', function ($accountQuery) use ($searchTerm) {
              $accountQuery->where('email', 'LIKE', "%{$searchTerm}%");
            });
        })
          ->orWhereHas('pet', function ($petQuery) use ($searchTerm) {
            $petQuery->where('name', 'LIKE', "%{$searchTerm}%");
          });
      });

    $adoptRequests = $query->paginate($numOfPage, ['*'], 'page', $pageNumber);

    $formattedAdoptRequests = $adoptRequests->map(function ($adoptRequest) {
      return [
        'id' => $adoptRequest->id,
        'status' => $adoptRequest->status,
        'note' => $adoptRequest->note,
        'created_at' => $adoptRequest->created_at,
        'updated_at' => $adoptRequest->updated_at,
        'pet' => $adoptRequest->pet ? [
          'pet_id' => $adoptRequest->pet->id,
          'pet_name' => $adoptRequest->pet->name,
          'pet_image' => $adoptRequest->pet->image,
          'pet_type' => $adoptRequest->pet->type,
          'pet_gender' => $adoptRequest->pet->gender,
          'pet_breed' => $adoptRequest->pet->breed->name,
        ] : null,
        'customer' => $adoptRequest->customer ? [
          'customer_id' => $adoptRequest->customer->id,
          'full_name' => $adoptRequest->customer->full_name,
          'username' => $adoptRequest->customer->account->username,
          'email' => $adoptRequest->customer->account->email,
          'avatar' => $adoptRequest->customer->account->avatar,
          'phone' => $adoptRequest->customer->phone,
        ] : null,
      ];
    });

    return response()->json([
      'message' => 'Pending adopt requests fetched successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $adoptRequests->total(),
        'per_page' => $adoptRequests->perPage(),
        'current_page' => $adoptRequests->currentPage(),
        'last_page' => $adoptRequests->lastPage(),
        'from' => $adoptRequests->firstItem(),
        'to' => $adoptRequests->lastItem(),
      ],
      'data' => $formattedAdoptRequests,
    ]);
  }

  public function searchApproveAdoptRequest(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $searchTerm = $request->query('search_term', '');

    $donePetIds = AdoptRequest::where('aid_center_id', $aidCenterId)
      ->where('status', 'Done')
      ->pluck('pet_id')
      ->toArray();

    $query = AdoptRequest::with(['pet', 'pet.breed', 'customer', 'customer.account'])
      ->where('aid_center_id', $aidCenterId)
      ->where('status', 'Approved')
      ->whereNotIn('pet_id', $donePetIds)
      ->where(function ($query) use ($searchTerm) {
        $query->orWhereHas('customer', function ($customerQuery) use ($searchTerm) {
          $customerQuery->where('full_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
            ->orWhereHas('account', function ($accountQuery) use ($searchTerm) {
              $accountQuery->where('email', 'LIKE', "%{$searchTerm}%");
            });
        })
          ->orWhereHas('pet', function ($petQuery) use ($searchTerm) {
            $petQuery->where('name', 'LIKE', "%{$searchTerm}%");
          });
      });

    $adoptRequests = $query->paginate($numOfPage, ['*'], 'page', $pageNumber);

    $formattedAdoptRequests = $adoptRequests->map(function ($adoptRequest) {
      return [
        'id' => $adoptRequest->id,
        'status' => $adoptRequest->status,
        'note' => $adoptRequest->note,
        'created_at' => $adoptRequest->created_at,
        'updated_at' => $adoptRequest->updated_at,
        'pet' => $adoptRequest->pet ? [
          'pet_id' => $adoptRequest->pet->id,
          'pet_name' => $adoptRequest->pet->name,
          'pet_image' => $adoptRequest->pet->image,
          'pet_type' => $adoptRequest->pet->type,
          'pet_gender' => $adoptRequest->pet->gender,
          'pet_breed' => $adoptRequest->pet->breed->name,
        ] : null,
        'customer' => $adoptRequest->customer ? [
          'customer_id' => $adoptRequest->customer->id,
          'full_name' => $adoptRequest->customer->full_name,
          'username' => $adoptRequest->customer->account->username,
          'email' => $adoptRequest->customer->account->email,
          'avatar' => $adoptRequest->customer->account->avatar,
          'phone' => $adoptRequest->customer->phone,
        ] : null,
      ];
    });

    return response()->json([
      'message' => 'Pending adopt requests fetched successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $adoptRequests->total(),
        'per_page' => $adoptRequests->perPage(),
        'current_page' => $adoptRequests->currentPage(),
        'last_page' => $adoptRequests->lastPage(),
        'from' => $adoptRequests->firstItem(),
        'to' => $adoptRequests->lastItem(),
      ],
      'data' => $formattedAdoptRequests,
    ]);
  }

  public function searchDoneAdoptRequest(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $searchTerm = $request->query('search_term', '');

    $query = AdoptRequest::with(['pet', 'pet.breed', 'customer', 'customer.account'])
      ->where('aid_center_id', $aidCenterId)
      ->where('status', 'Done')
      ->where(function ($query) use ($searchTerm) {
        $query->orWhereHas('customer', function ($customerQuery) use ($searchTerm) {
          $customerQuery->where('full_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
            ->orWhereHas('account', function ($accountQuery) use ($searchTerm) {
              $accountQuery->where('email', 'LIKE', "%{$searchTerm}%");
            });
        })
          ->orWhereHas('pet', function ($petQuery) use ($searchTerm) {
            $petQuery->where('name', 'LIKE', "%{$searchTerm}%");
          });
      });

    $adoptRequests = $query->paginate($numOfPage, ['*'], 'page', $pageNumber);

    $formattedAdoptRequests = $adoptRequests->map(function ($adoptRequest) {
      return [
        'id' => $adoptRequest->id,
        'status' => $adoptRequest->status,
        'note' => $adoptRequest->note,
        'created_at' => $adoptRequest->created_at,
        'updated_at' => $adoptRequest->updated_at,
        'pet' => $adoptRequest->pet ? [
          'pet_id' => $adoptRequest->pet->id,
          'pet_name' => $adoptRequest->pet->name,
          'pet_image' => $adoptRequest->pet->image,
          'pet_type' => $adoptRequest->pet->type,
          'pet_gender' => $adoptRequest->pet->gender,
          'pet_breed' => $adoptRequest->pet->breed->name,
        ] : null,
        'customer' => $adoptRequest->customer ? [
          'customer_id' => $adoptRequest->customer->id,
          'full_name' => $adoptRequest->customer->full_name,
          'username' => $adoptRequest->customer->account->username,
          'email' => $adoptRequest->customer->account->email,
          'avatar' => $adoptRequest->customer->account->avatar,
          'phone' => $adoptRequest->customer->phone,
        ] : null,
      ];
    });

    return response()->json([
      'message' => 'Pending adopt requests fetched successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $adoptRequests->total(),
        'per_page' => $adoptRequests->perPage(),
        'current_page' => $adoptRequests->currentPage(),
        'last_page' => $adoptRequests->lastPage(),
        'from' => $adoptRequests->firstItem(),
        'to' => $adoptRequests->lastItem(),
      ],
      'data' => $formattedAdoptRequests,
    ]);
  }

  public function approveAdoptRequest(Request $request, $adopt_request_id)
  {
    // Find the adopt request by id
    $adoptRequest = AdoptRequest::findOrFail($adopt_request_id);

    // Check if the authenticated user belongs to the same aid center
    $aidCenterId = auth()->user()->aidCenter->id;
    if ($adoptRequest->aid_center_id !== $aidCenterId) {
      return response()->json([
        'message' => 'Unauthorized action.',
        'status' => 403,
      ], 403);
    }

    // Update the status to 'Approve'
    $adoptRequest->status = 'Approved';
    $adoptRequest->save();

    // Send notification to the customer
    if ($adoptRequest->customer && $adoptRequest->customer->account) {
      $account = $adoptRequest->customer->account;
      $title = "MyPet App";
      $body = "Yêu cầu nhận nuôi thú cưng của bạn đã được phê duyệt";
      $data = [
        "adopt_request_id" => $adoptRequest->id,
        "pet_name" => $adoptRequest->pet->name,
        "status" => $adoptRequest->status,
      ];
      $sound = "default";

      // Send the push notification
      $this->sendPushNotification($account->expo_push_token, $title, $body, $data, $sound);
    }

    return response()->json([
      'message' => 'Adopt request approved successfully!',
      'status' => 200,
      'data' => [
        'id' => $adoptRequest->id,
        'status' => $adoptRequest->status,
        'note' => $adoptRequest->note,
        'created_at' => $adoptRequest->created_at,
        'updated_at' => $adoptRequest->updated_at,
        'pet' => $adoptRequest->pet ? [
          'pet_id' => $adoptRequest->pet->id,
          'pet_name' => $adoptRequest->pet->name,
          'pet_image' => $adoptRequest->pet->image,
          'pet_type' => $adoptRequest->pet->type,
          'pet_gender' => $adoptRequest->pet->gender,
          'pet_breed' => $adoptRequest->pet->breed->name,
        ] : null,
        'customer' => $adoptRequest->customer ? [
          'customer_id' => $adoptRequest->customer->id,
          'full_name' => $adoptRequest->customer->full_name,
          'phone' => $adoptRequest->customer->phone,
          'username' => $adoptRequest->customer->account->username,
          'email' => $adoptRequest->customer->account->email,
          'avatar' => $adoptRequest->customer->account->avatar,
        ] : null,
      ],
    ], 200);
  }

  public function doneAdoptRequest(Request $request, $adopt_request_id)
  {
    // Find the adopt request by id
    $adoptRequest = AdoptRequest::with(['pet', 'customer.account'])->findOrFail($adopt_request_id);

    // Check if the authenticated user belongs to the same aid center
    $aidCenterId = auth()->user()->aidCenter->id;
    if ($adoptRequest->aid_center_id !== $aidCenterId) {
      return response()->json([
        'message' => 'Unauthorized action.',
        'status' => 403,
      ], 403);
    }

    // Create a new record in history_adoptions
    DB::transaction(function () use ($adoptRequest) {
      HistoryAdopt::create([
        'customer_id' => $adoptRequest->customer_id,
        'pet_id' => $adoptRequest->pet_id,
      ]);

      // Update the status of the adopt request to 'Done'
      $adoptRequest->status = 'Done';
      $adoptRequest->save();

      // Update the status of the pet to 1 (adopted)
      $adoptRequest->pet->status = 1;
      $adoptRequest->pet->save();
    });

    // Send notification to the customer
    if ($adoptRequest->customer && $adoptRequest->customer->account) {
      $account = $adoptRequest->customer->account;
      $title = "MyPet App";
      $body = "Bạn đã chính thức nhận nuôi thú cưng.";
      $data = [
        "adopt_request_id" => $adoptRequest->id,
        "pet_name" => $adoptRequest->pet->name,
        "status" => $adoptRequest->status,
      ];
      $sound = "default";

      // Send the push notification
      $this->sendPushNotification($account->expo_push_token, $title, $body, $data, $sound);
    }

    return response()->json([
      'message' => 'Adopt request marked as done successfully!',
      'status' => 200,
      'data' => [
        'id' => $adoptRequest->id,
        'status' => $adoptRequest->status,
        'note' => $adoptRequest->note,
        'created_at' => $adoptRequest->created_at,
        'updated_at' => $adoptRequest->updated_at,
        'pet' => $adoptRequest->pet ? [
          'pet_id' => $adoptRequest->pet->id,
          'pet_name' => $adoptRequest->pet->name,
          'pet_image' => $adoptRequest->pet->image,
          'pet_type' => $adoptRequest->pet->type,
          'pet_gender' => $adoptRequest->pet->gender,
          'pet_breed' => $adoptRequest->pet->breed->name,
        ] : null,
        'customer' => $adoptRequest->customer ? [
          'customer_id' => $adoptRequest->customer->id,
          'full_name' => $adoptRequest->customer->full_name,
          'phone' => $adoptRequest->customer->phone,
          'username' => $adoptRequest->customer->account->username,
          'email' => $adoptRequest->customer->account->email,
          'avatar' => $adoptRequest->customer->account->avatar,
        ] : null,
      ],
    ], 200);
  }

  // Method to send push notification
  protected function sendPushNotification($recipientToken, $title, $body, $data = [], $sound = 'default')
  {
    $payload = [
      "to" => $recipientToken,
      "title" => $title,
      "body" => $body,
      "sound" => $sound, // Thêm trường sound
      "data" => $data    // Thêm trường data
    ];

    $response = Http::post("https://exp.host/--/api/v2/push/send", $payload)->json();
    Log::info($response);
  }
}
