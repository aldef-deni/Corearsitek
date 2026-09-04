<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category', 64)->index();
            $table->string('style')->nullable();
            $table->string('client')->nullable();
            $table->string('location')->nullable();
            $table->unsignedSmallInteger('floors')->nullable();
            $table->decimal('building_area', 10, 2)->nullable();
            $table->decimal('land_width', 8, 2)->nullable();
            $table->decimal('land_length', 8, 2)->nullable();
            $table->date('project_date')->nullable()->index();
            $table->text('description')->nullable();
            $table->string('cover_image');
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_images');
        Schema::dropIfExists('portfolios');
    }
};
