<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trunk_sublots', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trunk_lot_id');
            $table->foreign('trunk_lot_id')->references('id')->on('trunk_lots');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('area_id');
            $table->foreign('area_id')->references('id')->on('areas');

            $table->integer('initial_quantity')->min(0);
            $table->integer('actual_quantity')->min(0);
            $table->boolean('available')->default(true);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trunk_sublots');
    }
};
