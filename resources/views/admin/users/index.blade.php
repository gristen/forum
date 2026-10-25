@extends('admin.components.app')

@section('content')

    <div class="container admin-page">

        {{-- HEADER --}}
        <div class="admin-header">

            <div>

                <x-admin.breadcrumb :items="[
                    ['label' => 'Админ-панель'],
                    ['label' => 'Пользователи'],
                ]" />

                <h1 class="admin-title">
                    Пользователи
                </h1>

                <p class="admin-subtitle">
                    Статистика регистраций и управление участниками форума
                </p>

            </div>

        </div>


        {{-- STATS --}}
        <div class="admin-stats">

            {{-- ВСЕГО --}}
            <div class="admin-stat-card">

                <div class="admin-stat-top">

                    <div class="admin-stat-icon users">
                        <i class="bi bi-people-fill"></i>
                    </div>

                </div>

                <div class="admin-stat-label">
                    Всего пользователей
                </div>

                <div class="admin-stat-value">
                    {{ number_format($stats['total'], 0, ',', ' ') }}
                </div>

                <div class="admin-stat-footer">
                    Зарегистрировано на форуме
                </div>

            </div>


            {{-- ЗА МЕСЯЦ --}}
            <div class="admin-stat-card">

                <div class="admin-stat-top">

                    <div class="admin-stat-icon registrations">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>

                    <span class="admin-stat-growth {{ $stats['month']['percent'] >= 0 ? 'positive' : 'negative' }}">

                        <i class="bi {{ $stats['month']['percent'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }}"></i>

                        {{ abs($stats['month']['percent']) }}%

                    </span>

                </div>

                <div class="admin-stat-label">
                    Новые за месяц
                </div>

                <div class="admin-stat-value">
                    {{ $stats['month']['current'] }}
                </div>

                <div class="admin-stat-footer">
                    {{ $stats['month']['previous'] }} в прошлом месяце
                </div>

            </div>


            {{-- ЗА НЕДЕЛЮ --}}
            <div class="admin-stat-card">

                <div class="admin-stat-top">

                    <div class="admin-stat-icon week">
                        <i class="bi bi-calendar-week-fill"></i>
                    </div>

                </div>

                <div class="admin-stat-label">
                    Новые за неделю
                </div>

                <div class="admin-stat-value">
                    {{ $stats['week'] }}
                </div>

                <div class="admin-stat-footer">
                    {{ $stats['today'] }} сегодня
                </div>

            </div>


            {{-- ОНЛАЙН --}}
            <div class="admin-stat-card">

                <div class="admin-stat-top">

                    <div class="admin-stat-icon online">
                        <i class="bi bi-broadcast"></i>
                    </div>

                </div>

                <div class="admin-stat-label">
                    Сейчас онлайн
                </div>

                <div class="admin-stat-value">
                    {{ $stats['online'] }}
                </div>

                <div class="admin-stat-footer">
                    <span class="admin-online-dot"></span>
                    Активны за 15 минут
                </div>

            </div>

        </div>


        {{-- REGISTRATIONS CHART --}}
        <section class="admin-panel admin-chart-panel">

            <div class="admin-panel-header">

                <div>
                    <h2>
                        <i class="bi bi-graph-up-arrow"></i>
                        Регистрации по месяцам
                    </h2>

                    <p>
                        {{ now()->year }} год · всего {{ $monthlyTotal }} регистраций
                    </p>
                </div>

                <span class="admin-panel-count">
                    {{ $monthlyTotal }}
                </span>

            </div>


            <div class="admin-panel-body">

                <div class="admin-reg-chart">

                    @foreach($monthly as $month => $count)

                        <div
                            class="admin-reg-col {{ $loop->iteration === now()->month ? 'is-current' : '' }}"
                            title="{{ $month }}: {{ $count }}"
                        >

                            <span class="admin-reg-value">
                                {{ $count }}
                            </span>

                            <div class="admin-reg-track">
                                <div
                                    class="admin-reg-bar"
                                    style="height: {{ $count > 0 ? max(4, round($count / $monthlyMax * 100)) : 2 }}%"
                                ></div>
                            </div>

                            <span class="admin-reg-label">
                                {{ mb_substr($month, 0, 3) }}
                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        </section>


        {{-- USERS --}}
        <section class="admin-panel">

            <div class="admin-panel-header">

                <div>
                    <h2>
                        <i class="bi bi-people-fill"></i>
                        Список пользователей
                    </h2>

                    <p>
                        Найдено: {{ $users->total() }}
                    </p>
                </div>

                <span class="admin-panel-count">
                    {{ $users->total() }}
                </span>

            </div>


            {{-- TOOLBAR --}}
            <form
                method="GET"
                action="{{ route('admin.users.index') }}"
                class="admin-users-toolbar"
            >

                <div class="admin-users-search">

                    <i class="bi bi-search"></i>

                    <input
                        type="search"
                        name="search"
                        value="{{ $search }}"
                        class="admin-input"
                        placeholder="Имя или email..."
                        aria-label="Поиск пользователей"
                    >

                </div>


                <select name="role" class="admin-select" aria-label="Фильтр по роли">

                    <option value="">Все роли</option>

                    @foreach($roles as $roleOption)

                        <option
                            value="{{ $roleOption->name }}"
                            @selected($role === $roleOption->name)
                        >
                            {{ $roleOption->display_name }} ({{ $roleOption->users_count }})
                        </option>

                    @endforeach

                </select>


                <select name="sort" class="admin-select" aria-label="Сортировка">

                    <option value="latest" @selected($sort === 'latest')>Сначала новые</option>
                    <option value="oldest" @selected($sort === 'oldest')>Сначала старые</option>
                    <option value="name" @selected($sort === 'name')>По имени</option>

                </select>


                <button type="submit" class="admin-primary-btn">

                    <i class="bi bi-funnel"></i>
                    Применить

                </button>


                @if($search !== '' || $role !== '')

                    <a href="{{ route('admin.users.index') }}" class="admin-secondary-btn">

                        <i class="bi bi-x-lg"></i>
                        Сбросить

                    </a>

                @endif

            </form>


            {{-- TABLE --}}
            <div class="admin-users-table">

                <div class="admin-users-head">

                    <span>Пользователь</span>
                    <span>Роль</span>
                    <span>Активность</span>
                    <span>Регистрация</span>
                    <span>Статус</span>
                    <span></span>

                </div>


                @forelse($users as $user)

                    <div class="admin-users-row">

                        {{-- USER --}}
                        <div class="admin-user-cell">

                            @if($user->avatar)

                                <img
                                    src="{{ asset('storage/avatars/' . $user->avatar) }}"
                                    alt="{{ $user->name }}"
                                    class="admin-user-avatar"
                                    loading="lazy"
                                >

                            @else

                                <span class="admin-user-avatar admin-user-avatar-fallback">
                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                </span>

                            @endif


                            <div class="admin-user-info">

                                <a
                                    href="{{ route('admin.users.show', $user) }}"
                                    class="admin-user-name"
                                    title="{{ $user->name }}"
                                >
                                    {{ $user->name }}
                                </a>

                                <span class="admin-user-email" title="{{ $user->email }}">
                                    {{ $user->email }}
                                </span>

                            </div>

                        </div>


                        {{-- ROLE --}}
                        <div class="admin-user-role-cell">

                            @if($user->role)

                                <span class="admin-user-role {{ str_replace('bg-', '', $user->role->badge_class) }}">

                                    <i class="bi {{ $user->role->icon_class }}"></i>

                                    {{ $user->role->display_name }}

                                </span>

                            @else

                                <span class="admin-user-role secondary">
                                    Без роли
                                </span>

                            @endif

                        </div>


                        {{-- ACTIVITY --}}
                        <div class="admin-user-activity">

                            <span title="Темы">
                                <i class="bi bi-chat-square-text"></i>
                                {{ $user->topics_count }}
                            </span>

                            <span title="Сообщения">
                                <i class="bi bi-chat"></i>
                                {{ $user->posts_count }}
                            </span>

                        </div>


                        {{-- REGISTERED --}}
                        <div class="admin-user-date">

                            <span>
                                {{ $user->created_at?->format('d.m.Y') }}
                            </span>

                            <small>
                                {{ $user->created_at?->diffForHumans() }}
                            </small>

                        </div>


                        {{-- STATUS / BAN --}}
                        <div class="admin-user-status">

                            @if($user->isBanned())

                                <span class="admin-ban-badge active" title="Забанен">
                                    <i class="bi bi-slash-circle"></i>
                                    Забанен
                                </span>

                            @else

                                <span class="admin-ban-badge" title="Активен">
                                    <i class="bi bi-check-circle"></i>
                                    Активен
                                </span>

                            @endif

                        </div>


                        {{-- ACTION --}}
                        <a
                            href="{{ route('admin.users.show', $user) }}"
                            class="admin-user-action"
                            title="Открыть профиль"
                            aria-label="Открыть профиль {{ $user->name }}"
                        >
                            <i class="bi bi-arrow-up-right"></i>
                        </a>

                    </div>

                @empty

                    <div class="admin-empty">

                        <i class="bi bi-people"></i>

                        <span>
                            Пользователи не найдены
                        </span>

                    </div>

                @endforelse

            </div>


            {{-- PAGINATION --}}
            @if($users->hasPages())

                <div class="admin-pagination">

                    <span class="admin-pagination-info">
                        Показано {{ $users->firstItem() }}–{{ $users->lastItem() }}
                        из {{ $users->total() }}
                    </span>

                    {{ $users->links('admin.pagination') }}

                </div>

            @endif

        </section>

    </div>

@endsection
