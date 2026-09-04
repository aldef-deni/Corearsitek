<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Dua daftar berlawanan pada satu tabel: kerugian tanpa jasa arsitek
        // dan keunggulan memakai CoreArsitek.
        Schema::create('advantages', function (Blueprint $table) {
            $table->id();
            $table->string('type', 16)->index(); // rugi | untung
            $table->string('text');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo');
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
        Schema::dropIfExists('advantages');
    }
};
