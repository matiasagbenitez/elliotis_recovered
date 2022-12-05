<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('type_of_movements', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->unsignedBigInteger('origin_area_id');
            $table->foreign('origin_area_id')->references('id')->on('areas');

            $table->unsignedBigInteger('destination_area_id');
            $table->foreign('destination_area_id')->references('id')->on('areas');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('type_of_movements');
    }
};
