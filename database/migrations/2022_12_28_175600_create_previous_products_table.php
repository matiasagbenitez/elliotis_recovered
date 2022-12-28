<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('previous_products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('previous_product_id');
            $table->foreign('previous_product_id')->references('id')->on('products');

            $table->boolean('initial')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('previous_products');
    }
};
