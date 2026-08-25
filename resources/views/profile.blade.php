@extends('components.app')

@section('content')

    <div class="container py-4">

        <div class="row g-4">

            {{-- Левая колонка --}}
            <div class="col-lg-4">

                <div class="forum-card">

                    <div class="category-header">
                        Профиль пользователя
                    </div>

                    <div class="text-center p-4">

                        <div class="mx-auto mb-3 user-profile-avatar">
                          <p>photo)</p>
                        </div>

                        <h3 class="mb-1">
                          {{$user->name}}
                        </h3>

                        <div class="text-muted mb-3">
                            {{"@$user->name"}}
                        </div>

                        <span class="badge bg-success mb-4">
                        Онлайн
                    </span>

                        <p class="text-muted mb-4">
                           {{$user->about}}
                        </p>

                        <hr>

                        <div class="text-start">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Роль </span>
                                <span class="badge {{$user->role->badge_class}} ">
                                    <i class="{{$user->role->icon_class}}"></i> {{$user->role->displayname}}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Регистрация</span>
                                <strong>{{$user->created_at->isoFormat('D MMMM Y')}}</strong>
                            </div>

                           {{-- <div class="d-flex justify-content-between">
                                <span class="text-muted">Последний визит</span>
                                <strong>Сегодня</strong>
                            </div>--}}

                        </div>

                        <hr>

                        <button class="btn btn-primary w-100">
                            Редактировать профиль
                        </button>

                    </div>

                </div>

                <div class="forum-card mt-4">

                    <div class="category-header">
                        Статистика
                    </div>

                    <div class="p-3">

                        <div class="row g-3">

                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="number">18</div>
                                    <div class="label">Тем</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="number">352</div>
                                    <div class="label">Сообщений</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="number">214</div>
                                    <div class="label">Лайков</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="number">580</div>
                                    <div class="label">Репутация</div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Правая колонка --}}
            <div class="col-lg-8">

                <div class="forum-card mb-4">

                    <div class="category-header">
                        Последние темы
                    </div>

                    <div class="p-3">

                        <div class="topic-item">
                            <h6 class="mb-1">
                                <a href="#">Как настроить Laravel Sail?</a>
                            </h6>

                            <small class="text-muted">
                                Сегодня • 15 ответов
                            </small>
                        </div>

                        <div class="topic-item">
                            <h6 class="mb-1">
                                <a href="#">Помогите с миграциями</a>
                            </h6>

                            <small class="text-muted">
                                Вчера • 6 ответов
                            </small>
                        </div>

                        <div class="topic-item">
                            <h6 class="mb-1">
                                <a href="#">Как работает Gate?</a>
                            </h6>

                            <small class="text-muted">
                                3 дня назад • 22 ответа
                            </small>
                        </div>

                    </div>

                </div>

                <div class="forum-card">

                    <div class="category-header">
                        Последние сообщения
                    </div>

                    <div class="p-3">

                        <div class="topic-item">

                            <small class="text-primary">
                                Как настроить Laravel Sail?
                            </small>

                            <div class="mt-2">
                                Попробуй использовать docker-compose и команду sail up...
                            </div>

                            <small class="text-muted">
                                10 минут назад
                            </small>

                        </div>

                        <div class="topic-item">

                            <small class="text-primary">
                                Вопрос по PHP
                            </small>

                            <div class="mt-2">
                                Лучше использовать dependency injection вместо new...
                            </div>

                            <small class="text-muted">
                                Сегодня
                            </small>

                        </div>

                        <div class="topic-item">

                            <small class="text-primary">
                                Laravel 12
                            </small>

                            <div class="mt-2">
                                Мне помогло выполнить php artisan optimize:clear.
                            </div>

                            <small class="text-muted">
                                Вчера
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
