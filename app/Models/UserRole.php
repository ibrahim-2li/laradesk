<?php

namespace App\Models;

use Eloquent;
use EloquentFilter\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Route;

/**
 * App\Models\UserRole
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property array|null $permissions
 * @property int $dashboard_access
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static Builder|UserRole filter($input = [], $filter = null)
 * @method static Builder|UserRole newModelQuery()
 * @method static Builder|UserRole newQuery()
 * @method static Builder|UserRole paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static Builder|UserRole query()
 * @method static Builder|UserRole simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static Builder|UserRole whereBeginsWith($column, $value, $boolean = 'and')
 * @method static Builder|UserRole whereCreatedAt($value)
 * @method static Builder|UserRole whereDashboardAccess($value)
 * @method static Builder|UserRole whereEndsWith($column, $value, $boolean = 'and')
 * @method static Builder|UserRole whereId($value)
 * @method static Builder|UserRole whereLike($column, $value, $boolean = 'and')
 * @method static Builder|UserRole whereName($value)
 * @method static Builder|UserRole wherePermissions($value)
 * @method static Builder|UserRole whereType($value)
 * @method static Builder|UserRole whereUpdatedAt($value)
 * @mixin Eloquent
 */
class UserRole extends Model
{
    use HasFactory, Filterable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'json',
    ];

    protected $fillable = [
        'name',
        'type',
        'dashboard_access',
        'permissions',
    ];

    /**
     * Get the users for the user role
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * @param $route
     * @return bool
     * @throws Exception
     */
    public function checkPermission($route): bool
    {
        if ($this->id === 1) {
            return true;
        }
        if (!$this->checkDashboardAccess()) {
            return false;
        }

        $permissions = $this->permissions ?? [];
        return in_array($route, $permissions, true);
    }

    /**
     * @return bool
     */
    public function checkDashboardAccess(): bool
    {
        if ($this->id === 1) {
            return true;
        }

        return (bool) $this->dashboard_access;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getPermissions(): array
    {
        $controllers = [];
        $permissions = $this->permissions ?? [];
        
        foreach (Route::getRoutes()->getIterator() as $route) {
            if (strpos($route->uri, 'api/dashboard') !== false) {
                // Safely handle action controller array/string
                $action = $route->action['controller'] ?? '';
                if (!$action) continue;

                $path = str_replace('\\', '.', explode('@', str_replace($action.'\\', '', $action))[0]);
                $controllers[$path] = $this->id === 1 ? true : in_array($path, $permissions, true);
            }
        }
        return $controllers;
    }
}
