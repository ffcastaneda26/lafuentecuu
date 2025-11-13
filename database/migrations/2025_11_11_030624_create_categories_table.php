<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('Nombre de categoría');
            $table->string('slug')->unique()->comment('Sluga para acceder de manera única');
            $table->integer('order')->default(0)->comment('Orden de presentación');
            $table->boolean('is_active')->default(true)->comment('¿Está activa?');
            $table->text('description')->nullable()->comment('Descripción');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
