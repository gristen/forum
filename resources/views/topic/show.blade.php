@extends('components.app')

@section('content')

    <div class="forum-page">

        <div class="container py-4">

            {{-- =====================================================
                 BREADCRUMBS
            ====================================================== --}}

            <nav class="forum-breadcrumb mb-3">

                <ol class="breadcrumb mb-0">

                    <li class="breadcrumb-item">
                        <a href="#">
                            Форум
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="#">
                            {{ $topic->category->name }}
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        {{ $topic->short_title }}
                    </li>

                </ol>

            </nav>


            {{-- =====================================================
                 TOPIC HEADER
            ====================================================== --}}

            <div class="forum-topic-card mb-3">

                <div class="forum-topic-card-body">

                    <div class="d-flex justify-content-between align-items-start gap-3">

                        <div class="min-width-0">

                            <div class="forum-topic-label">
                                Вопрос
                            </div>

                            <h1 class="forum-topic-title">
                                {{ $topic->title }}
                            </h1>

                            <div class="forum-topic-meta">

                                <span>

                                    <i class="bi bi-person"></i>

                                    <a
                                        href="{{ route('profile', $topic->user->name) }}"
                                    >
                                        {{ $topic->user->name }}
                                    </a>

                                </span>


                                <span>

                                    <i class="bi bi-clock"></i>

                                    {{ $topic->created_at->diffForHumans() }}

                                </span>


                                <span>

                                    <i class="bi bi-chat-left-text"></i>

                                    {{ $topic->posts_count }} сообщений

                                </span>

                            </div>

                        </div>


                        <button
                            type="button"
                            class="forum-topic-more"
                        >
                            <i class="bi bi-three-dots"></i>
                        </button>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 POSTS
            ====================================================== --}}

            <div class="d-flex flex-column gap-3">

                @foreach($posts as $post)

                    <article class="forum-post">

                        <div class="row g-0">

                            {{-- =================================================
                                 USER
                            ================================================== --}}

                            <div class="col-md-2 forum-post-user">

                                <div class="forum-post-user-inner">

                                    @if($post->user->avatar)

                                        <img
                                            src="{{ asset('storage/avatars/' . $post->user->avatar) }}"
                                            alt="{{ $post->user->name }}"
                                            class="forum-post-avatar"
                                        >

                                    @else

                                        <div class="forum-post-avatar forum-post-avatar-placeholder">
                                            {{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}
                                        </div>

                                    @endif


                                    <div class="forum-post-username">
                                        {{ $post->user->name }}
                                    </div>


                                    <span class="badge {{ $post->user->role->badge_class }} mb-3">

                                        <i class="{{ $post->user->role->icon_class }}"></i>

                                        {{ $post->user->role->display_name }}

                                    </span>


                                    <div class="forum-post-user-info">

                                        <div>
                                            Сообщений: 128
                                        </div>

                                        <div>
                                            На форуме с 2025
                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 MESSAGE
                            ================================================== --}}

                            <div class="col-md-10">

                                <div class="forum-post-content">

                                    <div class="forum-post-top">

                                        <span></span>

                                        <span class="forum-post-date">
                                            {{ $post->created_at->diffForHumans() }}
                                        </span>

                                    </div>


                                    <div class="forum-post-text">
                                        {!! $post->content !!}
                                    </div>

                                </div>


                                {{-- =================================================
                                     POST FOOTER
                                ================================================== --}}

                                <div class="forum-post-footer">

                                    <div class="d-flex gap-2">

                                        <button
                                            type="button"
                                            class="forum-post-button"
                                        >

                                            <i class="bi bi-hand-thumbs-up"></i>

                                            5

                                        </button>


                                        <button
                                            type="button"
                                            class="forum-post-button"
                                        >

                                            <i class="bi bi-quote"></i>

                                            Цитировать

                                        </button>

                                    </div>


                                    <button
                                        type="button"
                                        class="forum-post-button forum-post-link"
                                    >

                                        <i class="bi bi-link-45deg"></i>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>


            {{-- =====================================================
                 PAGINATION
            ====================================================== --}}

            <div class="forum-pagination my-4">

                {{ $posts->links() }}

            </div>


            {{-- =====================================================
                 REPLY
            ====================================================== --}}

            <div class="forum-reply">

                <div class="forum-reply-body">

                    <h5 class="forum-reply-title">
                        Ответить в теме
                    </h5>


                    <textarea
                        class="forum-reply-input"
                        rows="6"
                        placeholder="Напишите сообщение..."
                    ></textarea>


                    <div class="d-flex justify-content-end">

                        <button
                            type="button"
                            class="forum-primary-button"
                        >

                            <i class="bi bi-send"></i>

                            Отправить

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
