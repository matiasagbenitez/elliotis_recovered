<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('type_of_movement_id');
            $table->foreign('type_of_movement_id')->references('id')->on('type_of_movements');

            $table->unsignedBigInteger('movement_status_id');
            $table->foreign('movement_status_id')->references('id')->on('movement_statuses');

            $table->integer('started_by')->nullable();
            $table->dateTime('started_at')->nullable();

            $table->integer('finished_by')->nullable();
            $table->dateTime('finished_at')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movements');
    }
};
