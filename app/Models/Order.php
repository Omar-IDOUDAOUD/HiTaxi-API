<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'from_passenger', 'to_driver',
        'at_time', 'flight_id'
    ];


    function from_passenger()
    {
        return $this->belongsTo(User::class, 'from_passenger', 'id');
    }

    function to_driver()
    {
        return $this->belongsTo(User::class,  'to_driver', 'id');
    }

    function flight_id()
    {
        return $this->belongsTo(Flight::class, 'flight_id', 'id');
    }

}