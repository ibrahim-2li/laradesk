<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\OrderReply
 *
 * @property int $id
 * @property int|null $ticket_id
 * @property int|null $user_id
 * @property string $body
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Order|null $Order
 * @property-read Collection|\App\Models\File[] $OrderAttachments
 * @property-read int|null $ticket_attachments_count
 * @property-read \App\Models\User|null $user
 * @method static Builder|OrderReply newModelQuery()
 * @method static Builder|OrderReply newQuery()
 * @method static Builder|OrderReply query()
 * @method static Builder|OrderReply whereBody($value)
 * @method static Builder|OrderReply whereCreatedAt($value)
 * @method static Builder|OrderReply whereId($value)
 * @method static Builder|OrderReply whereTicketId($value)
 * @method static Builder|OrderReply whereUpdatedAt($value)
 * @method static Builder|OrderReply whereUserId($value)
 * @mixin Eloquent
 */

class OrderReply extends Model
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
        return $this->belongsToMany(File::class, 'order_attachments');
    }
}
