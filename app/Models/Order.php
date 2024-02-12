<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Order
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
 * @property string|null $removed_at
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCounteragentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRemovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStorageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @property int $online_sale
 * @property array|null $product_history
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Counteragent|null $counteragent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderProduct> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOnlineSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereProductHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order withoutTrashed()
 * @property-read \App\Models\Organization|null $organization
 * @property-read \App\Models\Storage|null $storage
 * @property-read \App\Models\Store|null $store
 * @property string|null $discount_phone
 * @property string|null $discount_cashback
 * @property-read \App\Models\WebkassaCheck|null $webkassaCheck
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountCashback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountPhone($value)
 * @property int $check_status
 * @property string|null $give_price сдача
 * @property array|null $payments
 * @property string|null $total_discount_price
 * @property string|null $ticket_print_url
 * @property string|null $check_number
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCheckNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCheckStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereGivePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePayments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTicketPrintUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalDiscountPrice($value)
 * @mixin \Eloquent
 */
class Order extends Model implements Auditable
{
    use HasFactory,SoftDeletes,\OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'counteragent_id',
        'status',
        'user_id',
        'total_price',
        'payment_type',
        'payment_status',
        'payments',

        'product_history',
        'online_sale',

        'store_id',
        'organization_id',
        'storage_id',
        'discount_phone',
        'discount_cashback',
        'removed_at',
        'updated_at',
        'created_at',
        'deleted_at',
        'ticket_print_url',
        'check_status',
        'check_number',
        'total_discount_price',
        'give_price',
        'params'
    ];


    protected $casts = [
        'product_history' => 'array',
        'payments' => 'array',
        'created_at' => 'datetime:d.m.Y H:i',
        'params' => 'object'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function products(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }
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
    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }
    public function webkassaCheck(): HasOne
    {
        return $this->hasOne(WebkassaCheck::class)->whereNotNull('check_number')->latest();
    }
    public function paymentTypeInfo(): string
    {
        $payments = '';
        if (!$this->payments) return '';
        foreach ($this->payments as $payment) {
            if ($payment['PaymentType'] == 0){
                $payments.='💵Наличный '.'('.$payment['Sum'].') ';
            }elseif($payment['PaymentType'] == 1){
                $payments.='💳Безнал  '.'('.$payment['Sum'].') ';
            }
        }

        return $payments;
//        return  match ($this->payment_type)
//        {
//            1  => '💵Наличный',
//            2  => '💳Без наличный',
//            3  => '📆Отсрочка',
//            4  => '🏦Каспи',
//            5  => 'излишка',
//        };
    }

//    protected function createdAt(): Attribute
//    {
//        return Attribute::make(
//            get: fn($value) => Carbon::parse($value)->format('d.m.Y H:i'),
//        );
//    }
//    protected function deletedAt(): Attribute
//    {
//        return Attribute::make(
//            get: fn($value) => \Illuminate\Support\Carbon::parse($value)->format('d.m.Y H:i'),
//        );
//    }
}
