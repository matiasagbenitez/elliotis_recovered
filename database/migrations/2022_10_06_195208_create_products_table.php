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

            $table->float('m2')->nullable();
            $table->float('m2_price')->nullable();

            $table->float('cost')->required();
            $table->float('margin')->required();
            $table->float('selling_price')->required();

            $table->integer('real_stock')->required();
            $table->integer('necessary_stock')->nullable();
            $table->integer('minimum_stock')->required();
            $table->integer('reposition')->required()->default(0);

            $table->boolean('is_buyable')->default(false)->required();
            $table->boolean('is_salable')->default(false)->required();

            $table->unsignedBigInteger('wood_type_id');
            $table->foreign('wood_type_id')->references('id')->on('wood_types');

            $table->unsignedBigInteger('iva_type_id');
            $table->foreign('iva_type_id')->references('id')->on('iva_types');

            // Composite key for product_type_id, phase_id, wood_type_id
            $table->unique(['product_type_id', 'phase_id', 'wood_type_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
