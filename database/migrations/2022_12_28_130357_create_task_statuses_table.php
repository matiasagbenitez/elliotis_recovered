<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('task_statuses', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->boolean('running')->default(false);
            $table->boolean('finished')->default(false);
            $table->boolean('canceled')->default(false);
            $table->boolean('pending')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_statuses');
    }
};
