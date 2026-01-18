<?php

use App\Enums\AdvertisementPositionEnum;
use App\Enums\AdvertisementTypeEnum;
use App\Models\Sponsor;
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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Sponsor::class)->constrained()->onDelete('cascade')->comment('Patrocinador');
            $table->string('title', 150)->comment('Título');
            $table->text('description')->nullable()->comment('Descripción');
            $table->enum('type', array_column(AdvertisementTypeEnum::cases(), 'value'))
                ->default(AdvertisementTypeEnum::IMAGE->value)->comment('Posición: header,leftside,rightside,footer');
            $table->enum('position', array_column(AdvertisementPositionEnum::cases(), 'value'))
                ->default(AdvertisementPositionEnum::HEADER->value)->comment('Posición');
            $table->string('click_url')->nullable()->comment('URL al hacer click');
            $table->string('media_url')->nullable()->comment('URL donde se guarda la imagen o video');
            $table->boolean('active')->default(true)->comment('¿activo?');
            $table->timestamp('start_date')->nullable()->comment('Fecha de Inicio');
            $table->timestamp('end_date')->nullable()->comment('Fecha Final');
            $table->integer('priority')->default(0)->comment('Prioridad');
            $table->unsignedBigInteger('clicks_count')->default(0)->comment('Conteo de Clicks');

            $table->timestamps();

            // Índices para optimización de consultas
            $table->index('active');
            $table->index('type');

            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
