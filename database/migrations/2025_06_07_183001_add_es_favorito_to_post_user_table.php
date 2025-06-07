<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('post_user', function (Blueprint $table) {
            $table->boolean('es_favorito')->default(false)->after('custom_notes');
        });
    }

    public function down(): void
    {
        Schema::table('post_user', function (Blueprint $table) {
            $table->dropColumn('es_favorito');
        });
    }
};
