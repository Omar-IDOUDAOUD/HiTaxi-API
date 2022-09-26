<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    
    
    public $connection = 'mysql2'; 

    


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderLogs', function(Blueprint $tb){
            $tb->id();  
            $tb->string('from_place', 45)->nullable(false); 
            $tb->string('to_place')->nullable(false); 
            $tb->bigInteger('by_user')->unsigned()->nullable(false); 
            // $tb->foreign('by_user')->references('id')->on('users')->onDelete('restrict');
            $tb->bigInteger('flight_id')->unsigned()->nullable(false); 
            // $tb->foreign('flight_id')->references('id')->on('flights')->onDelete('restrict');
            $tb->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
