<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            return;
        }

        $topic = Topic::where('slug', 'kak-rabotaet-eloquent')->first();

        $post = Post::create([
            'topic_id' => $topic->id,
            'user_id' => $user->id,
            'content' => 'Можете объяснить простыми словами, как работает Eloquent и его связи?',
            'edited' => false,
            'edited_at' => null,
        ]);

        Post::create([
            'topic_id' => $topic->id,
            'user_id' => $user->id,
            'content' => 'Eloquent — это ORM Laravel, которая позволяет работать с таблицами базы данных через модели.',
            'edited' => false,
            'edited_at' => null,
        ]);

        Post::create([
            'topic_id' => $topic->id,
            'user_id' => $user->id,
            'content' => 'Например, belongsTo означает, что текущая модель принадлежит другой модели.',
            'edited' => false,
            'edited_at' => null,
        ]);

        $topic = Topic::where('slug', 'kak-sozdat-middleware')->first();

        Post::create([
            'topic_id' => $topic->id,
            'user_id' => $user->id,
            'content' => 'Middleware позволяет выполнять проверку или какую-либо логику перед передачей запроса контроллеру.',
            'edited' => false,
            'edited_at' => null,
        ]);

        $topic = Topic::where('slug', 'chto-takoe-trait-v-php')->first();

        Post::create([
            'topic_id' => $topic->id,
            'user_id' => $user->id,
            'content' => 'Trait позволяет переиспользовать набор методов в нескольких классах.',
            'edited' => false,
            'edited_at' => null,
        ]);

        $topic = Topic::where('slug', 'raznica-mezhdu-let-i-const')->first();

        Post::create([
            'topic_id' => $topic->id,
            'user_id' => $user->id,
            'content' => 'let позволяет переопределять значение переменной, а const используется для переменных, которые нельзя переназначить.',
            'edited' => false,
            'edited_at' => null,
        ]);

        $topic = Topic::where('slug', 'kakoy-redaktor-koda-ispolzuete')->first();

        Post::create([
            'topic_id' => $topic->id,
            'user_id' => $user->id,
            'content' => 'Я использую PhpStorm. А какой редактор предпочитаете вы?',
            'edited' => false,
            'edited_at' => null,
        ]);
    }
}
