<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(SrderStatus::class);
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
