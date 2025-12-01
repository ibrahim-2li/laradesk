<?php

namespace App\Models;

use Eloquent;
use App\Models\Stock;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderAttachments(): BelongsToMany
    {
        return $this->belongsToMany(File::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'item', 'id'); // 'item' is the foreign key, 'id' is the primary key in Stock
    }
}
