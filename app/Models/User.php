<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 
        'email',
        'full_name',
        'password','tel', 
        'avatar_image', 'role', 
        'traveles_type',
        'typical_place_one', 
        'typical_place_two'
    ];

    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function flight_driver(){
        return $this->hasMany(Flight::class, 'driver', 'id');
    }

    function order_from_passenger(){
        return $this->hasMany(Order::class,'from_passenger', 'id');
    }

    function order_to_driver(){
        return $this->hasMany(Order::class,'to_driver', 'id');
    }

}
