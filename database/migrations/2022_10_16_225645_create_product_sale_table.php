<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_sale', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sale_id');
            $table->foreign('sale_id')->references('id')->on('sales');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('sublot_id')->nullable();

            $table->float('m2_unitary', 10, 2);
            $table->integer('quantity');
            $table->float('m2_total', 10, 2);
            $table->float('m2_price', 10, 2);
            $table->float('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_sale');
    }
};
