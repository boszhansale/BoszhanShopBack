<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyOperation extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','number','operation_type','sum'];
}
