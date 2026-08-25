<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $laravel = Category::where('slug', 'laravel')->first();
        $php = Category::where('slug', 'php')->first();
        $javascript = Category::where('slug', 'javascript')->first();
        $general = Category::where('slug', 'general')->first();

        Topic::create([
            'title' => 'Как работает Eloquent?',
            'slug' => 'kak-rabotaet-eloquent',
            'user_id' => $user->id,
            'category_id' => $laravel->id,
            'views' => 125,
            'locked' => false,
            'pinned' => false,
            'solved' => true,
        ]);

        Topic::create([
            'title' => 'Как создать middleware?',
            'slug' => 'kak-sozdat-middleware',
            'user_id' => $user->id,
            'category_id' => $laravel->id,
            'views' => 87,
            'locked' => false,
            'pinned' => false,
            'solved' => false,
        ]);

        Topic::create([
            'title' => 'Что такое Trait в PHP?',
            'slug' => 'chto-takoe-trait-v-php',
            'user_id' => $user->id,
            'category_id' => $php->id,
            'views' => 210,
            'locked' => false,
            'pinned' => true,
            'solved' => true,
        ]);

        Topic::create([
            'title' => 'Разница между let и const',
            'slug' => 'raznica-mezhdu-let-i-const',
            'user_id' => $user->id,
            'category_id' => $javascript->id,
            'views' => 65,
            'locked' => false,
            'pinned' => false,
            'solved' => false,
        ]);

        Topic::create([
            'title' => 'Какой редактор кода используете?',
            'slug' => 'kakoy-redaktor-koda-ispolzuete',
            'user_id' => $user->id,
            'category_id' => $general->id,
            'views' => 340,
            'locked' => false,
            'pinned' => false,
            'solved' => false,
        ]);
    }
}
