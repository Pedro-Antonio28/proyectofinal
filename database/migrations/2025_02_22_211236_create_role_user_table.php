<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con usuarios
            $table->foreignId('role_id')->constrained()->onDelete('cascade'); // Relación con roles
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};

