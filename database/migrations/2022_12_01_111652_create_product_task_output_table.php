<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_task_output', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks');

            $table->unsignedBigInteger('lot_id');
            $table->foreign('lot_id')->references('id')->on('lots');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->integer('produced_quantity');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_task_output');
    }
};
