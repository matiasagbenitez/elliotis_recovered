<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->unsignedBigInteger('product_type_id');
            $table->foreign('product_type_id')->references('id')->on('product_types');

            $table->unsignedBigInteger('wood_type_id');
            $table->foreign('wood_type_id')->references('id')->on('wood_types');

            $table->boolean('is_buyable')->default(false)->required();
            $table->boolean('is_salable')->default(false)->required();

            $table->string('code')->unique();

            $table->integer('real_stock')->required();
            $table->integer('minimum_stock')->required();
            $table->integer('reposition')->required();

            $table->unsignedBigInteger('iva_type_id');
            $table->foreign('iva_type_id')->references('id')->on('iva_types');

            $table->float('cost')->required();
            $table->float('margin')->required();
            $table->float('selling_price')->required();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
