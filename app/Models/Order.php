<?php

namespace App\Models;

use App\Models\Branch;
use Eloquent;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use Filterable, HasFactory;

    protected $casts = [
        'branches_id' => 'integer',
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

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function ordersType(): BelongsTo
    {
        return $this->belongsTo(OrdersType::class);
    }

    public function orderReplies(): HasMany
    {
        return $this->hasMany(OrderReply::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifyUser(User $user): bool
    {
        if ($user->role_id !== 1) {
            $userId = $user->id;
            return $this->branches_id === null || ($this->branches->all_agents || $this->branches->agent()->pluck('id')->contains($userId)) || ($this->agent_id === null || $this->agent_id === $userId) || $this->closed_by === $userId;
        }
        return true;
    }
}
