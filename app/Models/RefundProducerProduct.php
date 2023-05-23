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
 * @property int $refund_producer_id
 * @property int $product_id
 * @property string $count
 * @property string $price
 * @property string $all_price
 * @property string|null $comment
 * @property int|null $reason_refund_id
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ReasonRefund|null $reasonRefund
 * @property-read \App\Models\RefundProducer $refundProducer
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProducerProduct whereAllPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProducerProduct whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProducerProduct whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProducerProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProducerProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProducerProduct whereRefundProducerId($value)
 * @mixin \Eloquent
 */
class RefundProducerProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'refund_producer_id',
        'price',
        'count',
        'all_price',
        'comment',
        'id',
        'updated_at',
        'created_at',
        'deleted_at',
        'product_id',
        'reason_refund_id',
    ];


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function refundProducer(): BelongsTo
    {
        return $this->belongsTo(RefundProducer::class);
    }
    public function reasonRefund(): BelongsTo
    {
        return $this->belongsTo(ReasonRefund::class);
    }
}
