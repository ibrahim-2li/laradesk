<?php

namespace App\Filters;

use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function search($search): OrderFilter
    {
        return $this->where('subject', 'LIKE', '%'.$search.'%')
            ->orWhereHas('orderReplies', function (Builder $query) use ($search) {
                $query->where('body', 'LIKE', '%'.$search.'%');
            });
    }

    public function user($user): OrderFilter
    {
        return $this->whereHas('user', function (Builder $query) use ($user) {
            $query->where('name', 'LIKE', '%'.$user.'%')
                ->orWhere('email', 'LIKE', '%'.$user.'%');
        });
    }

    public function agents($agents): OrderFilter
    {
        return $this->whereIn('agent_id', $agents);
    }

    public function branches($branches): OrderFilter
    {
        return $this->whereIn('branches_id', $branches);
    }


    public function status($status): OrderFilter
    {
        return $this->where('orders_status_id', '=', $status);
    }

    public function statuses($statuses): OrderFilter
    {
        return $this->whereIn('orders_status_id', $statuses);
    }

}
