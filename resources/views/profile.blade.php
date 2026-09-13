@extends('components.app')

@section('content')
    <div class="forum-page">
        <div class="container py-4">

            <div class="profile-grid">

                {{-- Левая колонка --}}
                <aside>

                    {{-- Профиль --}}
                    <div class="profile-card">

                        <div class="profile-cover"></div>

                        <div class="profile-main">

                            <div class="profile-avatar">
                                <img
                                    src="{{ asset('storage/avatars/' . $user->avatar) }}"
                                    alt="{{ $user->name }}"
                                >
                            </div>

                            <h2 class="profile-name">
                                {{ $user->name }}
                            </h2>

                            <div class="profile-role">
                                <span class="badge {{ $user->role->badge_class }}">
                                    <i class="{{ $user->role->icon_class }}"></i>
                                    {{ $user->role->displayname }}
                                </span>
                            </div>

                            <p class="profile-about">
                                {{ $user->about ?: 'Пользователь пока ничего о себе не рассказал.' }}
                            </p>

                            <div class="profile-meta">
                                <div>
                                    <span>Регистрация</span>
                                    <strong>
                                        {{ $user->created_at->isoFormat('D MMMM Y') }}
                                    </strong>
                                </div>
                            </div>

                            <button class="profile-edit-btn">
                                <i class="bi bi-pencil"></i>
                                Редактировать профиль
                            </button>

                        </div>

                    </div>


                    {{-- Статистика --}}
                    <div class="profile-card profile-stats-card">

                        <div class="profile-section-title">
                            Статистика
                        </div>

                        <div class="profile-stats">

                            <div class="profile-stat">
                                <strong>{{ $user->topics_count }}</strong>
                                <span>Тем</span>
                            </div>

                            <div class="profile-stat">
                                <strong>{{ $user->posts_count }}</strong>
                                <span>Сообщений</span>
                            </div>

                            <div class="profile-stat">
                                <strong>{{ $user->followers_count }}</strong>
                                <span>Подписчиков</span>
                            </div>

                            <div class="profile-stat">
                                <strong>{{ $user->following_count }}</strong>
                                <span>Подписок</span>
                            </div>

                        </div>

                    </div>

                </aside>


                {{-- Правая колонка --}}
                <main>

                    {{-- Последние темы --}}
                    <div class="profile-card mb-4">

                        <div class="profile-section-header">
                            <div>
                                <h3>Последние темы</h3>
                                <span>Темы, созданные пользователем</span>
                            </div>

                            <i class="bi bi-chat-square-text"></i>
                        </div>

                        <div class="profile-list">

                            @forelse($user->topics as $topic)

                                <a
                                    href="{{ route('topic.show', [$topic, $topic->slug]) }}"
                                    class="profile-list-item"
                                >

                                    <div class="profile-list-icon">
                                        <i class="bi bi-chat-left-text"></i>
                                    </div>

                                    <div class="profile-list-content">

                                        <h4>
                                            {{ $topic->short_title }}
                                        </h4>

                                        <div>
                                            {{ $topic->created_at->diffForHumans() }}
                                            <span>•</span>
                                            {{ $topic->posts_count }} ответов
                                        </div>

                                    </div>

                                    <i class="bi bi-chevron-right profile-arrow"></i>

                                </a>

                            @empty

                                <div class="profile-empty">
                                    <i class="bi bi-chat-square"></i>
                                    <span>Пользователь пока не создавал тем</span>
                                </div>

                            @endforelse

                        </div>

                    </div>


                    {{-- Последние сообщения --}}
                    <div class="profile-card">

                        <div class="profile-section-header">
                            <div>
                                <h3>Последние сообщения</h3>
                                <span>Недавняя активность на форуме</span>
                            </div>

                            <i class="bi bi-reply"></i>
                        </div>

                        <div class="profile-list">

                            @forelse($user->posts as $post)

                                <a
                                    href="{{ route('topic.show', [$post->topic, $post->topic->slug]) }}"
                                    class="profile-list-item"
                                >

                                    <div class="profile-list-icon">
                                        <i class="bi bi-reply"></i>
                                    </div>

                                    <div class="profile-list-content">

                                        <h4>
                                            {{ $post->topic->short_title }}
                                        </h4>

                                        <p>
                                            {{ Str::limit(strip_tags($post->content), 180) }}
                                        </p>

                                        <div>
                                            {{ $post->created_at->diffForHumans() }}
                                        </div>

                                    </div>

                                    <i class="bi bi-chevron-right profile-arrow"></i>

                                </a>

                            @empty

                                <div class="profile-empty">
                                    <i class="bi bi-chat-square"></i>
                                    <span>Пользователь пока не оставлял сообщений</span>
                                </div>

                            @endforelse

                        </div>

                    </div>

                </main>

            </div>

        </div>
    </div>
@endsection
