<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ReasonRefund
 *
 * @property int $id
 * @property int $type
 * @property string|null $title
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonRefund newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonRefund newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonRefund query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonRefund whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonRefund whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonRefund whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonRefund whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonRefund whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReasonRefund whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReasonRefund extends Model
{
    use HasFactory;

    protected $fillable = ['id','title','type','code'];
}
