<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MoneyOperation
 *
 * @property int $id
 * @property int $user_id
 * @property string $number
 * @property int $operation_type Изьятие = 1,внесение = 0
 * @property string $sum
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MoneyOperation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MoneyOperation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MoneyOperation query()
 * @method static \Illuminate\Database\Eloquent\Builder|MoneyOperation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoneyOperation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoneyOperation whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoneyOperation whereOperationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoneyOperation whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoneyOperation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MoneyOperation whereUserId($value)
 * @mixin \Eloquent
 */
class MoneyOperation extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','number','operation_type','sum'];
}
