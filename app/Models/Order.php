<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use Filterable, HasFactory;

    protected $casts = [
        'branch_id' => 'integer',
        'order_status_id' => 'integer',
        'orders_type_id' => 'integer',
        'user_id' => 'integer',
        'closed_at' => 'datetime'

    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function ordersType(): BelongsTo
    {
        return $this->belongsTo(OrdersType::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
