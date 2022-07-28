<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Car;
use App\Models\User;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'seller_id',
        'car_id'
    ];

    public function client()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
