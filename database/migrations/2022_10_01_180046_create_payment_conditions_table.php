<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_conditions', function (Blueprint $table) {
            $table->id();

            $table->string('name')->required()->unique();
            $table->boolean('is_deferred')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_conditions');
    }
};
