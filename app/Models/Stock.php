<?php

namespace App\Models;

use App\Models\Brand;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class Stock extends Model
{
    use HasFactory;

    protected $casts = [
        'brand_id' => 'integer',
    ];

    public function brands(): BelongsTo
    {
        return $this->belongsTo(Brand::class , 'brand_id');
    }
}
