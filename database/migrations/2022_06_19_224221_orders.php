<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function(Blueprint $table){
            $table->id();
            $table->bigInteger('from_passenger')->unsigned()->nullable(false);
            $table->foreign('from_passenger')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('to_driver')->unsigned()->nullable(false);
            $table->foreign('to_driver')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('flight_id')->unsigned()->nullable(false);
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
            $table->enum('accepted', ['WAITING', 'ACCEPTED', 'NOTACCEPTED'])->default('WAITING')->nullable(false);
            $table->timestamps();
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
