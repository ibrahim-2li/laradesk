<?php

namespace App\Livewire\Orders;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListOrders extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $query = Order::query()
            ->where('user_id', Auth::id())
            ->with(['status', 'priority', 'branches']); // Note: relation is 'branches' in Model

        if ($this->search) {
            $query->where('subject', 'like', '%' . $this->search . '%')
                  ->orWhere('id', 'like', '%' . $this->search . '%');
        }

        $orders = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('livewire.orders.list-orders', [
            'orders' => $orders
        ])->layout('layouts.dashboard');
    }
}
