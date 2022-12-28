<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('following_products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('base_product_id');
            $table->foreign('base_product_id')->references('id')->on('products');

            $table->unsignedBigInteger('following_product_id');
            $table->foreign('following_product_id')->references('id')->on('products');

            $table->boolean('final_product')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('following_products');
    }
};
