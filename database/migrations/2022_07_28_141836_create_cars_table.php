<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model');
            $table->string('brand');
            $table->year('year');
            $table->bigInteger('price');
            $table->string('color');
            $table->string('traction');
            $table->string('type');
            $table->integer('hp');
            $table->boolean('turbo');
            $table->integer('cylinders')->nullable();
            $table->double('motor_liters')->nullable();
            $table->integer('seller_id')->index();
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
        Schema::dropIfExists('cars');
    }
}
