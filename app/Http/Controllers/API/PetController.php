<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Pet;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Claims\Custom;

class PetController extends Controller
{
  public function pagingCustomerPet(Request $request, $customer_id = null)
  {
    if ($customer_id != null) {
      if (!Customer::find($customer_id)) {
        return response()->json([
          'message' => 'Customer not found!',
          'status' => 404
        ], 404);
      }
      $customerId = $customer_id;
    } else {
      $customerId = auth()->user()->id;
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Lấy số lượng sản phẩm
    $query = Pet::query()->whereNull('deleted_at')->where('customer_id', $customerId);

    $total_pets_of_customer = $query->count();
    $total_pages = ceil($total_pets_of_customer / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    $pets_of_customer = $query->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_pets = [];
    foreach ($pets_of_customer as $pet) {
      $formatted_pets[] = [
        'pet_id' => $pet->id,
        'pet_name' => $pet->name,
        'type' => $pet->type,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'description' => $pet->description,
        'image' => $pet->image,
        'is_purebred' => $pet->is_purebred,
        'status' => $pet->status,
        'breed' => [
          'breed_id' => $pet->breed->id,
          'name' => $pet->breed->name,
          'type' => $pet->breed->type,
          'description' => $pet->breed->description,
          'image' => $pet->breed->image,
          'origin' => $pet->breed->origin,
          'lifespan' => $pet->breed->lifespan,
          'average_size' => $pet->breed->average_size,
        ],
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_pets' => $total_pets_of_customer,
      'data' => $formatted_pets,
    ]);
  }

  public function pagingAdoptedPet(Request $request, $customer_id = null)
  {
    if ($customer_id != null) {
      if (!Customer::find($customer_id)) {
        return response()->json([
          'message' => 'Customer not found!',
          'status' => 404
        ], 404);
      }
      $customerId = $customer_id;
    } else {
      $customerId = auth()->user()->id;
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    // Lấy số lượng thú cưng đã nhận nuôi bởi khách hàng
    $query = Pet::query()
      ->whereHas('historyAdoptions', function ($query) use ($customerId) {
        $query->where('customer_id', $customerId);
      });

    $total_pets_adopted = $query->count();
    $total_pages = ceil($total_pets_adopted / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    $pets_adopted = $query->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_pets = [];
    foreach ($pets_adopted as $pet) {
      $formatted_pets[] = [
        'pet_id' => $pet->id,
        'pet_name' => $pet->name,
        'type' => $pet->type,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'description' => $pet->description,
        'image' => $pet->image,
        'is_purebred' => $pet->is_purebred,
        'status' => $pet->status,
        'breed' => [
          'breed_id' => $pet->breed->id,
          'name' => $pet->breed->name,
          'type' => $pet->breed->type,
          'description' => $pet->breed->description,
          'image' => $pet->breed->image,
          'origin' => $pet->breed->origin,
          'lifespan' => $pet->breed->lifespan,
          'average_size' => $pet->breed->average_size,
        ],
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_pets' => $total_pets_adopted,
      'data' => $formatted_pets,
    ]);
  }

  public function pagingAllPet(Request $request, $customer_id = null)
  {
    if ($customer_id != null) {
      if (!Customer::find($customer_id)) {
        return response()->json([
          'message' => 'Customer not found!',
          'status' => 404
        ], 404);
      }
      $customerId = $customer_id;
    } else {
      $customerId = auth()->user()->id;
    }

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $customer_pets_query = Pet::query()->whereNull('deleted_at')->where('customer_id', $customerId);
    $adopted_pets_query = Pet::query()->whereHas('historyAdoptions', function ($query) use ($customerId) {
      $query->where('customer_id', $customerId);
    });

    $total_customer_pets = $customer_pets_query->count();
    $total_adopted_pets = $adopted_pets_query->count();
    $total_pages = ceil(($total_customer_pets + $total_adopted_pets) / $num_of_page);

    // Tính toán offset
    $offset = ($page_number - 1) * $num_of_page;

    // Lấy danh sách thú cưng của khách hàng
    $customer_pets = $customer_pets_query->get();

    // Lấy danh sách thú cưng đã nhận nuôi
    $adopted_pets = $adopted_pets_query->get();

    // Tạo mảng dữ liệu trả về
    $formatted_pets = [
      'customer_pets' => $customer_pets->map(function ($pet) {
        return $this->formatPetData($pet);
      }),
      'adopted_pets' => $adopted_pets->map(function ($pet) {
        return $this->formatPetData($pet);
      }),
    ];

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_pets' => $total_customer_pets + $total_adopted_pets,
      'data' => $formatted_pets,
    ]);
  }

  private function formatPetData($pet)
  {
    return [
      'pet_id' => $pet->id,
      'pet_name' => $pet->name,
      'type' => $pet->type,
      'age' => $pet->age,
      'gender' => $pet->gender,
      'description' => $pet->description,
      'image' => $pet->image,
      'is_purebred' => $pet->is_purebred,
      'status' => $pet->status,
      'breed' => [
        'breed_id' => $pet->breed->id,
        'name' => $pet->breed->name,
        'type' => $pet->breed->type,
        'description' => $pet->breed->description,
        'image' => $pet->breed->image,
        'origin' => $pet->breed->origin,
        'lifespan' => $pet->breed->lifespan,
        'average_size' => $pet->breed->average_size,
      ],
    ];
  }

  public function show($pet_id)
  {
    $customerId = auth()->user()->id;

    // Kiểm tra xem pet_id có nằm trong danh sách pets của khách hàng không
    $pet = Pet::where('customer_id', $customerId)->whereNull('deleted_at')->find($pet_id);

    // Nếu không tìm thấy thú cưng trong danh sách của khách hàng, kiểm tra xem pet_id có nằm trong danh sách thú cưng đã nhận nuôi không
    if (!$pet) {
      $pet = Pet::whereHas('historyAdoptions', function ($query) use ($customerId, $pet_id) {
        $query->where('customer_id', $customerId)->where('pet_id', $pet_id);
      })->find($pet_id);
    }

    // Nếu không tìm thấy thú cưng, trả về lỗi 404
    if (!$pet) {
      return response()->json([
        'message' => 'Pet not found or does not belong to the customer',
        'status' => 404,
      ], 404);
    }

    // Lấy thông tin về lịch sử tiêm vaccine của thú cưng
    $vaccine_history = $pet->historyVaccines()->with('doctor')->get();

    // Lấy thông tin về lịch sử khám bệnh của thú cưng
    $diagnosis_history = $pet->historyDiagnosis()->with('doctor')->get();

    // Format dữ liệu của thú cưng
    $formatted_pet = [
      'pet_id' => $pet->id,
      'pet_name' => $pet->name,
      'type' => $pet->type,
      'age' => $pet->age,
      'gender' => $pet->gender,
      'description' => $pet->description,
      'image' => $pet->image,
      'is_purebred' => $pet->is_purebred,
      'status' => $pet->status,
      'breed' => [
        'breed_id' => $pet->breed->id,
        'name' => $pet->breed->name,
        'type' => $pet->breed->type,
        'description' => $pet->breed->description,
        'image' => $pet->breed->image,
        'origin' => $pet->breed->origin,
        'lifespan' => $pet->breed->lifespan,
        'average_size' => $pet->breed->average_size,
      ],
      'vaccine_history' => $vaccine_history->map(function ($history) {
        return [
          'vaccine_history_id' => $history->id,
          'vaccine' => $history->vaccine,
          'note' => $history->note,
          'created_at' => $history->created_at,
          'updated_at' => $history->updated_at,
          'doctor' => [
            'doctor_id' => $history->doctor->id,
            'account_id' => $history->doctor->account->id,
            'name' => $history->doctor->full_name,
            'email' => $history->doctor->account->email,
            'gender' => $history->doctor->gender,
            'birthdate' => $history->doctor->birthdate,
            'description' => $history->doctor->description,
            'CMND' => $history->doctor->CMND,
            'address' => $history->doctor->address,
            'image' => $history->doctor->image,
            'certificate' => $history->doctor->certificate,
          ],
        ];
      }),
      'diagnosis_history' => $diagnosis_history->map(function ($history) {
        return [
          'id' => $history->id,
          'reason' => $history->reason,
          'diagnosis' => $history->diagnosis,
          'treatment' => $history->treatment,
          'health_condition' => $history->health_condition,
          'note' => $history->note,
          'created_at' => $history->created_at,
          'updated_at' => $history->updated_at,
          'doctor' => [
            'doctor_id' => $history->doctor->id,
            'account_id' => $history->doctor->account->id,
            'name' => $history->doctor->full_name,
            'email' => $history->doctor->account->email,
            'gender' => $history->doctor->gender,
            'birthdate' => $history->doctor->birthdate,
            'description' => $history->doctor->description,
            'CMND' => $history->doctor->CMND,
            'address' => $history->doctor->address,
            'image' => $history->doctor->image,
            'certificate' => $history->doctor->certificate,
          ],
        ];
      }),
    ];

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'data' => $formatted_pet,
    ]);
  }

  public function searchPet(Request $request)
  {
    $customerId = auth()->user()->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));
    $keyword = $request->input('name', '');

    // Tìm kiếm thú cưng theo tên và chỉ lấy những thú cưng thuộc hoặc được nhận nuôi bởi khách hàng
    $query = Pet::query()
      ->where('name', 'like', "%$keyword%")
      ->whereNull('deleted_at')
      ->where(function ($query) use ($customerId) {
        $query->where('customer_id', $customerId)
          ->orWhereHas('historyAdoptions', function ($query) use ($customerId) {
            $query->where('customer_id', $customerId);
          });
      });

    $total_pets = $query->count();
    $total_pages = ceil($total_pets / $num_of_page);

    $offset = ($page_number - 1) * $num_of_page;

    $pets = $query->offset($offset)
      ->limit($num_of_page)
      ->get();

    $formatted_pets = [];
    foreach ($pets as $pet) {
      $formatted_pets[] = [
        'pet_id' => $pet->id,
        'pet_name' => $pet->name,
        'type' => $pet->type,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'description' => $pet->description,
        'image' => $pet->image,
        'is_purebred' => $pet->is_purebred,
        'status' => $pet->status,
        'breed' => [
          'breed_id' => $pet->breed->id,
          'name' => $pet->breed->name,
          'type' => $pet->breed->type,
          'description' => $pet->breed->description,
          'image' => $pet->breed->image,
          'origin' => $pet->breed->origin,
          'lifespan' => $pet->breed->lifespan,
          'average_size' => $pet->breed->average_size,
        ],
      ];
    }

    return response()->json([
      'message' => 'Query successfully!',
      'status' => 200,
      'page_number' => $page_number,
      'num_of_page' => $num_of_page,
      'total_pages' => $total_pages,
      'total_pets' => $total_pets,
      'data' => $formatted_pets,
    ]);
  }

  public function store(Request $request)
  {
    $customer_id = auth()->user()->id;

    $data = $request->all();

    // Đảm bảo rằng status là true nếu không được cung cấp
    $data['status'] = $data['status'] ?? true;

    $validatedData = $request->validate([
      'name' => 'required|string',
      'type' => 'required|string',
      'age' => 'nullable|numeric',
      'gender' => 'required|string',
      'description' => 'nullable|string',
      'image' => 'nullable|string',
      'is_purebred' => 'required|boolean',
      'status' => 'boolean',
      'breed_id' => 'required|exists:breeds,id',
      'aid_center_id' => 'nullable|exists:aid_centers,id',
    ]);
    $validatedData['customer_id'] = $customer_id;

    $pet = Pet::create($validatedData);

    return response()->json([
      'message' => 'Create pet successfully!',
      'status' => 201,
      'data' => $pet,
    ], 201);
  }

  public function update(Request $request, $pet_id)
  {
    $customer_id = auth()->user()->id;

    $pet = Pet::where('id', $pet_id)
      ->where(function ($query) use ($customer_id) {
        $query->where('customer_id', $customer_id)
          ->orWhereHas('historyAdoptions', function ($query) use ($customer_id) {
            $query->where('customer_id', $customer_id);
          });
      })
      ->first();

    if (!$pet) {
      return response()->json([
        'message' => 'Pet not found or does not belong to the customer',
        'status' => 404,
      ], 404);
    }

    $validatedData = $request->validate([
      'name' => 'required|string',
      'type' => 'required|string',
      'age' => 'nullable|numeric',
      'gender' => 'required|string',
      'description' => 'nullable|string',
      'image' => 'nullable|string',
      'is_purebred' => 'required|boolean',
      'status' => 'boolean',
      'breed_id' => 'required|exists:breeds,id',
      'aid_center_id' => 'nullable|exists:aid_centers,id',
    ]);
    $validatedData['customer_id'] = $customer_id;

    $pet->update($validatedData);

    return response()->json([
      'message' => 'Update pet successfully!',
      'status' => 200,
      'data' => $pet,
    ], 200);
  }

  public function destroy($pet_id)
  {
    $customer_id = auth()->user()->id;

    $pet = Pet::where('id', $pet_id)
      ->where('customer_id', $customer_id)
      ->first();

    if (!$pet) {
      return response()->json([
        'message' => 'Pet not found or does not belong to the customer',
        'status' => 404,
      ], 404);
    }

    $pet->delete();

    return response()->json([
      'message' => 'Delete pet successfully!',
      'status' => 200,
    ], 200);
  }

  public function restore($id)
  {
    $customer_id = auth()->user()->id;

    try {
      $pet = Pet::where('customer_id', $customer_id)->onlyTrashed()->findOrFail($id);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'message' => 'Pet not found or does not belong to the customer',
        'status' => 404
      ], 404);
    }

    $pet->restore();

    return response()->json([
      'message' => 'Restore pet successfully!',
      'status' => 200,
      'data' => $pet,
    ], 200);
  }

  public function getDeletedPet(Request $request)
  {
    $customer_id = auth()->user()->id;

    $page_number = intval($request->query('page_number', 1));
    $num_of_page = intval($request->query('num_of_page', 10));

    $query = Pet::query()->with(['breed', 'customer'])->where('customer_id', $customer_id);

    $pets = $query->onlyTrashed()->paginate($num_of_page, ['*'], 'page', $page_number);

    $formatted_pets = [];
    foreach ($pets as $pet) {
      $formatted_pets[] = [
        'id' => $pet->id,
        'name' => $pet->name,
        'type' => $pet->type,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'description' => $pet->description,
        'image' => $pet->image,
        'is_purebred' => $pet->is_purebred,
        'status' => $pet->status,
        'created_at' => $pet->created_at,
        'updated_at' => $pet->updated_at,
        'deleted_at' => $pet->deleted_at,
        'breed' => [
          'breed_id' => $pet->breed->id,
          'name' => $pet->breed->name,
          'type' => $pet->breed->type,
          'description' => $pet->breed->description,
          'image' => $pet->breed->image,
          'origin' => $pet->breed->origin,
          'lifespan' => $pet->breed->lifespan,
          'average_size' => $pet->breed->average_size,
        ],
      ];
    }

    return response()->json([
      'message' => 'Fetch deleted pets successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $pets->total(),
        'per_page' => $pets->perPage(),
        'current_page' => $pets->currentPage(),
        'last_page' => $pets->lastPage(),
        'from' => $pets->firstItem(),
        'to' => $pets->lastItem(),
      ],
      'data' => $formatted_pets,
    ], 200);
  }

  // ================================     For Aid Center     ================================
  public function searchAdoptedPet(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $searchTerm = $request->query('search_term', '');

    $query = Pet::with(['breed', 'historyAdoptions.customer', 'customer.account'])
      ->where('aid_center_id', $aidCenterId)
      ->where('status', 1)
      ->where(function ($query) use ($searchTerm) {
        $query->where('name', 'LIKE', "%{$searchTerm}%")
          ->orWhereHas('customer', function ($customerQuery) use ($searchTerm) {
            $customerQuery->where('full_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('phone', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('account', function ($accountQuery) use ($searchTerm) {
                $accountQuery->where('email', 'LIKE', "%{$searchTerm}%");
              });
          });
      });

    $pets = $query->paginate($numOfPage, ['*'], 'page', $pageNumber);

    $formattedPets = [];
    foreach ($pets as $pet) {
      $formattedPets[] = [
        'id' => $pet->id,
        'name' => $pet->name,
        'type' => $pet->type,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'description' => $pet->description,
        'image' => $pet->image,
        'is_purebred' => $pet->is_purebred,
        'status' => $pet->status,
        'created_at' => $pet->created_at,
        'updated_at' => $pet->updated_at,
        'breed' => $pet->breed ? [
          'breed_id' => $pet->breed->id,
          'name' => $pet->breed->name,
          'type' => $pet->breed->type,
          'description' => $pet->breed->description,
          'image' => $pet->breed->image,
          'origin' => $pet->breed->origin,
          'lifespan' => $pet->breed->lifespan,
          'average_size' => $pet->breed->average_size,
        ] : null,
        'customer' => $pet->historyAdoptions ? [
          'customer_id' => $pet->historyAdoptions->customer->id,
          'full_name' => $pet->historyAdoptions->customer->full_name,
          'username' => $pet->historyAdoptions->customer->account->username,
          'email' => $pet->historyAdoptions->customer->account->email,
          'avatar' => $pet->historyAdoptions->customer->account->avatar,
          'phone' => $pet->historyAdoptions->customer->phone,
        ] : null,
      ];
    }

    return response()->json([
      'message' => 'Adopted pets retrieved successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $pets->total(),
        'per_page' => $pets->perPage(),
        'current_page' => $pets->currentPage(),
        'last_page' => $pets->lastPage(),
        'from' => $pets->firstItem(),
        'to' => $pets->lastItem(),
      ],
      'data' => $formattedPets,
    ]);
  }

  public function searchUnadoptedPet(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $searchTerm = $request->query('search_term', '');

    $query = Pet::where('aid_center_id', $aidCenterId)
      ->where('status', 0)
      ->where(function ($query) use ($searchTerm) {
        $query->where('name', 'LIKE', "%{$searchTerm}%");
      });

    $pets = $query->paginate($numOfPage, ['*'], 'page', $pageNumber);

    $formattedPets = [];
    foreach ($pets as $pet) {
      $formattedPets[] = [
        'id' => $pet->id,
        'name' => $pet->name,
        'type' => $pet->type,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'description' => $pet->description,
        'image' => $pet->image,
        'is_purebred' => $pet->is_purebred,
        'status' => $pet->status,
        'created_at' => $pet->created_at,
        'updated_at' => $pet->updated_at,
        'breed' => $pet->breed ? [
          'breed_id' => $pet->breed->id,
          'name' => $pet->breed->name,
          'type' => $pet->breed->type,
          'description' => $pet->breed->description,
          'image' => $pet->breed->image,
          'origin' => $pet->breed->origin,
          'lifespan' => $pet->breed->lifespan,
          'average_size' => $pet->breed->average_size,
        ] : null,
      ];
    }

    return response()->json([
      'message' => 'Unadopted pets retrieved successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $pets->total(),
        'per_page' => $pets->perPage(),
        'current_page' => $pets->currentPage(),
        'last_page' => $pets->lastPage(),
        'from' => $pets->firstItem(),
        'to' => $pets->lastItem(),
      ],
      'data' => $formattedPets,
    ]);
  }

  public function searchDeletedPet(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $searchTerm = $request->query('search_term', '');

    $pets = Pet::onlyTrashed('deleted_at')
      ->where('aid_center_id', $aidCenterId)
      ->where(function ($query) use ($searchTerm) {
        $query->where('name', 'LIKE', "%{$searchTerm}%");
      })
      ->paginate($numOfPage, ['*'], 'page', $pageNumber);

    $formattedPets = [];
    foreach ($pets as $pet) {
      $formattedPets[] = [
        'id' => $pet->id,
        'name' => $pet->name,
        'type' => $pet->type,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'description' => $pet->description,
        'image' => $pet->image,
        'is_purebred' => $pet->is_purebred,
        'status' => $pet->status,
        'created_at' => $pet->created_at,
        'updated_at' => $pet->updated_at,
        'breed' => $pet->breed ? [
          'breed_id' => $pet->breed->id,
          'name' => $pet->breed->name,
          'type' => $pet->breed->type,
          'description' => $pet->breed->description,
          'image' => $pet->breed->image,
          'origin' => $pet->breed->origin,
          'lifespan' => $pet->breed->lifespan,
          'average_size' => $pet->breed->average_size,
        ] : null,
      ];
    }

    return response()->json([
      'message' => 'Deleted pets retrieved successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $pets->total(),
        'per_page' => $pets->perPage(),
        'current_page' => $pets->currentPage(),
        'last_page' => $pets->lastPage(),
        'from' => $pets->firstItem(),
        'to' => $pets->lastItem(),
      ],
      'data' => $formattedPets,
    ]);
  }

  public function getAdoptedPets(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $filter = strtolower($request->query('filter', 'all'));

    $query = Pet::with(['breed', 'historyAdoptions.customer', 'customer.account'])
      ->where('aid_center_id', $aidCenterId)
      ->where('status', 1);

    if ($filter !== 'all') {
      $query->where('type', $filter);
    }

    $pets = $query->paginate($numOfPage, ['*'], 'page', $pageNumber);

    $formattedPets = [];
    foreach ($pets as $pet) {
      $formattedPets[] = [
        'id' => $pet->id,
        'name' => $pet->name,
        'type' => $pet->type,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'description' => $pet->description,
        'image' => $pet->image,
        'is_purebred' => $pet->is_purebred,
        'status' => $pet->status,
        'created_at' => $pet->created_at,
        'updated_at' => $pet->updated_at,
        'breed' => $pet->breed ? [
          'breed_id' => $pet->breed->id,
          'name' => $pet->breed->name,
          'type' => $pet->breed->type,
          'description' => $pet->breed->description,
          'image' => $pet->breed->image,
          'origin' => $pet->breed->origin,
          'lifespan' => $pet->breed->lifespan,
          'average_size' => $pet->breed->average_size,
        ] : null,
        'customer' => $pet->historyAdoptions ? [
          'customer_id' => $pet->historyAdoptions->customer->id,
          'full_name' => $pet->historyAdoptions->customer->full_name,
          'phone' => $pet->historyAdoptions->customer->phone,
          'username' => $pet->historyAdoptions->customer->account->username,
          'email' => $pet->historyAdoptions->customer->account->email,
          'avatar' => $pet->historyAdoptions->customer->account->avatar,
        ] : null,
      ];
    }

    return response()->json([
      'message' => 'Adopted pets retrieved successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $pets->total(),
        'per_page' => $pets->perPage(),
        'current_page' => $pets->currentPage(),
        'last_page' => $pets->lastPage(),
        'from' => $pets->firstItem(),
        'to' => $pets->lastItem(),
      ],
      'data' => $formattedPets,
    ]);
  }

  public function getUnadoptedPets(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $filter = strtolower($request->query('filter', 'all'));

    $query = Pet::with(['breed'])
      ->where('aid_center_id', $aidCenterId)
      ->where('status', 0);

    if ($filter !== 'all') {
      $query->where('type', $filter);
    }

    $pets = $query->paginate($numOfPage, ['*'], 'page', $pageNumber);

    $formattedPets = [];
    foreach ($pets as $pet) {
      $formattedPets[] = [
        'id' => $pet->id,
        'name' => $pet->name,
        'type' => $pet->type,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'description' => $pet->description,
        'image' => $pet->image,
        'is_purebred' => $pet->is_purebred,
        'status' => $pet->status,
        'created_at' => $pet->created_at,
        'updated_at' => $pet->updated_at,
        'breed' => $pet->breed ? [
          'breed_id' => $pet->breed->id,
          'name' => $pet->breed->name,
          'type' => $pet->breed->type,
          'description' => $pet->breed->description,
          'image' => $pet->breed->image,
          'origin' => $pet->breed->origin,
          'lifespan' => $pet->breed->lifespan,
          'average_size' => $pet->breed->average_size,
        ] : null,
      ];
    }

    return response()->json([
      'message' => 'Unadopted pets retrieved successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $pets->total(),
        'per_page' => $pets->perPage(),
        'current_page' => $pets->currentPage(),
        'last_page' => $pets->lastPage(),
        'from' => $pets->firstItem(),
        'to' => $pets->lastItem(),
      ],
      'data' => $formattedPets,
    ]);
  }

  public function getDeletedPets(Request $request)
  {
    $aidCenterId = auth()->user()->aidCenter->id;
    $pageNumber = intval($request->query('page_number', 1));
    $numOfPage = intval($request->query('num_of_page', 10));
    $filter = strtolower($request->query('filter', 'all'));

    $query = Pet::with(['breed'])
      ->onlyTrashed('deleted_at')
      ->where('aid_center_id', $aidCenterId);

    if ($filter !== 'all') {
      $query->where('type', $filter);
    }

    $pets = $query->paginate($numOfPage, ['*'], 'page', $pageNumber);

    $formattedPets = [];
    foreach ($pets as $pet) {
      $formattedPets[] = [
        'id' => $pet->id,
        'name' => $pet->name,
        'type' => $pet->type,
        'age' => $pet->age,
        'gender' => $pet->gender,
        'description' => $pet->description,
        'image' => $pet->image,
        'is_purebred' => $pet->is_purebred,
        'status' => $pet->status,
        'created_at' => $pet->created_at,
        'updated_at' => $pet->updated_at,
        'deleted_at' => $pet->deleted_at,
        'breed' => $pet->breed ? [
          'breed_id' => $pet->breed->id,
          'name' => $pet->breed->name,
          'type' => $pet->breed->type,
          'description' => $pet->breed->description,
          'image' => $pet->breed->image,
          'origin' => $pet->breed->origin,
          'lifespan' => $pet->breed->lifespan,
          'average_size' => $pet->breed->average_size,
        ] : null,
      ];
    }

    return response()->json([
      'message' => 'Deleted pets retrieved successfully!',
      'status' => 200,
      'pagination' => [
        'total' => $pets->total(),
        'per_page' => $pets->perPage(),
        'current_page' => $pets->currentPage(),
        'last_page' => $pets->lastPage(),
        'from' => $pets->firstItem(),
        'to' => $pets->lastItem(),
      ],
      'data' => $formattedPets,
    ]);
  }
}
