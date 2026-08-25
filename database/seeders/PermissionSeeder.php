<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{


    private $permissions = [

// ===== КАТЕГОРИИ =====
        'category.view',
        'category.create',
        'category.edit',
        'category.delete',
        'category.move',        // перемещать темы между категориями

        // ===== ТЕМЫ =====
        'topic.view',
        'topic.create',
        'topic.edit',           // редактировать любые темы
        'topic.edit_own',       // редактировать свои темы
        'topic.delete',         // удалять любые темы
        'topic.delete_own',     // удалять свои темы
        'topic.pin',            // закреплять темы
        'topic.unpin',          // откреплять темы
        'topic.lock',           // закрывать темы
        'topic.unlock',         // открывать темы
        'topic.move',           // перемещать темы
        'topic.solve',          // отмечать как решённое

        // ===== ПОСТЫ =====
        'post.view',
        'post.create',
        'post.edit',            // редактировать любые посты
        'post.edit_own',        // редактировать свои посты
        'post.delete',          // удалять любые посты
        'post.delete_own',      // удалять свои посты
        'post.reply',           // отвечать на посты
        'post.like',            // ставить лайки
        'post.dislike',         // ставить дизлайки
        'post.report',          // жаловаться на посты

        // ===== ПОЛЬЗОВАТЕЛИ =====
        'user.view',
        'user.edit',
        'user.delete',
        'user.block',           // блокировать пользователей
        'user.unblock',         // разблокировать пользователей
        'user.ban',             // банить пользователей

        // ===== МОДЕРАЦИЯ =====
        'moderate.reports',     // просматривать жалобы
        'moderate.resolve',     // разрешать жалобы
        'moderate.reject',      // отклонять жалобы

        // ===== АДМИНИСТРИРОВАНИЕ =====
        'admin.access',         // доступ к админке
        'admin.settings',       // управление настройками
        'admin.users',          // управление пользователями
        'admin.roles',          // управление ролями
        'admin.categories',     // управление категориями
        'admin.logs',           // просмотр логов


        'role.view',
        'role.create',
        'role.edit',
        'role.delete',
        'role.assign_permissions',


    ];

    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::query()->firstOrCreate([
                'name' => $permission
            ]);

        }
    }
}
