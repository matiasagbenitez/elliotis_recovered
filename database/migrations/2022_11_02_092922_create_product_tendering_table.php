<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('product_tendering', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tendering_id');
            $table->foreign('tendering_id')->references('id')->on('tenderings');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->integer('quantity');
            $table->float('price', 10, 2);
            $table->float('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_tendering');
    }
};
