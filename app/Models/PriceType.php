<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PriceType
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PriceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PriceType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PriceType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PriceType extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'description','deleted_at'];

}
