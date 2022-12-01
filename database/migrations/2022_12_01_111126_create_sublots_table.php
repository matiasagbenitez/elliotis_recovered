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

            $table->unsignedBigInteger('lot_id');
            $table->foreign('lot_id')->references('id')->on('lots');

            $table->string('code')->unique();

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->integer('initial_quantity');
            $table->integer('actual_quantity');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sublots');
    }
};
