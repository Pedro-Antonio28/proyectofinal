<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_alimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que elige el alimento
            $table->foreignId('alimento_id')->constrained()->onDelete('cascade'); // Alimento seleccionado
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_alimentos');
    }
};
