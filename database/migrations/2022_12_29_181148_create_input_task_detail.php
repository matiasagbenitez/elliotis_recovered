<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('input_task_detail', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks');

            $table->unsignedBigInteger('sublot_id');
            $table->foreign('sublot_id')->references('id')->on('sublots');

            $table->integer('consumed_quantity')->min(0)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('input_task_detail');
    }
};
