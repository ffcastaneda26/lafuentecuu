<?php

use App\Models\Category;
use App\Models\User;
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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->comment('Título');
            $table->string('subtitle', 150)->nullable()->comment('Subtítulo');
            $table->longText('body')->comment('Cuerpo de la noticia');
            $table->string('slug')->unique()->comment('Slug');
            $table->string('featured_image')->nullable()->comment('Imagen Destacada');
            $table->enum('status', ['borrador', 'publicada', 'archivada'])->default('borrador')->comment('Borrador-Publicada-Archivada');
            $table->timestamp('published_at')->nullable()->comment('Publicada el');
            $table->unsignedBigInteger('views_count')->default(0)->comment('Veces que se ha visto');
            $table->foreignIdFor(Category::class)->comment('Categoría');
            $table->foreignIdFor(User::class)->comment('Usuario que creó la noticia');
            $table->timestamps();
            $table->index(['status', 'published_at']);
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
