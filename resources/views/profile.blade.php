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
                            <img class="w-50" src="{{ asset('storage/avatars/'. $user->avatar)}}" alt="">
                        </div>

                        <h3 class="mb-1">
                            {{$user->name}}
                        </h3>

                        <div class="text-muted mb-3">
                            {{"@$user->name"}}
                        </div>

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
                                    <div class="number">{{$user->topics_count}}</div>
                                    <div class="label">Топиков</div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="number">{{$user->posts_count}}</div>
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
                        @forelse($user->topics as $topic)
                            <div class="topic-item">
                                <h6 class="mb-1">
                                    <a href="{{ route('topic.show',[$topic, $topic->slug]) }}">{{ $topic->short_title }}</a>
                                </h6>

                                <small class="text-muted">
                                    {{$topic->created_at->diffForHumans()}} • {{$topic->posts_count}} ответов
                                </small>
                            </div>

                        @empty
                            <p>ничего нет</p>
                        @endforelse


                    </div>

                </div>

                <div class="forum-card">

                    <div class="category-header">
                        Последние сообщения
                    </div>

                    <div class="p-3">
                        @forelse($user->posts as $post)
                        <div class="topic-item">

                            <small class="text-primary">
                                <a href="{{ route('topic.show',[$post->topic, $post->topic->slug]) }}">{{$post->topic->short_title}}</a>
                            </small>

                            <div class="mt-2">
                                {{ strip_tags($post->content) }}
                            </div>

                            <small class="text-muted">
                                {{$post->created_at->diffForHumans()}}
                            </small>

                        </div>
                        @empty
                            <p>пусто</p>
                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
