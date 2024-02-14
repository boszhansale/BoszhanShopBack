<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Report extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $fillable = [
        'name',
        'body',
        'user_id',
        'store_id'
    ];

    protected $casts = [
        'body'=> 'array',
        'created_at' => 'datetime:d.m.Y H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
    public function getDate(): string
    {
        return Carbon::parse($this->created_at)->format('Y-m-d');
    }
    public function getTime():string
    {
        return Carbon::parse($this->created_at)->format('H:i');
    }
}
