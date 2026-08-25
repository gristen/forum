<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
            'description' => 'Вопросы и обсуждения по Laravel',
            'icon' => 'bi-code-slash',
            'order' => 1,
        ]);

        Category::create([
            'name' => 'PHP',
            'slug' => 'php',
            'description' => 'Обсуждение PHP и разработки',
            'icon' => 'bi-filetype-php',
            'order' => 2,
        ]);

        Category::create([
            'name' => 'JavaScript',
            'slug' => 'javascript',
            'description' => 'JavaScript, Vue и фронтенд',
            'icon' => 'bi-filetype-js',
            'order' => 3,
        ]);

        Category::create([
            'name' => 'Общие вопросы',
            'slug' => 'general',
            'description' => 'Общие вопросы и обсуждения',
            'icon' => 'bi-chat-dots',
            'order' => 4,
        ]);
    }
}
