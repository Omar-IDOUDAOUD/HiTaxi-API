<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;

    protected $connection = 'mysql2'; 
    protected $table = 'orderlogs';

    protected $fillable = [
        'from_place', 'to_place', 
        'by_user', 'flight_id'
    ]; 


}
