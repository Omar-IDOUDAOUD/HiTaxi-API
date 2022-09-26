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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('full_name', 45)->nullable(false);
            $table->string('email', 45)->unique()->nullable(false);
            $table->string('tel', 12)->nullable(false);
            $table->string('avatar_image', 255)->nullable();
            $table->enum('role', ['passenger', 'driver'])->nullable(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false);
            $table->enum('traveles_type', ['typical', 'random'])->nullable(false);
            $table->string('typical_place_one', 45)->nullable();
            $table->string('typical_place_two', 45)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
