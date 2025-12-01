<?php

namespace App\Models;

use Eloquent;
use App\Models\Item;
use App\Models\Brand;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'quantity'];

    protected $casts = [
        'brand_id' => 'integer',
    ];

    public function brands(): BelongsTo
    {
        return $this->belongsTo(Brand::class , 'brand_id');
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

}
