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
        Schema::create('searchlogs', function(Blueprint $tb){
            $tb->id(); 
            $tb->string('from_place', 45)->nullable(false); 
            $tb->string('to_place')->nullable(false); 
            $tb->bigInteger('by_user')->unsigned()->nullable(false);  
            $tb->integer('resultes_number')->nullable(false); 
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
