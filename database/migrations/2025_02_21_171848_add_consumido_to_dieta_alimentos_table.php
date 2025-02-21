<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('dieta_alimentos', function (Blueprint $table) {
            $table->boolean('consumido')->default(false)->after('cantidad'); // Añadir la columna después de "cantidad"
        });
    }

    public function down()
    {
        Schema::table('dieta_alimentos', function (Blueprint $table) {
            $table->dropColumn('consumido'); // Eliminar la columna si se revierte la migración
        });
    }
};
