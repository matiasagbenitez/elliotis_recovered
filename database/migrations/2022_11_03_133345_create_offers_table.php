<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('hash_id');
            $table->foreign('hash_id')->references('id')->on('hashes');

            $table->float('subtotal', 10, 2)->required();
            $table->float('iva', 10, 2)->required();
            $table->float('total', 10, 2)->required();

            $table->float('tn_total', 10, 2)->required();

            $table->datetime('delivery_date')->nullable();

            $table->boolean('products_ok')->default(false);
            $table->boolean('quantities_ok')->default(false);

            $table->text('observations')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offers');
    }
};
