<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'brand',
        'year',
        'price',
        'color',
        'traction',
        'type',
        'hp',
        'turbo',
        'cylinders',
        'motor_liters',
        'seller_id'
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function getPriceAttribute($value)
    {
        return number_format($value, 2, '.', ',');
    }

    public function getYearAttribute($value)
    {
        return date('Y', strtotime($value));
    }
}
