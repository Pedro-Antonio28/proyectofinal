<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('dieta_alimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dieta_id')->constrained()->onDelete('cascade'); // Relación con la tabla dietas
            $table->foreignId('alimento_id')->constrained()->onDelete('cascade'); // Relación con la tabla alimentos
            $table->string('dia'); // Día de la semana (Lunes, Martes, etc.)
            $table->string('tipo_comida'); // Desayuno, Comida, Cena, etc.
            $table->integer('cantidad')->default(100); // Cantidad en gramos
            $table->boolean('consumido')->default(false); // Para marcar si se ha consumido
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dieta_alimentos');
    }
};
