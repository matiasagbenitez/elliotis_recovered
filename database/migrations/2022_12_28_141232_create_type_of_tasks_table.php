<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('type_of_tasks', function (Blueprint $table) {
            $table->id();

            $table->string('type', 255);
            $table->string('name', 255)->unique();
            $table->boolean('initial_task')->default(false);

            $table->boolean('movement')->default(false);
            $table->unsignedBigInteger('origin_area_id');
            $table->foreign('origin_area_id')->references('id')->on('areas');
            $table->unsignedBigInteger('destination_area_id');
            $table->foreign('destination_area_id')->references('id')->on('areas');

            $table->boolean('transformation')->default(false);
            $table->unsignedBigInteger('initial_phase_id');
            $table->foreign('initial_phase_id')->references('id')->on('phases');
            $table->unsignedBigInteger('final_phase_id');
            $table->foreign('final_phase_id')->references('id')->on('phases');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('type_of_tasks');
    }
};
