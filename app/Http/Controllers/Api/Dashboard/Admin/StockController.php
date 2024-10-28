<?php

namespace App\Http\Controllers\Api\Dashboard\Admin;

use Auth;
use Exception;
use App\Models\Brand;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Stock\StockResource;
use App\Http\Requests\Dashboard\Admin\Stock\StoreRequest;
use App\Http\Requests\Dashboard\Admin\Stock\UpdateRequest;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('demo', ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return JsonResponse
     * @throws Exception
     */


     public function index(Request $request): JsonResponse
     {
         $sort = json_decode($request->get('sort', json_encode(['order' => 'asc', 'column' => 'created_at'], JSON_THROW_ON_ERROR)), true, 512, JSON_THROW_ON_ERROR);

         $items = Stock::query() // Start with query instead of all
             ->orderBy($sort['column'], $sort['order'])
             ->paginate((int) $request->get('perPage', 10));

         return response()->json([
             'items' => StockResource::collection($items->items()),
             'pagination' => [
                 'currentPage' => $items->currentPage(),
                 'perPage' => $items->perPage(),
                 'total' => $items->total(),
                 'totalPages' => $items->lastPage()
             ]
         ]);
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
        $stock = new Stock();
        $stock->name = $request->get('name');
        $stock->details = $request->get('details');
        $stock->brand_id = $request->get('brand_id');
        $stock->count = $request->get('count');
        if ($stock->save()) {
            return response()->json(['message' => __('Data saved correctly'), 'stock' => new StockResource($stock)]);
        }
        return response()->json(['message' => __('An error occurred while saving data')], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  Stock  $stock
     * @return JsonResponse
     */
    public function show(Stock $stock): JsonResponse
    {

        return response()->json(new StockResource($stock));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  Stock  $stock
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Stock $stock): JsonResponse
    {
        $request->validated();
        $stock->name = $request->get('name');
        $stock->details = $request->get('details');
        $stock->brand_id = $request->get('brand_id');
        $stock->count = $request->get('count');
        if ($stock->save()) {
            return response()->json(['message' => 'Data updated correctly', 'stock' => new StockResource($stock)]);
        }
        return response()->json(['message' => __('An error occurred while saving data')], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Stock  $stock
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Stock $stock): JsonResponse
    {
        /** @var Stock $stock */
        if ($stock->delete()) {
            return response()->json(['message' => 'Data deleted successfully']);
        }
        return response()->json(['message' => __('An error occurred while deleting data')], 500);
    }

    public function brands(): JsonResponse
    {
        return response()->json(BrandResource::collection(Brand::all()));
    }

}
