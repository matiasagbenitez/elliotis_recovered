<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('initial_task_detail', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks');

            $table->unsignedBigInteger('trunk_sublot_id')->nullable();
            $table->foreign('trunk_sublot_id')->references('id')->on('trunk_sublots');

            $table->integer('consumed_quantity')->min(0)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('initial_task_detail');
    }
};
