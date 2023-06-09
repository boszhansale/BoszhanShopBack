<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\WebkassaCheck
 *
 * @property int $id
 * @property string $number
 * @property int $order_id
 * @property int $webkassa_cash_box_id
 * @property string|null $ticket_url
 * @property string|null $ticket_print_url
 * @property mixed|null $params request data
 * @property mixed|null $data response data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereTicketPrintUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereTicketUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereWebkassaCashBoxId($value)
 * @property string|null $check_number
 * @property int|null $operation_type
 * @property int|null $refund_id
 * @property int|null $receipt_id
 * @property int|null $refund_producer_id
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereCheckNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereOperationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereReceiptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereRefundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCheck whereRefundProducerId($value)
 * @mixin \Eloquent
 */
class WebkassaCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'data',
        'number',
        'params',
        'ticket_print_url',
        'ticket_url',
        'webkassa_cash_box_id',
        'check_number',
        'operation_type',
        'refund_id',
        'receipt_id',
        'refund_producer_id'
    ];

    protected $casts = [
        'params' => 'object',
        'data' => 'object'
    ];
    public const  PAYMENT_TYPES = [
       0 => 'нал',
       1 => 'карта',
       4 => 'мобильный',
    ];
    public const  OPERATION_TYPE = [
       0 => 'Покупка',
       1 => 'Возврат покупки',
       2 => 'Продажа',
       3 => 'Возврат продажа',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }
}
