<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DiscountCard
 *
 * @property int $id
 * @property int $store_id
 * @property float $discount
 * @property string $phone
 * @property string $cashback
 * @property string $cashback_total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCard whereCashback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCard whereCashbackTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCard whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCard wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCard whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountCard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DiscountCard extends Model
{
    use HasFactory;

    protected $fillable = ['store_id','discount','phone','cashback','cashback_total_price'];
}
