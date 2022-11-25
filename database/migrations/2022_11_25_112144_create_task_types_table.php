<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->required()->unique();

            $table->unsignedBigInteger('area_id');
            $table->foreign('area_id')->references('id')->on('areas');

            $table->unsignedBigInteger('initial_phase_id');
            $table->foreign('initial_phase_id')->references('id')->on('phases');

            $table->unsignedBigInteger('final_phase_id');
            $table->foreign('final_phase_id')->references('id')->on('phases');

            $table->boolean('initial_task')->default(false);
            $table->boolean('final_task')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_types');
    }
};
