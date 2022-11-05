<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wood_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->required();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wood_types');
    }
};
