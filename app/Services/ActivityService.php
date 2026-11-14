<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Ban;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Post;
use App\Models\Role;
use App\Models\Topic;
use App\Models\User;

class ActivityService
{
    /**
     * Логирует действие.
     *
     * @param  mixed  $subject  модель, над которой совершено действие
     * @param  User|null  $actor  кто совершил (null — берётся auth(); полезно
     *                            для вызовов вне авторизации, напр. из observer)
     * @param  string|null  $description  готовая фраза-действие без имени
     *                                    автора, напр. "обновил права роли «X»";
     *                                    null — описание генерируется по $action
     */
    public function log($action, $subject, ?User $actor = null, ?string $description = null): void
    {
        $actor ??= auth()->user();

        Activity::query()->create([
            'action' => $action,
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->id,
            'user_id' => $actor?->id,
            'description' => $description !== null
                ? trim(($actor?->name ?? 'Система').' '.$description)
                : $this->generateDescription($action, $subject, $actor),
        ]);
    }

    public function generateDescription($action, $subject, $actor)
    {
        $actorName = $actor?->name ?? 'Система';
        $subjectName = $this->subjectName($subject);

        /* баны описываются отдельно: субъект тут — Ban, его имя = имя забаненного */
        if ($action === 'banned' || $action === 'unbanned') {
            /* @var Ban $subject */
            $until = $subject->expires_at
                ? 'до '.$subject->expires_at->format('d.m.Y')
                : 'навсегда';

            return $action === 'banned'
                ? "$actorName забанил пользователя «{$subjectName}» ($until)"
                : "$actorName снял бан с пользователя «{$subjectName}»";
        }

        /* для сообщений интересен адресат, а не само сообщение */
        if ($action === 'message_sent') {
            $recipient = $this->messageRecipient($subject);

            return ($recipient !== null)
                ? "$actorName отправил сообщение пользователю «{$recipient}»"
                : "$actorName отправил сообщение";
        }

        return match ($action) {
            'rate' => "$actorName оценил $subjectName",
            'favorite' => "$actorName добавил $subjectName в избранное",
            'like' => "$actorName добавил $subjectName в понравшиеся",
            'watchLater' => "$actorName добавил $subjectName в \" Смотреть позже\"",
            'login' => "$actorName вошел в систему",
            'logout' => "$actorName вышел из системы",
            'register' => "$actorName зарегистрировался на сайте",
            'comment' => "$actorName оставил комментарий к $subjectName",
            'view' => "$actorName перешел на $subjectName",
            'created' => "$actorName создал {$this->subjectLabel($subject)} «{$subjectName}»",
            'updated' => "$actorName обновил {$this->subjectLabel($subject)} «{$subjectName}»",
            'deleted' => "$actorName удалил {$this->subjectLabel($subject)} «{$subjectName}»",
            default => "$actorName совершил действие «{$action}»",
        };
    }

    /**
     * Как субъект зовётся в тексте описания.
     */
    private function subjectName($subject): string
    {
        if (method_exists($subject, 'getActivityName')) {
            return trim((string) $subject->getActivityName());
        }

        return (string) ($subject->name ?? $subject->title ?? class_basename($subject));
    }

    /**
     * В каком виде (падеж, с отсылкой к теме) субъект проступает после глагола.
     */
    private function subjectLabel($subject): string
    {
        return match ($subject->getMorphClass()) {
            Topic::class => 'тему',
            Post::class => 'сообщение в теме',
            Category::class => 'категорию',
            Role::class => 'роль',
            User::class => 'пользователя',
            Ban::class => 'блокировку',
            Conversation::class => 'диалог',
            default => 'запись',
        };
    }

    /**
     * Имя адресата сообщения (второй участник диалога).
     */
    private function messageRecipient(Message $message): ?string
    {
        $conversation = $message->conversation;

        if (! $conversation) {
            return null;
        }

        $other = $conversation->user_one_id === $message->user_id
            ? $conversation->userTwo
            : $conversation->userOne;

        return $other?->name;
    }

    public function getActivity(?int $limit = 5)
    {
        return Activity::query()->limit($limit)->latest()->get();
    }
}
