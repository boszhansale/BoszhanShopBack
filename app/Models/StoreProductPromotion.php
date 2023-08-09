<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StoreProductPromotion
 *
 * @property int $id
 * @property int $store_id
 * @property int $product_id
 * @property int $type
 * @property string $min_total_price
 * @property string $price
 * @property float|null $count
 * @property string|null $date_from
 * @property string|null $date_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion whereMinTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreProductPromotion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreProductPromotion extends Model
{
    use HasFactory;
}
