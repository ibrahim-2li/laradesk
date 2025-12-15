<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Branch
 *
 * @property int $id
 * @property string $name
 * @property int $all_agents
 * @property int $public
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|User[] $agent
 * @property-read int|null $agent_count
 * @method static Builder|Branch newModelQuery()
 * @method static Builder|Branch newQuery()
 * @method static Builder|Branch query()
 * @method static Builder|Branch whereAllAgents($value)
 * @method static Builder|Branch whereCreatedAt($value)
 * @method static Builder|Branch whereId($value)
 * @method static Builder|Branch whereName($value)
 * @method static Builder|Branch wherePublic($value)
 * @method static Builder|Branch whereUpdatedAt($value)
 * @mixin Eloquent
 */

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'all_agents',
        'public',
    ];

    public function agent(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_branches', 'branches_id', 'user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_branches', 'branches_id', 'user_id');
    }
    public function agents()
    {
        if (!$this->all_agents) {
            return $this->agent->all();
        }
        return User::whereIn('role_id', UserRole::where('dashboard_access', true)->pluck('id'))
            ->where('status', true)
            ->get();
    }
}
