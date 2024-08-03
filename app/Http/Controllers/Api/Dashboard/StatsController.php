<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\User;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Language;
use App\Models\UserRole;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function count(): JsonResponse
    {
        $user = Auth::user();
        $query = Order::query();
    if ($user->role_id !== 1) {
        // Get the IDs of the departments the user belongs to
        $branchesId = DB::table('user_branches')
        ->where('user_id', $user->id)
        ->pluck('branches_id');

    // Filter Orders belonging to the user or their department
    $query->where(function ($query) use ($user, $branchesId) {
        $query->where('user_id', $user->id)
            ->orWhereIn('branches_id', $branchesId);
    });

        return response()->json([
            'open_orders' => (clone $query)->where('orders_status_id', 1)->count(),
            'pending_orders' => (clone $query)->where('orders_status_id', 2)->count(),
            'sended_orders' => (clone $query)->where('orders_status_id', 3)->count(),
            'without_agent' => (clone $query)->whereNull('agent_id')->count(),
        ]);
    }else{
        return response()->json([
            'open_orders' => Order::where('orders_status_id', 1)->count(),
            'pending_orders' => Order::where('orders_status_id', 2)->count(),
            'sended_orders' => Order::where('orders_status_id', 3)->count(),
            'without_agent' => Order::whereNull('agent_id')->count(),
        ]);
    }
    }

    public function registeredUsers(): JsonResponse
    {
        $graph = [];
        $month = 1;
        while ($month <= 12) {
            $graph[] = User::whereMonth('created_at', '=', $month)->count();
            $month++;
        }
        return response()->json($graph);
    }

    public function openedOrders(): JsonResponse
    {
        $graph = [];
        $month = 1;
        while ($month <= 12) {
            $graph[] = Order::whereMonth('created_at', '=', $month)->count();
            $month++;
        }
        return response()->json($graph);
    }
}
