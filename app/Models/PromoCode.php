<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'discount',
        'start',
        'end',
    ];
    public function orders():HasMany
    {
        return $this->hasMany(Order::class,'discount_phone','phone');
    }
}
