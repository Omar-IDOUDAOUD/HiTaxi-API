<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{

    use HasFactory;

    protected $connection = 'mysql2'; 
    protected $table = 'searchlogs'; 

    protected $fillable = [
        'from_place', 'to_place', 
        'by_user', 'resultes_number'
    ]; 
}
