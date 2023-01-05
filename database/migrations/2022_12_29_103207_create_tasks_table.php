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

            $table->unsignedBigInteger('type_of_task_id');
            $table->foreign('type_of_task_id')->references('id')->on('type_of_tasks');

            $table->unsignedBigInteger('task_status_id');
            $table->foreign('task_status_id')->references('id')->on('task_statuses');

            // False FK
            $table->integer('started_by')->nullable();
            $table->dateTime('started_at')->nullable();

            // False FK
            $table->integer('finished_by')->nullable();
            $table->dateTime('finished_at')->nullable();

            // Cancelled
            $table->boolean('cancelled')->default(false);
            $table->dateTime('cancelled_at')->nullable();
            $table->integer('cancelled_by')->nullable();
            $table->text('cancelled_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
