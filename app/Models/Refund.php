<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Refund
 *
 * @property int $id
 * @property int $status
 * @property int $payment_type
 * @property int $payment_status
 * @property int $user_id
 * @property int|null $counteragent_id
 * @property int|null $store_id
 * @property int|null $storage_id
 * @property int|null $organization_id
 * @property string $total_price
 * @property int|null $order_id
 * @property string|null $removed_at
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Refund newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Refund newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Refund query()
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereCounteragentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereReasonRefundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereRemovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereStorageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereUserId($value)
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereType($value)
 * @property-read \App\Models\ReasonRefund|null $ReasonRefund
 * @property-read \App\Models\Counteragent|null $counteragent
 * @property-read \App\Models\Organization|null $organization
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RefundProduct> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\Store|null $store
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereOrderId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RefundProduct> $products
 * @mixin \Eloquent
 */
class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'removed_at',
        'updated_at',
        'counteragent_id',
        'created_at',
        'deleted_at',
        'storage_id',
        'payment_status',
        'organization_id',
        'store_id',
        'payment_type',
        'total_price',
        'user_id',
        'status',
        'type',
        'order_id'
    ];

    public function counteragent(): BelongsTo
    {
        return $this->belongsTo(Counteragent::class);
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
    public function ReasonRefund(): BelongsTo
    {
        return $this->belongsTo(ReasonRefund::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function products(): HasMany
    {
        return $this->hasMany(RefundProduct::class);
    }
}
