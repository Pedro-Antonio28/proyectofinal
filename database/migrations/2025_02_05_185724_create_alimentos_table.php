<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('alimentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del alimento
            $table->integer('calorias'); // Calorías por 100g
            $table->decimal('proteinas',5, 2); // Proteínas en gramos por 100g
            $table->decimal('carbohidratos', 5, 2); // Carbohidratos en gramos por 100g
            $table->decimal('grasas', 5, 2); // Grasas en gramos por 100g
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alimentos');
    }
};
