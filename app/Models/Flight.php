<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $table = 'flights';
    protected $fillable = [
        'created_at', 'driver',
        'from_place', 'to_place',
        'departure_time', 'maximum_passengers',
        'price', 'cart', 'cart_mark',
        'cart_image', 'back_box_volume',
        'free_places_left'
    ];

    function driver()
    {
        return $this->belongsTo(User::class, 'driver', 'id');
    }

    function order_flight_id()
    {
        return $this->hasMany(Order::class, 'flight_id', 'id');
    }
}
