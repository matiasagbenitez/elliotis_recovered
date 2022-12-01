<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_task_input', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks');

            $table->unsignedBigInteger('sublot_id');
            $table->foreign('sublot_id')->references('id')->on('sublots');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->integer('consumed_quantity');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_task_input');
    }
};
