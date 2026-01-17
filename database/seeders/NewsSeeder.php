<?php

// database/seeders/NewsSeeder.php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->warn("No hay categorías. Crea algunas primero o este seeder no hará nada.");
            return;
        }

        foreach ($categories as $category) {
            // Creamos 5 noticias para cada categoría
            News::factory()
                ->count(5)
                ->create([
                    'category_id' => $category->id,
                ]);
        }

        $this->command->info("¡Se han creado 5 noticias para cada una de las {$categories->count()} categorías!");
    }
}
