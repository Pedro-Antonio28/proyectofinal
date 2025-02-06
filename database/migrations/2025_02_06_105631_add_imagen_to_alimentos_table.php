<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('alimentos', function (Blueprint $table) {
            $table->string('imagen')->nullable()->after('categoria'); // Guardará la URL o nombre del archivo
        });
    }

    public function down()
    {
        Schema::table('alimentos', function (Blueprint $table) {
            $table->dropColumn('imagen');
        });
    }
};
