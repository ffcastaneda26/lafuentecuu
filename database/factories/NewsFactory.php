<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(6);
        return [
            'title' => $title,
            'subtitle' => $this->faker->sentence(10),
            'body' => $this->faker->paragraphs(5, true),
            'slug' => Str::slug($title),
            'featured_image' => 'news/default.jpg', // Asegúrate de tener una imagen en storage
            'status' => 'publicada',
            'featured' => false,
            'is_more_news' => true, // Las marcamos para que aparezcan en tu nueva sección
            'sort_order' => 0,
            'published_at' => now()->subDays(rand(0, 30)),
            'views_count' => $this->faker->numberBetween(0, 1000),
            'user_id' => User::first()?->id ?? User::factory(),
        ];
    }
}
