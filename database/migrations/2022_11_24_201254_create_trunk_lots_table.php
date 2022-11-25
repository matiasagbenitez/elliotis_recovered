<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trunk_lots', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('trunk_purchase_id');
            $table->foreign('trunk_purchase_id')->references('id')->on('trunk_purchases');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->string('code')->unique();

            $table->integer('initial_quantity');
            $table->integer('actual_quantity');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trunk_lots');
    }
};
