<?php

namespace App\Http\Controllers\Api\Dashboard\Admin;

use Exception;
use App\Models\User;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Ticket;
use App\Models\UserRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Branch\BranchResource;
use App\Http\Resources\User\UserDetailsResource;
use App\Http\Resources\Branch\BranchDetailsResource;
use App\Http\Requests\Dashboard\Admin\Branch\StoreRequest;
use App\Http\Requests\Dashboard\Admin\Branch\UpdateRequest;

class BranchController extends Controller
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
        return response()->json(BranchDetailsResource::collection(Branch::all()));
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
        $branche = new Branch();
        $branche->name = $request->get('name');
        $branche->all_agents = $request->get('all_agents');
        $branche->public = $request->get('public');
        $agents = [];
        if (!$branche->all_agents) {
            $agents = $request->get('agents');
        }
        if ($branche->save()) {
            $branche->agent()->sync($agents);
            return response()->json(['message' => 'Data saved correctly', 'branche' => new BranchResource($branche)]);
        }
        return response()->json(['message' => __('An error occurred while saving data')], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  Branch  $branche
     * @return JsonResponse
     */
    public function show(Branch $branch): JsonResponse
    {
        return response()->json(new BranchResource($branch));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  Branch  $branche
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, Branch $branch): JsonResponse
    {
        $request->validated();
        $branch->name = $request->get('name');
        $branch->all_agents = $request->get('all_agents');
        $branch->public = $request->get('public');
        $agents = [];
        if (!$branch->all_agents) {
            $agents = $request->get('agents');
        }
        if ($branch->save()) {
            $branch->agent()->sync($agents);
            return response()->json(['message' => 'Data updated correctly', 'branch' => new BranchResource($branch)]);
        }
        return response()->json(['message' => __('An error occurred while saving data')], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Branch  $branch
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Branch $branch): JsonResponse
    {
        Order::where('branches_id', $branch->id)->update(['branches_id' => null]);
       // DB::table('user_branches')->where('branches_id',$branche->id)->update(['branches_id' => null]);
        $branch->agent()->sync([]);
        //$branche->delete();
        if ($branch->delete()) {
            return response()->json(['message' => 'Data deleted successfully']);
        }
        return response()->json(['message' => __('An error occurred while deleting data')], 500);
    }

    public function users()
    {
        $roles = UserRole::where('dashboard_access', true)->pluck('id');
        $agents = User::whereIn('role_id', $roles)->where('status', true)->get();
        return response()->json(UserDetailsResource::collection($agents));
    }
}
