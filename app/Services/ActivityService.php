<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\User;

class ActivityService
{

    public function log($action, $subject, ?User $actor = null)
    {
        $actor = $actor ?? auth()->user(); // в момент вызова методе в observer у нас нет авторизированного юзера так что мы передаем сразу User

        Activity::query()->create([
            'action' => $action,
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->id,
            'user_id' => $actor?->id,
            'description' => $this->generateDescription($action, $subject, $actor),
        ]);
    }

    public function generateDescription($action, $subject, $actor)
    {
        /*
        $subjectName = method_exists($subject,'getActivityName')
            ? $subject->getActivityName()
            : class_basename($subject);*/

        $subjectName = $subject->name;

        $actorName = $actor->name;

        return match ($action) {
            'rate' => "$actorName оценил $subjectName",
            'favorite' => "$actorName добавил $subjectName в избранное",
            'like' => "$actorName добавил $subjectName в понравшиеся",
            'watchLater' => "$actorName добавил $subjectName в \" Смотреть позже\"",
            'login' => "$actorName вошел в систему",
            'register' => "$actorName зарегистрировался на сайте",
            'comment' => "$actorName оставил комментарий к $subjectName",
            'view' => "$actorName перешел на $subjectName",
            default => "test"
        };

}

    public function getActivity(?int $limit = 5)
    {
       return Activity::query()->limit($limit)->latest()->get();
    }





}
