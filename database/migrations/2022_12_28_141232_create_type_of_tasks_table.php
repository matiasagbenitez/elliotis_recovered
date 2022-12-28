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

            $table->string('type', 255)->nullable();
            $table->string('name', 255)->unique();

            // Just one can be true
            $table->boolean('initial_task')->default(false)->unique();

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

            // Composite key
            $table->unique(['origin_area_id', 'destination_area_id', 'initial_phase_id', 'final_phase_id'], 'type_of_task_key');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('type_of_tasks');
    }
};
