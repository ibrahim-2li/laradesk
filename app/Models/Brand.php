<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class Brand extends Model
{
    use HasFactory;

    public function stocks(): BelongsToMany
    {
        return $this->belongsToMany(Stock::class, 'brand_id');
    }
}
