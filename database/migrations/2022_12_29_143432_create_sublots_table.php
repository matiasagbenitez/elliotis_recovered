<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sublots', function (Blueprint $table) {
            $table->id();

            $table->string('code');

            $table->unsignedBigInteger('lot_id');
            $table->foreign('lot_id')->references('id')->on('lots');

            $table->unsignedBigInteger('phase_id');
            $table->foreign('phase_id')->references('id')->on('phases');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('area_id');
            $table->foreign('area_id')->references('id')->on('areas');

            $table->boolean('available')->default(true);
            $table->integer('initial_quantity')->default(0);
            $table->integer('actual_quantity')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sublots');
    }
};
