<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn(PHP_EOL.__('Creando Categorías'));

        $categories = [
            ['name' => 'Local', 'order' => 1],
            ['name' => 'Nacional', 'order' => 2],
            ['name' => 'Opinión', 'order' => 3],
            ['name' => 'Economía', 'order' => 4],
            ['name' => 'Espectáculos', 'order' => 5],
            ['name' => 'Deportes', 'order' => 6],
            ['name' => 'Cultura', 'order' => 7],
            ['name' => 'Internacional', 'order' => 8],
            ['name' => 'Tecnología', 'order' => 9],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => 'Noticias de '.$category['name'],
                'order' => $category['order'],
                'is_active' => true, // ✅ Todos activos
            ]);
        }

        $this->command->info('✅ Categorías creadas exitosamente!');
    }
}
