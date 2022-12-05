<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('movement_statuses', function (Blueprint $table) {
            $table->id();

            $table->string('name')->required()->unique();
            $table->boolean('is_finished')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movement_statuses');
    }
};
