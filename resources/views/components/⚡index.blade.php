<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Livewire\Component;

new class extends Component {

    public $categories;
    public $latestTopics;
    public $statistics;
    public $activeUsers;

    public function mount()
    {
        $this->categories = Category::query()
            ->withCount('topics')
            ->with([
                'topics' => fn($q) => $q
                    ->withCount('posts')
                    ->latest('updated_at')
                    ->with([
                        'user',
                        'posts' => fn($q) => $q
                            ->latest()
                            ->limit(1)
                            ->with('user'),
                    ]),
            ])
            ->get();


        $this->latestTopics = Topic::query()
            ->latest('updated_at')
            ->with([
                'user',
                'category',
            ])
            ->withCount('posts')
            ->limit(4)
            ->get();

        $this->statistics = [
            'topics' => Topic::count(),
            'posts' => Post::count(),
            'users' => User::count(),
            'online' => 0,
        ];

        $this->activeUsers = User::query()
            ->withCount('posts')
            ->orderByDesc('posts_count')
            ->limit(4)
            ->get();
    }
};

?>

<div>
    <div class="forum-page d-flex flex-column min-vh-100">

        {{-- =====================================================
             MAIN
        ====================================================== --}}

        <main class="flex-grow-1 py-4">

            <div class="forum-container">

                {{-- =================================================
                     WELCOME
                ================================================== --}}

                <section class="forum-welcome mb-4">

                    <div class="welcome-main">

                        <div class="welcome-icon">
                            <i class="bi bi-chat-square-text"></i>
                        </div>

                        <div>
                            <div class="welcome-title">
                                Добро пожаловать на форум
                            </div>

                            <div class="welcome-description">
                                Общайтесь, задавайте вопросы, делитесь опытом и находите новых друзей.
                            </div>
                        </div>

                    </div>

                    <div class="welcome-stats">

                        <div class="welcome-online">
                            <span class="online-dot-static"></span>

                            <strong>
                                {{ number_format($statistics['online'], 0, ',', ' ') }}
                            </strong>

                            онлайн
                        </div>

                        <div class="welcome-divider"></div>

                        <div>
                            <strong>
                                {{ number_format($statistics['topics'], 0, ',', ' ') }}
                            </strong>

                            тем
                        </div>

                    </div>

                </section>


                {{-- =================================================
                     CONTENT
                ================================================== --}}

                <div class="forum-layout">

                    {{-- =================================================
                         LEFT COLUMN
                    ================================================== --}}

                    <div class="forum-main">

                        @if(Auth::check())
                            <div class="mb-3">
                                <a
                                    href="{{ route('topics.create') }}"
                                    class="forum-create-topic"
                                >
                                    <i class="bi bi-plus-lg"></i>

                                    Создать новую тему
                                </a>
                            </div>
                        @endif


                        {{-- =============================================
                             CATEGORIES
                        ============================================== --}}

                        @forelse($categories as $category)

                            <section class="forum-section mb-3">

                                {{-- CATEGORY HEADER --}}

                                <div class="forum-section-header">

                                    <div class="section-title">

                                        <span class="section-icon">
                                            <i class="bi bi-folder2-open"></i>
                                        </span>

                                        <span>
                                            {{ $category->name }}
                                        </span>

                                        <span class="section-count">
                                            {{ $category->topics_count }}
                                        </span>

                                    </div>

                                    <a href="#" class="section-link">
                                        Все темы

                                        <i class="bi bi-chevron-right"></i>
                                    </a>

                                </div>


                                {{-- CATEGORY TOPICS --}}

                                <div class="forum-topic-list">

                                    @forelse($category->topics as $topic)

                                        @php
                                            $lastPost = $topic->posts->first();
                                        @endphp

                                        <div class="forum-topic-row">

                                            {{-- ICON --}}

                                            <div class="topic-status">

                                                @if($topic->pinned)
                                                    <i
                                                        class="bi bi-pin-angle-fill"
                                                        title="Закреплено"
                                                    ></i>
                                                @elseif($topic->locked)
                                                    <i
                                                        class="bi bi-lock-fill"
                                                        title="Закрыто"
                                                    ></i>
                                                @else
                                                    <i class="bi bi-chat-left-text"></i>
                                                @endif

                                            </div>


                                            {{-- TITLE --}}

                                            <div class="topic-content">

                                                <a
                                                    href="{{ route('topic.show', [
                                                        'topic' => $topic,
                                                        'slug' => $topic->slug
                                                    ]) }}"
                                                    class="topic-title"
                                                >
                                                    {{ $topic->title }}
                                                </a>

                                                <div class="topic-meta">

                                                    <span>
                                                        <i class="bi bi-chat"></i>

                                                        {{ $topic->posts_count }}

                                                        {{ trans_choice(
                                                            'сообщение|сообщения|сообщений',
                                                            $topic->posts_count
                                                        ) }}
                                                    </span>

                                                    <span class="topic-dot">•</span>

                                                    <span>
                                                        {{ $topic->user->name }}
                                                    </span>

                                                </div>

                                            </div>


                                            {{-- LAST POST --}}

                                            <div class="topic-last">

                                                @if($lastPost)

                                                    <img
                                                        src="{{ $lastPost->user->avatar
                                                            ? asset('storage/avatars/' . $lastPost->user->avatar)
                                                            : 'https://ui-avatars.com/api/?name=' . urlencode($lastPost->user->name) . '&background=6b542d&color=fff&size=36'
                                                        }}"
                                                        alt="{{ $lastPost->user->name }}"
                                                        class="avatar-sm"
                                                    >

                                                    <div class="last-post-info">

                                                        <div class="last-post-user">
                                                            {{ $lastPost->user->name }}
                                                        </div>

                                                        <div class="last-post-time">
                                                            {{ $lastPost->created_at->diffForHumans() }}
                                                        </div>

                                                    </div>

                                                @else

                                                    <span class="no-posts">
                                                        Нет сообщений
                                                    </span>

                                                @endif

                                            </div>

                                        </div>

                                    @empty

                                        <div class="forum-empty">
                                            <i class="bi bi-inbox"></i>

                                            В этой категории пока нет тем.
                                        </div>

                                    @endforelse

                                </div>

                            </section>

                        @empty

                            <div class="forum-section">
                                <div class="forum-empty py-5">
                                    <i class="bi bi-inbox"></i>

                                    Категорий пока нет.
                                </div>
                            </div>

                        @endforelse

                    </div>


                    {{-- =================================================
                         RIGHT SIDEBAR
                    ================================================== --}}

                    <aside class="forum-sidebar">


                        {{-- =============================================
                             LATEST TOPICS
                        ============================================== --}}

                        <section class="sidebar-block">

                            <div class="sidebar-header">

                                <div>
                                    <i class="bi bi-fire"></i>

                                    Последние обсуждения
                                </div>

                                <a href="#">
                                    Все
                                </a>

                            </div>


                            <div class="sidebar-body">

                                @forelse($latestTopics as $topic)

                                    <a
                                        href="{{ route('topic.show', [
                                            'topic' => $topic,
                                            'slug' => $topic->slug
                                        ]) }}"
                                        class="latest-topic"
                                    >

                                        <img
                                            src="{{ $topic->user->avatar
                                                ? asset('storage/avatars/' . $topic->user->avatar)
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($topic->user->name) . '&background=6b542d&color=fff&size=40'
                                            }}"
                                            alt="{{ $topic->user->name }}"
                                            class="avatar-md"
                                        >

                                        <div class="latest-topic-content">

                                            <div class="latest-topic-title">
                                                {{ $topic->title }}
                                            </div>

                                            <div class="latest-topic-meta">

                                                {{ $topic->user->name }}

                                                <span>•</span>

                                                {{ $topic->updated_at->diffForHumans() }}

                                            </div>

                                        </div>

                                        <div class="latest-topic-count">

                                            <strong>
                                                {{ max(0, $topic->posts_count - 1) }}
                                            </strong>

                                            <span>
                                                ответов
                                            </span>

                                        </div>

                                    </a>

                                @empty

                                    <div class="sidebar-empty">
                                        Пока нет обсуждений.
                                    </div>

                                @endforelse

                            </div>

                        </section>


                        {{-- =============================================
                             STATISTICS
                        ============================================== --}}

                        <section class="sidebar-block">

                            <div class="sidebar-header">

                                <div>
                                    <i class="bi bi-bar-chart-fill"></i>

                                    Статистика
                                </div>

                            </div>

                            <div class="sidebar-body">

                                <div class="forum-stat-grid">

                                    <div class="forum-stat">

                                        <div class="forum-stat-number">
                                            {{ number_format($statistics['topics'], 0, ',', ' ') }}
                                        </div>

                                        <div class="forum-stat-label">
                                            Тем
                                        </div>

                                    </div>


                                    <div class="forum-stat">

                                        <div class="forum-stat-number">
                                            {{ number_format($statistics['posts'], 0, ',', ' ') }}
                                        </div>

                                        <div class="forum-stat-label">
                                            Сообщений
                                        </div>

                                    </div>


                                    <div class="forum-stat">

                                        <div class="forum-stat-number">
                                            {{ number_format($statistics['users'], 0, ',', ' ') }}
                                        </div>

                                        <div class="forum-stat-label">
                                            Пользователей
                                        </div>

                                    </div>


                                    <div class="forum-stat">

                                        <div class="forum-stat-number online-number">
                                            {{ number_format($statistics['online'], 0, ',', ' ') }}
                                        </div>

                                        <div class="forum-stat-label">
                                            Онлайн
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </section>


                        {{-- =============================================
                             ACTIVE USERS
                        ============================================== --}}

                        <section class="sidebar-block">

                            <div class="sidebar-header">

                                <div>
                                    <i class="bi bi-trophy-fill"></i>

                                    Активные участники
                                </div>

                            </div>


                            <div class="sidebar-body">

                                @forelse($activeUsers as $user)

                                    <div class="active-user">

                                        <div class="active-user-left">

                                            <img
                                                src="{{ $user->avatar
                                                    ? asset('storage/avatars/' . $user->avatar)
                                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6b542d&color=fff&size=34'
                                                }}"
                                                alt="{{ $user->name }}"
                                                class="avatar-sm"
                                            >

                                            <span>
                                                {{ $user->name }}
                                            </span>

                                        </div>

                                        <span class="active-user-posts">

                                            {{ number_format($user->posts_count, 0, ',', ' ') }}

                                            {{ trans_choice(
                                                'сообщение|сообщения|сообщений',
                                                $user->posts_count
                                            ) }}

                                        </span>

                                    </div>

                                @empty

                                    <div class="sidebar-empty">
                                        Пока нет пользователей.
                                    </div>

                                @endforelse

                            </div>

                        </section>

                    </aside>

                </div>

            </div>

        </main>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <footer class="forum-footer">

            <div class="forum-container">

                <div class="footer-inner">

                    <div class="footer-copy">

                        © 2026 Форум.hub

                        <span>•</span>

                        Сделано на
                        <strong>Laravel</strong>

                    </div>


                    <div class="footer-links">

                        <a href="#">
                            Правила
                        </a>

                        <a href="#">
                            Контакты
                        </a>

                        <a href="#">
                            <i class="bi bi-github"></i>
                        </a>

                        <a href="#">
                            <i class="bi bi-telegram"></i>
                        </a>

                        <a href="#">
                            <i class="bi bi-vk"></i>
                        </a>

                    </div>

                </div>

            </div>

        </footer>

    </div>
</div>

