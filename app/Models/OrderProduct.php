<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\OrderProduct
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string $count
 * @property string $price
 * @property string $all_price
 * @property string|null $comment
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereAllPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct withoutTrashed()
 * @property-read \App\Models\Product $product
 * @property string $discount_price
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereDiscountPrice($value)
 * @mixin \Eloquent
 */
class OrderProduct extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['id','updated_at','created_at','deleted_at','product_id','price','count','order_id','comment','all_price','discount_price'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (float $value) => ceil($value),
        );
    }
    protected function allPrice(): Attribute
    {
        return Attribute::make(
            get: fn (float $value) => ceil($value),
        );
    }


}
