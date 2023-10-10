<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Store
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string|null $lat
 * @property string|null $lng
 * @property float $discount
 * @property int $enabled
 * @property string|null $removed_at
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store query()
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereRemovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereUpdatedAt($value)
 * @property int $discount_position
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereDiscountPosition($value)
 * @property int|null $counteragent_id
 * @property-read \App\Models\Counteragent|null $counteragent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Refund> $refunds
 * @property-read int|null $refunds_count
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCounteragentId($value)
 * @mixin \Eloquent
 */
class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'discount',
        'name',
        'enabled',
        'address',
        'counteragent_id',
        'lat',
        'lng',
        'phone',
        'discount_position',
        'deleted_at',
        'created_at',
        'updated_at',
        'removed_at',
        'id_1c'
    ];

    public function counteragent(): BelongsTo
    {
        return $this->belongsTo(Counteragent::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }
}
