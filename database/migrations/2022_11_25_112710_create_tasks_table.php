<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('task_type_id');
            $table->foreign('task_type_id')->references('id')->on('task_types');

            $table->unsignedBigInteger('task_status_id');
            $table->foreign('task_status_id')->references('id')->on('task_statuses');

            $table->integer('started_by')->nullable();
            $table->dateTime('started_at');

            $table->integer('finished_by')->nullable();
            $table->dateTime('finished_at');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
