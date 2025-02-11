<?php

namespace App\Models;

use Eloquent;
use App\Models\Item;
use App\Models\Branch;
use App\Models\ItemConfirm;
use EloquentFilter\Filterable;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use Filterable, HasFactory;

    protected $casts = [

        'orders_status_id' => 'integer',
        'priority_id' => 'integer',
        'branches_id' => 'integer',
        'user_id' => 'integer',
        'agent_id' => 'integer',
        'closed_at' => 'datetime'

    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }



    public function Status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class , 'orders_status_id');
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    public function branches(): BelongsTo
    {
        return $this->belongsTo(Branch::class , 'branches_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderReplies(): HasMany
    {
        return $this->hasMany(OrderReply::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function confirmItems(): HasMany
    {
        return $this->hasMany(ItemConfirm::class);
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'order_labels');
    }

    public function verifyUser(User $user): bool
    {
        if ($user->role_id !== 1) {
            $userId = $user->id;
            return $this->branch_id === null || ($this->branch->all_agents || $this->branch->agent()->pluck('id')->contains($userId)) || ($this->agent_id === null || $this->agent_id === $userId) || $this->closed_by === $userId;
        }
        return true;
    }
}
