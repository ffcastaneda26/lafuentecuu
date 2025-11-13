<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sponsor_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('ad_type', ['image', 'text', 'video', 'banner', 'html']);
            $table->json('content'); // Flexible para diferentes tipos
            $table->string('position'); // header, sidebar, footer, inline-1, inline-2, etc.
            $table->string('click_url')->nullable();
            $table->unsignedBigInteger('impressions_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->enum('status', ['active', 'inactive', 'scheduled'])->default('active');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->index(['status', 'position']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
