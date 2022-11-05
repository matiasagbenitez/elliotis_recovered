<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('iva_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->required();
            $table->decimal('percentage', 5, 2)->required()->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('iva_types');
    }
};
