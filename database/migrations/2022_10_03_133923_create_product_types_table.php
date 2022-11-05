<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_name_id');
            $table->foreign('product_name_id')->references('id')->on('product_names');

            $table->unsignedBigInteger('measure_id');
            $table->foreign('measure_id')->references('id')->on('measures');

            $table->unsignedBigInteger('unity_id');
            $table->foreign('unity_id')->references('id')->on('unities');

            // Composite unique key
            $table->unique(['product_name_id', 'measure_id', 'unity_id']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_types');
    }
};
