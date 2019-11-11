<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('availability', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('driving_instructor');
//            $table->string('name', 15);
            $table->string('title', 100);
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->timestamps();

            $table->foreign('driving_instructor')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('availability');
    }
}
