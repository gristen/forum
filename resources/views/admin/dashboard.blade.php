@extends('admin.components.app')

@section('content')

    <div class="container admin-page ">

        {{-- HEADER --}}
        <div class="admin-header">

            <div>
                <x-admin.breadcrumb :items="[
                    ['label' => 'Админ-панель'],
                    ['label' => 'Dashboard'],
                ]" />

                <h1 class="admin-title">
                    Панель управления
                </h1>

                <p class="admin-subtitle">
                    Управление форумом и просмотр состояния системы
                </p>
            </div>

        </div>


        {{-- STATS --}}
        <div class="admin-stats">

            @foreach($dashboardData['stats'] as $stat)

                <div class="admin-stat-card">

                    <div class="admin-stat-top">

                        <div class="admin-stat-icon {{ $stat['color'] }}">
                            <i class="bi {{ $stat['icon'] }}"></i>
                        </div>

                        @if(isset($stat['growth']))
                            <span class="admin-stat-growth {{ $stat['growth']['percent'] >= 0 ? 'positive' : 'negative' }}">

                            <i class="bi {{ $stat['growth']['percent'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>

                            {{ abs($stat['growth']['percent']) }}%

                    </span>
                        @endif

                    </div>

                    <div class="admin-stat-label">
                        {{ $stat['label'] }}
                    </div>

                    <div class="admin-stat-value">
                        {{ number_format($stat['total'], 0, ',', ' ') }}
                    </div>

                    <div class="admin-stat-footer">

                        @if(isset($stat['growth']))
                            {{ $stat['growth']['current'] }} за {{ $stat['growth']['field'] }}
                        @else
                            {{ $stat['footer'] }}
                        @endif

                    </div>

                </div>

            @endforeach

        </div>


        {{-- MAIN GRID --}}
        <div class="admin-grid">

            {{-- LEFT --}}
            <main class="admin-main">

                {{-- QUICK ACTIONS --}}
                <section class="admin-panel">

                    <div class="admin-panel-header">

                        <div>
                            <h2>
                                <i class="bi bi-lightning-charge-fill"></i>
                                Быстрые действия
                            </h2>

                            <p>
                                Часто используемые разделы
                            </p>
                        </div>

                    </div>


                    <div class="admin-actions-grid">

                        <a href="{{ route('admin.categories.create') }}" class="admin-action-card">

                            <div class="admin-action-icon purple">
                                <i class="bi bi-folder-plus"></i>
                            </div>

                            <div>
                                <strong>Создать категорию</strong>
                                <span>Добавить новый раздел форума</span>
                            </div>

                            <i class="bi bi-chevron-right admin-action-arrow"></i>

                        </a>


                        <a href="{{ route('admin.users.index') }}" class="admin-action-card">

                            <div class="admin-action-icon green">
                                <i class="bi bi-people-fill"></i>
                            </div>

                            <div>
                                <strong>Пользователи</strong>
                                <span>Управление пользователями</span>
                            </div>

                            <i class="bi bi-chevron-right admin-action-arrow"></i>

                        </a>


                        <a href="{{ route('admin.roles.index') }}" class="admin-action-card">

                            <div class="admin-action-icon yellow">
                                <i class="bi bi-shield-check"></i>
                            </div>

                            <div>
                                <strong>Роли и права</strong>
                                <span>Настройка доступа</span>
                            </div>

                            <i class="bi bi-chevron-right admin-action-arrow"></i>

                        </a>


                        <a href="#" class="admin-action-card">

                            <div class="admin-action-icon red">
                                <i class="bi bi-flag-fill"></i>
                            </div>

                            <div>
                                <strong>Жалобы</strong>
                                <span>Проверить обращения</span>
                            </div>

                            <i class="bi bi-chevron-right admin-action-arrow"></i>

                        </a>

                    </div>

                </section>


                {{-- RECENT TOPICS --}}
                <section class="admin-panel">

                    <div class="admin-panel-header">

                        <div>
                            <h2>
                                <i class="bi bi-chat-square-text-fill"></i>
                                Последние темы
                            </h2>

                            <p>
                                Недавно созданные темы
                            </p>
                        </div>

                        <a href="#" class="admin-panel-link">
                            Все темы
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>


                    <div class="admin-table">

                        @forelse($dashboardData['latestTopics'] ?? [] as $topic)

                            <div class="admin-table-row">

                                <div class="admin-table-icon purple">
                                    <i class="bi bi-chat-fill"></i>
                                </div>

                                <div class="admin-table-main">

                                    <a href="#">
                                        {{ $topic->title }}
                                    </a>

                                    <span>
                                        {{ $topic->user->name ?? 'Неизвестный пользователь' }}
                                    </span>

                                </div>

                                <div class="admin-table-meta">

                                    <span>
                                        {{ $topic->posts_count ?? 0 }}
                                        <i class="bi bi-chat"></i>
                                    </span>

                                    <small>
                                        {{ $topic->created_at?->diffForHumans() }}
                                    </small>

                                </div>

                            </div>

                        @empty

                            <div class="admin-empty">
                                <i class="bi bi-inbox"></i>
                                <span>Тем пока нет</span>
                            </div>

                        @endforelse

                    </div>

                </section>

            </main>


            {{-- SIDEBAR --}}


        </div>

    </div>
@endsection
