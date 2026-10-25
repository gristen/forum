@extends('components.app')

@section('content')

    <div class="forum-page">

        <div class="container py-4">

            <x-breadcrumb :items="[
                ['label' => 'Форум', 'url' => route('home')],
                ['label' => $category->name],
            ]" />


            {{-- =====================================================
                 CATEGORY
            ====================================================== --}}

            <section class="forum-section mb-3">

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

                    @auth

                        <a
                            href="{{ route('topics.create') }}"
                            class="section-link"
                        >
                            <i class="bi bi-plus-lg"></i>

                            Создать тему
                        </a>

                    @endauth

                </div>


                @if($category->description)

                    <div class="category-description">
                        {{ $category->description }}
                    </div>

                @endif


                <div class="forum-topic-list">

                    @forelse($topics as $topic)

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


            {{-- =====================================================
                 PAGINATION
            ====================================================== --}}

            @if($topics->hasPages())

                <div class="forum-pagination my-4">
                    {{ $topics->links() }}
                </div>

            @endif

        </div>

    </div>

@endsection
