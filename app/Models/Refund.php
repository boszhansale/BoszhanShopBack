<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property int $check_status
 * @property string|null $ticket_print_url
 * @property string|null $check_number
 * @property array|null $payments
 * @method static \Illuminate\Database\Eloquent\Builder|Refund onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereCheckNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereCheckStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund wherePayments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund whereTicketPrintUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Refund withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Refund withoutTrashed()
 * @mixin \Eloquent
 */
//Возврат от покупателя
class Refund extends Model
{
    use HasFactory,SoftDeletes;

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
        'payments',
        'user_id',
        'status',
        'type',
        'order_id',
        'check_number',
        'check_status',
        'ticket_print_url'
    ];

    protected $casts = [
      'payments' => 'array'
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
    function typeInfo():string
    {
        return match($this->type){
            1 => 'День в день',
            2 => 'Не день в день'
        };
    }
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->addHours(6)->format('d.m.Y H:i'),
        );
    }
}
