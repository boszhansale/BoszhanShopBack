<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Moving
 *
 * @property int $id
 * @property int $status
 * @property int $operation
 * @property int $user_id
 * @property int|null $store_id
 * @property int|null $storage_id
 * @property string|null $total_price
 * @property string|null $removed_at
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MovingProduct> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Storage|null $storage
 * @property-read \App\Models\Store|null $store
 * @method static \Illuminate\Database\Eloquent\Builder|Moving newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Moving newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Moving query()
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereOperation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereRemovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereStorageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereUserId($value)
 * @property int|null $order_id
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Moving whereOrderId($value)
 * @mixin \Eloquent
 */
class Moving extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'store_id',
        'status',
        'storage_id',
        'order_id',
        'user_id',
        'total_price',
        'removed_at',
        'operation',
    ];

    const OPERATION = [
        1 => 'с склада',//in shop out storage
        2 => 'на склад'// in storage out shop
    ];
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(MovingProduct::class);
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
