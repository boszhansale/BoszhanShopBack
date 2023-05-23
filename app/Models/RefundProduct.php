<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\RefundProduct
 *
 * @property int $id
 * @property int $refund_id
 * @property int $product_id
 * @property string $count
 * @property string $price
 * @property string $all_price
 * @property string|null $comment
 * @property int|null $reason_refund_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct whereAllPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct whereReasonRefundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct whereRefundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RefundProduct whereUpdatedAt($value)
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ReasonRefund|null $reasonRefund
 * @property-read \App\Models\Refund $refund
 * @mixin \Eloquent
 */
class RefundProduct extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'refund_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }
    public function reasonRefund(): BelongsTo
    {
        return $this->belongsTo(ReasonRefund::class);
    }
}
