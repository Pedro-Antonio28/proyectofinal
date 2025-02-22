<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('dieta_alimentos', function (Blueprint $table) {
            if (!Schema::hasColumn('dieta_alimentos', 'consumido')) {
                $table->boolean('consumido')->default(0)->after('cantidad');
            }
        });
    }

    public function down()
    {
        Schema::table('dieta_alimentos', function (Blueprint $table) {
            if (Schema::hasColumn('dieta_alimentos', 'consumido')) {
                $table->dropColumn('consumido');
            }
        });
    }
};
