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
        Schema::create('flights', function(Blueprint $table){
            $table->id()->unsigned();
            $table->timestamps(); 
            $table->bigInteger('driver')->unsigned()->nullable(false);
            $table->foreign('driver')->references('id')->on('users')->onDelete('cascade');
            $table->string('from_place', 45)->nullable(false);
            $table->string('to_place', 45)->nullable(false);
            $table->dateTime('departure_time')->nullable(false);
            $table->integer('maximum_passengers')->nullable(false);
            $table->decimal('price', 8, 2)->nullable();
            $table->string('cart', 45)->nullable();
            $table->string('cart_mark', 55)->nullable();
            $table->string('cart_image', 255)->nullable(); 
            $table->decimal('back_box_volume', 8, 2)->nullable();
            $table->integer('free_places_left')->nullable();
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
