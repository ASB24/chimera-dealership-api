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
        'user_id'
    ];
}
