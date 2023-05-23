<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\RejectProduct
 *
 * @property int $id
 * @property int $reject_id
 * @property int $product_id
 * @property string $count
 * @property string $price
 * @property string $all_price
 * @property string|null $comment
 * @property int|null $reason_refund_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ReasonRefund|null $reasonRefund
 * @property-read \App\Models\Refund $refund
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct whereAllPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct whereReasonRefundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct whereRejectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RejectProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
//Списание
class RejectProduct extends Model
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
        'reject_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }
}
