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


        @livewire('admin.users-table')

    </div>

@endsection
