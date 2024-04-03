<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Setting;
use App\Models\Language;
use App\Models\UserRole;
use App\Models\Department;
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
        $query = Ticket::query();
    if ($user->role_id !== 1) {
        // Get the IDs of the departments the user belongs to
        $departmentIds = DB::table('user_departments')
        ->where('user_id', $user->id)
        ->pluck('department_id');

    // Filter tickets belonging to the user or their department
    $query->where(function ($query) use ($user, $departmentIds) {
        $query->where('user_id', $user->id)
            ->orWhereIn('department_id', $departmentIds);
    });

        return response()->json([
            'open_tickets' => (clone $query)->where('status_id', 1)->count(),
            'pending_tickets' => (clone $query)->where('status_id', 2)->count(),
            'solved_tickets' => (clone $query)->whereIn('status_id', [3, 4])->count(),
            'without_agent' => (clone $query)->whereNull('agent_id')->count(),
        ]);
    }else{
        return response()->json([
            'open_tickets' => Ticket::where('status_id', 1)->count(),
            'pending_tickets' => Ticket::where('status_id', 2)->count(),
            'solved_tickets' => Ticket::whereIn('status_id', [3, 4])->count(),
            'without_agent' => Ticket::whereNull('agent_id')->count(),
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

    public function openedTickets(): JsonResponse
    {
        $graph = [];
        $month = 1;
        while ($month <= 12) {
            $graph[] = Ticket::whereMonth('created_at', '=', $month)->count();
            $month++;
        }
        return response()->json($graph);
    }
}
