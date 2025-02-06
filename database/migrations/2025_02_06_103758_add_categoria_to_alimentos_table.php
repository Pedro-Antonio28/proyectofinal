<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('alimentos', function (Blueprint $table) {
            $table->string('categoria')->after('nombre')->default('otros');
        });
    }

    public function down()
    {
        Schema::table('alimentos', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
};
