<?php

namespace App\Http\Controllers\Api\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\Brand\StoreRequest;
use App\Http\Requests\Dashboard\Admin\Brand\UpdateRequest;
use App\Http\Resources\Brand\BrandResource;
use App\Models\Brand;
use Exception;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(BrandResource::collection(Brand::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $request->validated();
        $brand = new Brand();
        $brand->name = $request->get('name');
        if ($brand->save()) {
            return response()->json(['message' => 'Data saved correctly', 'brand' => new BrandResource($brand)]);
        }
        return response()->json(['message' => __('An error occurred while saving data')], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  Brand  $brand
     * @return JsonResponse
     */
    public function show(Brand $brand): JsonResponse
    {
        return response()->json(new BrandResource($brand));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  Brand  $brand
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Brand $brand): JsonResponse
    {
        $request->validated();
        $brand->name = $request->get('name');
        if ($brand->save()) {
            return response()->json(['message' => 'Data updated correctly', 'brand' => new BrandResource($brand)]);
        }
        return response()->json(['message' => __('An error occurred while saving data')], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Brand  $brand
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Brand $brand): JsonResponse
    {
        if ($brand->delete()) {
            return response()->json(['message' => 'Data deleted successfully']);
        }
        return response()->json(['message' => __('An error occurred while deleting data')], 500);
    }
}
