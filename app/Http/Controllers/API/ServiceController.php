<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MedicalCenter;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $query = Service::query();

        $services = $query->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $services,
        ], 200);
    }

    public function show($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json([
                'message' => 'Product not found!',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $service,
        ], 200);
    }

    public function getListServiceByMedicalCenterId($medical_center_id)
    {
        if (!MedicalCenter::find($medical_center_id)) {
            return response()->json([
                'message' => 'Medical Center not found!',
                'status' => 404
            ], 404);
        }

        $services = Service::where('medical_center_id', $medical_center_id)->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $services,
        ], 200);
    }

    public function getNumberOfServiceByMedicalCenterId($medical_center_id)
    {
        if (!MedicalCenter::find($medical_center_id)) {
            return response()->json([
                'message' => 'Medical Center not found!',
                'status' => 404
            ], 404);
        }

        $number_of_services = Service::where('medical_center_id', $medical_center_id)->count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $number_of_services,
        ], 200);
    }

    public function getListServiceByCategoryId($service_category_id)
    {
        if (!ServiceCategory::find($service_category_id)) {
            return response()->json([
                'message' => 'Service Category not found!',
                'status' => 404
            ], 404);
        }

        $services = Service::where('service_category_id', $service_category_id)->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $services,
        ], 200);
    }

    public function getNumberOfServiceByCategoryId($service_category_id)
    {
        if (!ServiceCategory::find($service_category_id)) {
            return response()->json([
                'message' => 'Service Category not found!',
                'status' => 404
            ], 404);
        }

        $number_of_services = Service::where('service_category_id', $service_category_id)->count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $number_of_services,
        ], 200);
    }

    public function getListServiceWithMedicalCenterAndCategory($medical_center_id, $service_category_id)
    {
        $medical_center = MedicalCenter::find($medical_center_id);
        if (!$medical_center) {
            return response()->json([
                'message' => 'Medical Center not found!',
                'status' => 404
            ], 404);
        }

        $category = ServiceCategory::find($service_category_id);
        if (!$category) {
            return response()->json([
                'message' => 'Service Category not found!',
                'status' => 404
            ], 404);
        }

        $services = Service::where('medical_center_id', $medical_center_id)
            ->where('service_category_id', $service_category_id)
            ->with('medicalCenter', 'category')
            ->get();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $services,
        ], 200);
    }

    public function getNumberOfServiceWithMedicalCenterAndCategory($medical_center_id, $service_category_id)
    {
        $medical_center = MedicalCenter::find($medical_center_id);
        if (!$medical_center) {
            return response()->json([
                'message' => 'Medical Center not found!',
                'status' => 404
            ], 404);
        }

        $category = ServiceCategory::find($service_category_id);
        if (!$category) {
            return response()->json([
                'message' => 'Service Category not found!',
                'status' => 404
            ], 404);
        }

        $number_of_services = Service::where('medical_center_id', $medical_center_id)
            ->where('service_category_id', $service_category_id)
            ->count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $number_of_services,
        ], 200);
    }

    public function getNumberOfMedicalCetnterProvideServiceByCategory($service_category_id)
    {
        $category = ServiceCategory::find($service_category_id);
        if (!$category) {
            return response()->json([
                'message' => 'Service Category not found!',
                'status' => 404
            ], 404);
        }

        $medical_centers = Service::whereHas('category', function ($query) use ($service_category_id) {
            $query->where('id', $service_category_id);
        })
            ->distinct('medical_center_id')
            ->pluck('medical_center_id');

        $number_of_medical_centers = $medical_centers->count();

        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'data' => $number_of_medical_centers,
        ], 200);
    }

    public function searchService(Request $request)
    {
        $name = $request->input('name');

        $query = Service::query();

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }
        
        $products = $query->get();

        return response()->json($products);
    }

    public function paging(Request $request)
    {
        $categories = [];

        $page_number = intval($request->query('page_number', 1));
        $num_of_page = intval($request->query('num_of_page', 10));
        $target = $request->query('target') ?? 'all';

        if ($target === 'all') {
            // Nếu không có field 'target' được gửi đến, lấy tất cả các danh mục
            $categories = DB::table('service_categories')
                ->pluck('id')
                ->toArray();
        } else {
            // Nếu 'target' là 'dog' hoặc 'cat' thì query dữ liệu từ bảng 'service_categories'
            if ($target === 'dog' || $target === 'cat') {
                $categories = DB::table('service_categories')
                    ->where('target', $target)
                    ->pluck('id')
                    ->toArray();

                // Nếu không có danh mục nào phù hợp thì trả về mảng rỗng
                if (empty($categories)) {
                    return response()->json([
                        'message' => 'Query successfully!',
                        'status' => 200,
                        'data' => []
                    ]);
                }
            }
        }

        $total_services_query = Service::query();

        if (!empty($categories)) {
            $total_services_query->whereIn('service_category_id', $categories);
        }

        $total_services = $total_services_query->count();
        $total_pages = ceil($total_services / $num_of_page);

        $offset = ($page_number - 1) * $num_of_page;

        $services = $total_services_query->with(['medicalCenter', 'category'])
            ->offset($offset)
            ->limit($num_of_page)
            ->get();

        $formatted_services = [];
        foreach ($services as $service) {
            $formatted_services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'image' => $service->image,
                'sold_quantity' => $service->sold_quantity,
                'service_category_id' => $service->service_category_id,
                'rating' => $service->calculateServiceRating($service->id),
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
                'medical_center' => [
                    'id' => $service->medicalCenter->id,
                    'name' => $service->medicalCenter->name,
                    'email' => $service->medicalCenter->account->email,
                    'description' => $service->medicalCenter->description,
                    'image' => $service->medicalCenter->image,
                    'phone' => $service->medicalCenter->phone,
                    'address' => $service->medicalCenter->address,
                    'website' => $service->medicalCenter->website,
                    'fanpage' => $service->medicalCenter->fanpage,
                    'work_time' => $service->medicalCenter->work_time,
                    'establish_year' => $service->medicalCenter->establish_year,
                    'created_at' => $service->medicalCenter->created_at,
                    'updated_at' => $service->medicalCenter->updated_at,
                ],
                'category' => [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                    'target' => $service->category->target,
                    'type' => $service->category->type,
                    'created_at' => $service->category->created_at,
                    'updated_at' => $service->category->updated_at,
                ],
            ];
        }

        // Trả về JSON response
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'page_number' => $page_number,
            'num_of_page' => $num_of_page,
            'total_pages' => $total_pages,
            'total_services' => $total_services,
            'data' => $formatted_services,
        ]);
    }

    public function getBestSellingService(Request $request)
    {
        $categories = [];

        $page_number = intval($request->query('page_number', 1));
        $num_of_page = intval($request->query('num_of_page', 10));
        $target = $request->query('target') ?? 'all';

        if ($target === 'all') {
            // Nếu không có field 'target' được gửi đến, lấy tất cả các danh mục
            $categories = DB::table('service_categories')
                ->pluck('id')
                ->toArray();
        } else {
            // Nếu 'target' là 'dog' hoặc 'cat' thì query dữ liệu từ bảng 'service_categories'
            if ($target === 'dog' || $target === 'cat') {
                $categories = DB::table('service_categories')
                    ->where('target', $target)
                    ->pluck('id')
                    ->toArray();

                // Nếu không có danh mục nào phù hợp thì trả về mảng rỗng
                if (empty($categories)) {
                    return response()->json([
                        'message' => 'Query successfully!',
                        'status' => 200,
                        'data' => []
                    ]);
                }
            }
        }

        $total_services_query = Service::query();

        if (!empty($categories)) {
            $total_services_query->whereIn('service_category_id', $categories);
        }

        $total_services = $total_services_query->count();
        $total_pages = ceil($total_services / $num_of_page);

        $offset = ($page_number - 1) * $num_of_page;

        $services = $total_services_query->with(['medicalCenter', 'category'])
            ->orderBy('sold_quantity', 'desc')
            ->offset($offset)
            ->limit($num_of_page)
            ->get();

        $formatted_services = [];
        foreach ($services as $service) {
            $formatted_services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'image' => $service->image,
                'sold_quantity' => $service->sold_quantity,
                'service_category_id' => $service->service_category_id,
                'rating' => $service->calculateServiceRating($service->id),
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
                'medical_center' => [
                    'id' => $service->medicalCenter->id,
                    'name' => $service->medicalCenter->name,
                    'email' => $service->medicalCenter->account->email,
                    'description' => $service->medicalCenter->description,
                    'image' => $service->medicalCenter->image,
                    'phone' => $service->medicalCenter->phone,
                    'address' => $service->medicalCenter->address,
                    'website' => $service->medicalCenter->website,
                    'fanpage' => $service->medicalCenter->fanpage,
                    'work_time' => $service->medicalCenter->work_time,
                    'establish_year' => $service->medicalCenter->establish_year,
                    'created_at' => $service->medicalCenter->created_at,
                    'updated_at' => $service->medicalCenter->updated_at,
                ],
                'category' => [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                    'target' => $service->category->target,
                    'type' => $service->category->type,
                    'created_at' => $service->category->created_at,
                    'updated_at' => $service->category->updated_at,
                ],
            ];
        }

        // Trả về JSON response
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'page_number' => $page_number,
            'num_of_page' => $num_of_page,
            'total_pages' => $total_pages,
            'total_services' => $total_services,
            'data' => $formatted_services,
        ]);
    }

    public function getBestSellingServiceByMedicalCenter(Request $request, $medical_center_id)
    {
        if (!MedicalCenter::find($medical_center_id)) {
            return response()->json([
                'message' => 'Medical Center not found!',
                'status' => 404
            ], 404);
        }

        $categories = [];

        $page_number = intval($request->query('page_number', 1));
        $num_of_page = intval($request->query('num_of_page', 10));
        $target = $request->query('target') ?? 'all';

        if ($target === 'all') {
            // Nếu không có field 'target' được gửi đến, lấy tất cả các danh mục
            $categories = DB::table('service_categories')
                ->pluck('id')
                ->toArray();
        } else {
            // Nếu 'target' là 'dog' hoặc 'cat' thì query dữ liệu từ bảng 'service_categories'
            if ($target === 'dog' || $target === 'cat') {
                $categories = DB::table('service_categories')
                    ->where('target', $target)
                    ->pluck('id')
                    ->toArray();

                // Nếu không có danh mục nào phù hợp thì trả về mảng rỗng
                if (empty($categories)) {
                    return response()->json([
                        'message' => 'Query successfully!',
                        'status' => 200,
                        'data' => []
                    ]);
                }
            }
        }

        $total_services_query = Service::query()->where('medical_center_id', $medical_center_id);

        if (!empty($categories)) {
            $total_services_query->whereIn('service_category_id', $categories);
        }

        $total_services = $total_services_query->count();
        $total_pages = ceil($total_services / $num_of_page);

        $offset = ($page_number - 1) * $num_of_page;

        $services = $total_services_query->with(['medicalCenter', 'category'])
            ->orderBy('sold_quantity', 'desc')
            ->offset($offset)
            ->limit($num_of_page)
            ->get();

        $formatted_services = [];
        foreach ($services as $service) {
            $formatted_services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'image' => $service->image,
                'sold_quantity' => $service->sold_quantity,
                'service_category_id' => $service->service_category_id,
                'rating' => $service->calculateServiceRating($service->id),
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
                'medical_center' => [
                    'id' => $service->medicalCenter->id,
                    'name' => $service->medicalCenter->name,
                    'email' => $service->medicalCenter->account->email,
                    'description' => $service->medicalCenter->description,
                    'image' => $service->medicalCenter->image,
                    'phone' => $service->medicalCenter->phone,
                    'address' => $service->medicalCenter->address,
                    'website' => $service->medicalCenter->website,
                    'fanpage' => $service->medicalCenter->fanpage,
                    'work_time' => $service->medicalCenter->work_time,
                    'establish_year' => $service->medicalCenter->establish_year,
                    'created_at' => $service->medicalCenter->created_at,
                    'updated_at' => $service->medicalCenter->updated_at,
                ],
                'category' => [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                    'target' => $service->category->target,
                    'type' => $service->category->type,
                    'created_at' => $service->category->created_at,
                    'updated_at' => $service->category->updated_at,
                ],
            ];
        }

        // Trả về JSON response
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'page_number' => $page_number,
            'num_of_page' => $num_of_page,
            'total_pages' => $total_pages,
            'total_services' => $total_services,
            'data' => $formatted_services,
        ]);
    }

    public function getBestSellingServiceByCategory(Request $request, $service_category_id)
    {
        if (!ServiceCategory::find($service_category_id)) {
            return response()->json([
                'message' => 'Service Category not found!',
                'status' => 404
            ], 404);
        }

        $page_number = intval($request->query('page_number', 1));
        $num_of_page = intval($request->query('num_of_page', 10));

        $total_services_query = Service::query()->where('service_category_id', $service_category_id);

        $total_services = $total_services_query->count();
        $total_pages = ceil($total_services / $num_of_page);

        $offset = ($page_number - 1) * $num_of_page;

        $services = $total_services_query->with(['medicalCenter', 'category'])
            ->orderBy('sold_quantity', 'desc')
            ->offset($offset)
            ->limit($num_of_page)
            ->get();

        $formatted_services = [];
        foreach ($services as $service) {
            $formatted_services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'image' => $service->image,
                'sold_quantity' => $service->sold_quantity,
                'service_category_id' => $service->service_category_id,
                'rating' => $service->calculateServiceRating($service->id),
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
                'medical_center' => [
                    'id' => $service->medicalCenter->id,
                    'name' => $service->medicalCenter->name,
                    'email' => $service->medicalCenter->account->email,
                    'description' => $service->medicalCenter->description,
                    'image' => $service->medicalCenter->image,
                    'phone' => $service->medicalCenter->phone,
                    'address' => $service->medicalCenter->address,
                    'website' => $service->medicalCenter->website,
                    'fanpage' => $service->medicalCenter->fanpage,
                    'work_time' => $service->medicalCenter->work_time,
                    'establish_year' => $service->medicalCenter->establish_year,
                    'created_at' => $service->medicalCenter->created_at,
                    'updated_at' => $service->medicalCenter->updated_at,
                ],
                'category' => [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                    'target' => $service->category->target,
                    'type' => $service->category->type,
                    'created_at' => $service->category->created_at,
                    'updated_at' => $service->category->updated_at,
                ],
            ];
        }

        // Trả về JSON response
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'page_number' => $page_number,
            'num_of_page' => $num_of_page,
            'total_pages' => $total_pages,
            'total_services' => $total_services,
            'data' => $formatted_services,
        ]);
    }

    public function getBestSellingServicetWithMedicalCenterAndCategory(Request $request, $medical_center_id, $service_category_id)
    {
        if (!MedicalCenter::find($medical_center_id)) {
            return response()->json([
                'message' => 'Medical Center not found!',
                'status' => 404
            ], 404);
        }

        if (!ServiceCategory::find($service_category_id)) {
            return response()->json([
                'message' => 'Service Category not found!',
                'status' => 404
            ], 404);
        }

        $page_number = intval($request->query('page_number', 1));
        $num_of_page = intval($request->query('num_of_page', 10));

        $total_services_query = Service::query()
            ->where('medical_center_id', $medical_center_id)
            ->where('service_category_id', $service_category_id);

        $total_services = $total_services_query->count();
        $total_pages = ceil($total_services / $num_of_page);

        $offset = ($page_number - 1) * $num_of_page;

        $services = $total_services_query->with(['medicalCenter', 'category'])
            ->orderBy('sold_quantity', 'desc')
            ->offset($offset)
            ->limit($num_of_page)
            ->get();

        $formatted_services = [];
        foreach ($services as $service) {
            $formatted_services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'image' => $service->image,
                'sold_quantity' => $service->sold_quantity,
                'service_category_id' => $service->service_category_id,
                'rating' => $service->calculateServiceRating($service->id),
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
                'medical_center' => [
                    'id' => $service->medicalCenter->id,
                    'name' => $service->medicalCenter->name,
                    'email' => $service->medicalCenter->account->email,
                    'description' => $service->medicalCenter->description,
                    'image' => $service->medicalCenter->image,
                    'phone' => $service->medicalCenter->phone,
                    'address' => $service->medicalCenter->address,
                    'website' => $service->medicalCenter->website,
                    'fanpage' => $service->medicalCenter->fanpage,
                    'work_time' => $service->medicalCenter->work_time,
                    'establish_year' => $service->medicalCenter->establish_year,
                    'created_at' => $service->medicalCenter->created_at,
                    'updated_at' => $service->medicalCenter->updated_at,
                ],
                'category' => [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                    'target' => $service->category->target,
                    'type' => $service->category->type,
                    'created_at' => $service->category->created_at,
                    'updated_at' => $service->category->updated_at,
                ],
            ];
        }

        // Trả về JSON response
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'page_number' => $page_number,
            'num_of_page' => $num_of_page,
            'total_pages' => $total_pages,
            'total_services' => $total_services,
            'data' => $formatted_services,
        ]);
    }

    public function getHighestRatingService(Request $request)
    {
        $categories = [];

        $page_number = intval($request->query('page_number', 1));
        $num_of_page = intval($request->query('num_of_page', 10));
        $target = $request->query('target') ?? 'all';

        if ($target === 'all') {
            $categories = DB::table('service_categories')
                ->pluck('id')
                ->toArray();
        } else {
            if ($target === 'dog' || $target === 'cat') {
                $categories = DB::table('service_categories')
                    ->where('target', $target)
                    ->pluck('id')
                    ->toArray();

                if (empty($categories)) {
                    return response()->json([
                        'message' => 'Query successfully!',
                        'status' => 200,
                        'data' => []
                    ]);
                }
            }
        }

        $total_services_query = Service::query();

        if (!empty($categories)) {
            $total_services_query->whereIn('service_category_id', $categories);
        }

        $total_services = $total_services_query->count();
        $total_pages = ceil($total_services / $num_of_page);
        $offset = ($page_number - 1) * $num_of_page;

        $averageRatings = DB::table('rating_services')
            ->select('service_id', DB::raw('AVG(rating) as average_rating'))
            ->groupBy('service_id');

        $services = $total_services_query->with(['medicalCenter', 'category'])
            ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
                $join->on('services.id', '=', 'average_ratings.service_id');
            })
            ->select('services.*', 'average_ratings.average_rating')
            ->whereIn('service_category_id', $categories)
            ->orderBy('average_rating', 'desc')
            ->offset($offset)
            ->limit($num_of_page)
            ->get();

        $formatted_services = [];
        foreach ($services as $service) {
            $formatted_services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'image' => $service->image,
                'sold_quantity' => $service->sold_quantity,
                'service_category_id' => $service->service_category_id,
                'rating' => $service->calculateServiceRating($service->id),
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
                'medical_center' => [
                    'id' => $service->medicalCenter->id,
                    'name' => $service->medicalCenter->name,
                    'email' => $service->medicalCenter->account->email,
                    'description' => $service->medicalCenter->description,
                    'image' => $service->medicalCenter->image,
                    'phone' => $service->medicalCenter->phone,
                    'address' => $service->medicalCenter->address,
                    'website' => $service->medicalCenter->website,
                    'fanpage' => $service->medicalCenter->fanpage,
                    'work_time' => $service->medicalCenter->work_time,
                    'establish_year' => $service->medicalCenter->establish_year,
                    'created_at' => $service->medicalCenter->created_at,
                    'updated_at' => $service->medicalCenter->updated_at,
                ],
                'category' => [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                    'target' => $service->category->target,
                    'type' => $service->category->type,
                    'created_at' => $service->category->created_at,
                    'updated_at' => $service->category->updated_at,
                ],
            ];
        }

        // Trả về JSON response
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'page_number' => $page_number,
            'num_of_page' => $num_of_page,
            'total_pages' => $total_pages,
            'total_services' => $total_services,
            'data' => $formatted_services,
        ]);
    }

    public function getHighestRatingServiceByMedicalCenter(Request $request, $medical_center_id)
    {
        if (!MedicalCenter::find($medical_center_id)) {
            return response()->json([
                'message' => 'Medical Center not found!',
                'status' => 404
            ], 404);
        }

        $categories = [];

        $page_number = intval($request->query('page_number', 1));
        $num_of_page = intval($request->query('num_of_page', 10));
        $target = $request->query('target') ?? 'all';

        if ($target === 'all') {
            $categories = DB::table('service_categories')
                ->pluck('id')
                ->toArray();
        } else {
            if ($target === 'dog' || $target === 'cat') {
                $categories = DB::table('service_categories')
                    ->where('target', $target)
                    ->pluck('id')
                    ->toArray();

                if (empty($categories)) {
                    return response()->json([
                        'message' => 'Query successfully!',
                        'status' => 200,
                        'data' => []
                    ]);
                }
            }
        }

        $total_services_query = Service::query()->where('medical_center_id', $medical_center_id);

        if (!empty($categories)) {
            $total_services_query->whereIn('service_category_id', $categories);
        }

        $total_services = $total_services_query->count();
        $total_pages = ceil($total_services / $num_of_page);

        $offset = ($page_number - 1) * $num_of_page;

        $averageRatings = DB::table('rating_services')
            ->select('service_id', DB::raw('AVG(rating) as average_rating'))
            ->groupBy('service_id');

        $services = $total_services_query->with(['medicalCenter', 'category'])
            ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
                $join->on('services.id', '=', 'average_ratings.service_id');
            })
            ->select('services.*', 'average_ratings.average_rating')
            ->whereIn('service_category_id', $categories)
            ->orderBy('average_rating', 'desc')
            ->offset($offset)
            ->limit($num_of_page)
            ->get();

        $formatted_services = [];
        foreach ($services as $service) {
            $formatted_services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'image' => $service->image,
                'sold_quantity' => $service->sold_quantity,
                'service_category_id' => $service->service_category_id,
                'rating' => $service->calculateServiceRating($service->id),
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
                'medical_center' => [
                    'id' => $service->medicalCenter->id,
                    'name' => $service->medicalCenter->name,
                    'email' => $service->medicalCenter->account->email,
                    'description' => $service->medicalCenter->description,
                    'image' => $service->medicalCenter->image,
                    'phone' => $service->medicalCenter->phone,
                    'address' => $service->medicalCenter->address,
                    'website' => $service->medicalCenter->website,
                    'fanpage' => $service->medicalCenter->fanpage,
                    'work_time' => $service->medicalCenter->work_time,
                    'establish_year' => $service->medicalCenter->establish_year,
                    'created_at' => $service->medicalCenter->created_at,
                    'updated_at' => $service->medicalCenter->updated_at,
                ],
                'category' => [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                    'target' => $service->category->target,
                    'type' => $service->category->type,
                    'created_at' => $service->category->created_at,
                    'updated_at' => $service->category->updated_at,
                ],
            ];
        }

        // Trả về JSON response
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'page_number' => $page_number,
            'num_of_page' => $num_of_page,
            'total_pages' => $total_pages,
            'total_services' => $total_services,
            'data' => $formatted_services,
        ]);
    }

    public function getHighestRatingServiceByCategory(Request $request, $service_category_id)
    {
        if (!ServiceCategory::find($service_category_id)) {
            return response()->json([
                'message' => 'Service Category not found!',
                'status' => 404
            ], 404);
        }

        $page_number = intval($request->query('page_number', 1));
        $num_of_page = intval($request->query('num_of_page', 10));

        $total_services_query = Service::query()->where('service_category_id', $service_category_id);

        $total_services = $total_services_query->count();
        $total_pages = ceil($total_services / $num_of_page);

        $offset = ($page_number - 1) * $num_of_page;

        $averageRatings = DB::table('rating_services')
            ->select('service_id', DB::raw('AVG(rating) as average_rating'))
            ->groupBy('service_id');

        $services = $total_services_query->with(['medicalCenter', 'category'])
            ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
                $join->on('services.id', '=', 'average_ratings.service_id');
            })
            ->select('services.*', 'average_ratings.average_rating')
            ->orderBy('average_rating', 'desc')
            ->offset($offset)
            ->limit($num_of_page)
            ->get();

        $formatted_services = [];
        foreach ($services as $service) {
            $formatted_services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'image' => $service->image,
                'sold_quantity' => $service->sold_quantity,
                'service_category_id' => $service->service_category_id,
                'rating' => $service->calculateServiceRating($service->id),
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
                'medical_center' => [
                    'id' => $service->medicalCenter->id,
                    'name' => $service->medicalCenter->name,
                    'email' => $service->medicalCenter->account->email,
                    'description' => $service->medicalCenter->description,
                    'image' => $service->medicalCenter->image,
                    'phone' => $service->medicalCenter->phone,
                    'address' => $service->medicalCenter->address,
                    'website' => $service->medicalCenter->website,
                    'fanpage' => $service->medicalCenter->fanpage,
                    'work_time' => $service->medicalCenter->work_time,
                    'establish_year' => $service->medicalCenter->establish_year,
                    'created_at' => $service->medicalCenter->created_at,
                    'updated_at' => $service->medicalCenter->updated_at,
                ],
                'category' => [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                    'target' => $service->category->target,
                    'type' => $service->category->type,
                    'created_at' => $service->category->created_at,
                    'updated_at' => $service->category->updated_at,
                ],
            ];
        }

        // Trả về JSON response
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'page_number' => $page_number,
            'num_of_page' => $num_of_page,
            'total_pages' => $total_pages,
            'total_services' => $total_services,
            'data' => $formatted_services,
        ]);
    }

    public function getHighestRatingServiceWithMedicalCenterAndCategory(Request $request, $medical_center_id, $service_category_id)
    {
        if (!MedicalCenter::find($medical_center_id)) {
            return response()->json([
                'message' => 'Medical Center not found!',
                'status' => 404
            ], 404);
        }

        if (!ServiceCategory::find($service_category_id)) {
            return response()->json([
                'message' => 'Service Category not found!',
                'status' => 404
            ], 404);
        }

        $page_number = intval($request->query('page_number', 1));
        $num_of_page = intval($request->query('num_of_page', 10));

        $total_services_query = Service::query()
            ->where('medical_center_id', $medical_center_id)
            ->where('service_category_id', $service_category_id);

        $total_services = $total_services_query->count();
        $total_pages = ceil($total_services / $num_of_page);

        $offset = ($page_number - 1) * $num_of_page;

        $averageRatings = DB::table('rating_services')
            ->select('service_id', DB::raw('AVG(rating) as average_rating'))
            ->groupBy('service_id');

        $services = $total_services_query->with(['medicalCenter', 'category'])
            ->leftJoinSub($averageRatings, 'average_ratings', function ($join) {
                $join->on('services.id', '=', 'average_ratings.service_id');
            })
            ->select('services.*', 'average_ratings.average_rating')
            ->orderBy('average_rating', 'desc')
            ->offset($offset)
            ->limit($num_of_page)
            ->get();

        $formatted_services = [];
        foreach ($services as $service) {
            $formatted_services[] = [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'image' => $service->image,
                'sold_quantity' => $service->sold_quantity,
                'service_category_id' => $service->service_category_id,
                'rating' => $service->calculateServiceRating($service->id),
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
                'medical_center' => [
                    'id' => $service->medicalCenter->id,
                    'name' => $service->medicalCenter->name,
                    'email' => $service->medicalCenter->account->email,
                    'description' => $service->medicalCenter->description,
                    'image' => $service->medicalCenter->image,
                    'phone' => $service->medicalCenter->phone,
                    'address' => $service->medicalCenter->address,
                    'website' => $service->medicalCenter->website,
                    'fanpage' => $service->medicalCenter->fanpage,
                    'work_time' => $service->medicalCenter->work_time,
                    'establish_year' => $service->medicalCenter->establish_year,
                    'created_at' => $service->medicalCenter->created_at,
                    'updated_at' => $service->medicalCenter->updated_at,
                ],
                'category' => [
                    'id' => $service->category->id,
                    'name' => $service->category->name,
                    'target' => $service->category->target,
                    'type' => $service->category->type,
                    'created_at' => $service->category->created_at,
                    'updated_at' => $service->category->updated_at,
                ],
            ];
        }

        // Trả về JSON response
        return response()->json([
            'message' => 'Query successfully!',
            'status' => 200,
            'page_number' => $page_number,
            'num_of_page' => $num_of_page,
            'total_pages' => $total_pages,
            'total_services' => $total_services,
            'data' => $formatted_services,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|string|url',
            'sold_quantity' => 0,
            'service_category_id' => 'required|exists:service_categories,id',
            'medical_center_id' => 'required|exists:medical_centers,id',
        ]);

        $service = Service::create($data);

        return response()->json([
            'message' => 'Create service successfully!',
            'status' => 201,
            'data' => $service,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $data = $request->validate([
            'name' => 'string',
            'description' => 'string',
            'price' => 'numeric',
            'image' => 'nullable|string|url',
            'service_category_id' => 'exists:service_categories,id',
        ]);

        $service->update($data);

        return response()->json([
            'message' => 'Update service successfully!',
            'status' => 200,
            'data' => $service,
        ], 200);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return response()->json([
            'message' => 'Delete service successfully!',
            'status' => 204,
        ], 204);
    }

    public function restore($id) {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore();
        return response()->json([
            'message' => 'Restore service successfully!',
            'status' => 200,
            'data' => $service,
        ], 200);
    }

    public function getDeletedServices() {
        $services = Service::onlyTrashed()->get();
        return response()->json([
            'message' => 'Fetch deleted services successfully!',
            'status' => 200,
            'data' => $services,
        ], 200);
    }

    public function sortServicesByPrice(Request $request) {
        $orderDirection = $request->query('order');

        if ($orderDirection == null) {
            $orderDirection = 'asc';
        }

        if (!in_array($orderDirection, ['asc', 'desc'])) {
            return response()->json([
                'message' => 'Order direction must be "asc" or "desc".',
                'status' => 400,
            ], 400);
        }

        $services = Service::orderBy('price', $orderDirection)->get();
        return response()->json($services);
    }
}
