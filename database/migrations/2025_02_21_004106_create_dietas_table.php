<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('dietas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('semana'); // Guardará el número de la semana
            $table->json('dieta'); // Se guardará la dieta en formato JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dietas');
    }
};
