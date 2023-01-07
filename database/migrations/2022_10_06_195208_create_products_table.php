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

            $table->unsignedBigInteger('phase_id');
            $table->foreign('phase_id')->references('id')->on('phases');

            $table->float('cost')->required();
            $table->float('margin')->required();
            $table->float('selling_price')->required();

            $table->integer('real_stock')->required();
            $table->integer('necessary_stock')->nullable();
            $table->integer('minimum_stock')->required();
            $table->integer('reposition')->required();

            $table->boolean('is_buyable')->default(false)->required();
            $table->boolean('is_salable')->default(false)->required();

            $table->unsignedBigInteger('wood_type_id');
            $table->foreign('wood_type_id')->references('id')->on('wood_types');

            $table->unsignedBigInteger('iva_type_id');
            $table->foreign('iva_type_id')->references('id')->on('iva_types');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
