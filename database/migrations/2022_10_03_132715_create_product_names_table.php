<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_names', function (Blueprint $table) {
            $table->id();

            $table->string('name')->required()->unique();
            $table->float('margin')->default(1.35);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_names');
    }
};
