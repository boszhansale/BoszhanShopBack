<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WebkassaCashBox
 *
 * @property int $id
 * @property string $unique_number
 * @property string $closed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCashBox newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCashBox newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCashBox query()
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCashBox whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCashBox whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCashBox whereUniqueNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCashBox whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WebkassaCashBox whereClosedAt($value)
 * @mixin \Eloquent
 */
class WebkassaCashBox extends Model
{
    use HasFactory;

    protected $fillable = ['unique_number','closed_at'];
}
