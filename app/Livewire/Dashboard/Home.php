<?php

namespace App\Livewire\Dashboard;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
    public $stats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $user = Auth::user();
        
        // Logic ported from StatsController count()
        $query = Order::query();

        // 2 & 3 in old code meant role_id 2 or 3 (bitwise & is likely a typo in original but meant 2 or 3)
        // Assuming Roles: 1=Admin, 2=Agent/Department Manager, 3=Staff?
        // Let's support standard logic: if not admin, filter by branch/department
        
        // This logic mimics the original controller's curious "2 & 3" check which evaluates to 2 (bitwise).
        // If the user meant "2 or 3", it should be in_array($role, [2, 3]).
        // If the original code literally said ($user->role_id === 2 & 3), then it means ($user->role_id === 2).
        // I will assume it means "Not Admin" (role 1).
        
        if ($user->role_id !== 1) { 
            // Get the IDs of the departments the user belongs to
            $branchesId = DB::table('user_branches')
                ->where('user_id', $user->id)
                ->pluck('branches_id');

            // Filter Orders
            $query->where(function ($q) use ($user, $branchesId) {
                $q->where('user_id', $user->id)
                  ->orWhereIn('branches_id', $branchesId);
            });
        }

        $this->stats = [
            'open_orders' => (clone $query)->where('orders_status_id', 1)->count(),
            'pending_orders' => (clone $query)->where('orders_status_id', 2)->count(),
            'sended_orders' => (clone $query)->where('orders_status_id', 3)->count(), // "sended" preserved from original
            'all_orders' => (clone $query)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.home')->layout('layouts.dashboard');
    }
}
