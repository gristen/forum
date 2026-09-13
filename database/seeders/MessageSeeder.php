<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
        {
            $userOneId = 1;
            $userTwoId = 2;

            // Приводим пару к единому порядку: 1 + 2, а не 2 + 1
            [$userOneId, $userTwoId] = collect([
                $userOneId,
                $userTwoId,
            ])->sort()->values()->all();

            $conversation = Conversation::firstOrCreate([
                'user_one_id' => $userOneId,
                'user_two_id' => $userTwoId,
            ]);

            $messages = [
                [
                    'user_id' => 2,
                    'content' => 'Привет! Как дела?',
                ],
                [
                    'user_id' => 1,
                    'content' => 'Привет! Всё хорошо, делаю форум на Laravel.',
                ],
                [
                    'user_id' => 2,
                    'content' => 'О, круто! Уже много чего сделал?',
                ],
                [
                    'user_id' => 1,
                    'content' => 'Да, категории, топики и посты уже готовы.',
                ],
                [
                    'user_id' => 2,
                    'content' => 'А личные сообщения уже работают?',
                ],
                [
                    'user_id' => 1,
                    'content' => 'Сейчас как раз занимаюсь ими 😄',
                ],
                [
                    'user_id' => 2,
                    'content' => 'Отлично! Потом покажешь, что получилось.',
                ],
            ];

            foreach ($messages as $index => $message) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $message['user_id'],
                    'content' => $message['content'],
                    'read_at' => $index < 5 ? now() : null,
                    'created_at' => now()->subMinutes(count($messages) - $index),
                    'updated_at' => now()->subMinutes(count($messages) - $index),
                ]);
            }
        }
}
