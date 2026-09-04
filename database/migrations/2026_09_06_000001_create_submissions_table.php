<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pengajuan pembuatan desain yang dikirim calon klien lewat halaman Kontak.
 * Datanya disimpan lebih dulu, baru dikirim sebagai email pemberitahuan;
 * kalau pengiriman email gagal, pengajuannya tetap tercatat di dashboard.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();

            $table->string('service_type')->nullable();
            $table->string('location')->nullable();
            $table->string('land_area')->nullable();
            $table->string('building_area')->nullable();
            $table->string('floors')->nullable();
            $table->string('budget')->nullable();
            $table->string('style')->nullable();
            $table->text('message');

            $table->string('status')->default('baru');
            $table->boolean('is_read')->default(false);
            $table->text('admin_note')->nullable();

            // Jejak pengiriman email supaya admin tahu kalau ada yang gagal.
            $table->timestamp('email_sent_at')->nullable();
            $table->string('email_error')->nullable();

            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
