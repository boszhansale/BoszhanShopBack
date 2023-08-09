<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string|null $id_1c
 * @property string|null $article
 * @property int $measure
 * @property string $barcode
 * @property float|null $remainder
 * @property int $purchase
 * @property int $return
 * @property string $presale_id
 * @property float $discount
 * @property int $hit
 * @property int $new
 * @property int $action
 * @property int $discount_5
 * @property int $discount_10
 * @property int $discount_15
 * @property int $discount_20
 * @property int|null $rating
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereArticle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscount10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscount15($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscount20($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDiscount5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId1c($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePresaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereRemainder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereReturn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @property int $enabled
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductBarcode> $barcodes
 * @property-read int|null $barcodes_count
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductCounteragentPrice> $counteragentPrices
 * @property-read int|null $counteragent_prices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductPriceType> $prices
 * @property-read int|null $prices_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereEnabled($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductBarcode> $barcodes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductCounteragentPrice> $counteragentPrices
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage> $images
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductPriceType> $prices
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'category_id',
        'id_1c',
        'article',
        'measure',
        'name',
        'barcode',
        'remainder',
        'enabled',
        'purchase',
        'return',
        'presale_id',
        'discount',
        'hit',
        'new',
        'action',
        'discount_5',
        'discount_10',
        'discount_15',
        'discount_20',
        'rating',
    ];

    protected $hidden = ['created_at','updated_at','deleted_at'];

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPriceType::class, 'product_id');
    }

    public function counteragentPrices(): HasMany
    {
        return $this->hasMany(ProductCounteragentPrice::class, 'product_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function barcodes(): HasMany
    {
        return $this->hasMany(ProductBarcode::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function measureDescription(): string
    {
        return $this->measure == 1 ? 'шт' : 'кг';
    }

    //ostatki
    public function remainsDateTo($date)
    {
        $receiptProductCount = $this->join('receipt_products','receipt_products.product_id','products.id')
            ->join('receipts','receipts.id','receipt_products.receipt_id')
            ->where('receipts.user_id',Auth::id())
            ->whereDate('receipts.created_at','<=',$date)
            ->select('products.*')
            ->sum('count') ?? 0;

        $orderProductCount = $this->join('order_products','order_products.product_id','products.id')
            ->join('orders','orders.id','order_products.order_id')
            ->where('orders.user_id',Auth::id())
            ->whereDate('orders.created_at','<=',$date)
            ->select('products.*')
            ->sum('count') ?? 0;

        $movingProductCount = $this->join('moving_products','moving_products.product_id','products.id')
            ->join('movings','movings.id','moving_products.moving_id')
            ->where('movings.user_id',Auth::id())
            ->where('movings.operation',1)
            ->whereDate('movings.created_at','<=',$date)
            ->select('products.*')
            ->sum('count') ?? 0;


        return $receiptProductCount - $orderProductCount - $movingProductCount;


    }

}
