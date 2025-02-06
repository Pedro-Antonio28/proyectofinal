<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('proteinas')->nullable()->after('calorias_necesarias');
            $table->integer('carbohidratos')->nullable()->after('proteinas');
            $table->integer('grasas')->nullable()->after('carbohidratos');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['proteinas', 'carbohidratos', 'grasas']);
        });
    }
};
