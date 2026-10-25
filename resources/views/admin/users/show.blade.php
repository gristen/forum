@extends('admin.components.app')

@section('content')

    <div class="container admin-page">

        {{-- FLASH --}}
        @if (session('error'))
            <div class="admin-alert admin-alert-danger">
                <i class="bi bi-exclamation-triangle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif


        {{-- HEADER --}}
        <div class="admin-header">

            <div>

                <x-admin.breadcrumb :items="[
                    ['label' => 'Админ-панель'],
                    ['label' => 'Пользователи', 'url' => route('admin.users.index')],
                    ['label' => $user->name],
                ]" />

                <h1 class="admin-title">
                    {{ $user->name }}
                </h1>

                <p class="admin-subtitle">
                    {{ $user->email }}
                </p>

                @if($activeBan)
                    <span class="admin-user-banned-badge">
                        <i class="bi bi-slash-circle"></i>
                        Забанен{{ $activeBan->expires_at ? ' до ' . $activeBan->expires_at->format('d.m.Y H:i') : ' навсегда' }}
                    </span>
                @endif

            </div>

            <div class="admin-user-actions">

                <a href="{{ route('admin.users.index') }}" class="admin-secondary-btn">
                    <i class="bi bi-arrow-left"></i>
                    К списку
                </a>

                @if($activeBan)
                    <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="admin-secondary-btn">
                            <i class="bi bi-unlock"></i>
                            Разбанить
                        </button>
                    </form>
                @else
                    <a href="#ban" class="admin-danger-btn">
                        <i class="bi bi-slash-circle"></i>
                        Забанить
                    </a>
                @endif

                <a href="{{ route('admin.users.edit', $user) }}" class="admin-primary-btn">
                    <i class="bi bi-pencil"></i>
                    Редактировать
                </a>

                <form
                    method="POST"
                    action="{{ route('admin.users.destroy', $user) }}"
                    onsubmit="return confirm('Удалить пользователя «{{ $user->name }}»?')"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="admin-danger-btn">
                        <i class="bi bi-trash3"></i>
                        Удалить
                    </button>
                </form>

            </div>

        </div>


        {{-- STATS --}}
        <div class="admin-stats">

            {{-- ТЕМЫ --}}
            <div class="admin-stat-card">

                <div class="admin-stat-top">
                    <div class="admin-stat-icon topics">
                        <i class="bi bi-chat-square-text-fill"></i>
                    </div>
                </div>

                <div class="admin-stat-label">
                    Тем создано
                </div>

                <div class="admin-stat-value">
                    {{ $stats['topics'] }}
                </div>

                <div class="admin-stat-footer">
                    За всё время
                </div>

            </div>


            {{-- СООБЩЕНИЯ --}}
            <div class="admin-stat-card">

                <div class="admin-stat-top">
                    <div class="admin-stat-icon messages">
                        <i class="bi bi-chat"></i>
                    </div>
                </div>

                <div class="admin-stat-label">
                    Сообщений
                </div>

                <div class="admin-stat-value">
                    {{ $stats['messages'] }}
                </div>

                <div class="admin-stat-footer">
                    За всё время
                </div>

            </div>


            {{-- ДИАЛОГИ --}}
            <div class="admin-stat-card">

                <div class="admin-stat-top">
                    <div class="admin-stat-icon users">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>

                <div class="admin-stat-label">
                    Диалогов
                </div>

                <div class="admin-stat-value">
                    {{ $stats['conversations'] }}
                </div>

                <div class="admin-stat-footer">
                    С пользователями
                </div>

            </div>


            {{-- ПОСЛЕДНЯЯ АКТИВНОСТЬ --}}
            <div class="admin-stat-card">

                <div class="admin-stat-top">
                    <div class="admin-stat-icon categories">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>

                <div class="admin-stat-label">
                    Последняя активность
                </div>

                <div class="admin-stat-value">
                    {{ $lastActivityAt ? $lastActivityAt->diffForHumans() : 'нет данных' }}
                </div>

                <div class="admin-stat-footer">
                    На форуме
                </div>

            </div>

        </div>


        {{-- INFO --}}
        <section class="admin-panel">

            <div class="admin-panel-header">

                <div>
                    <h2>
                        <i class="bi bi-person-vcard"></i>
                        Информация
                    </h2>

                    <p>
                        Данные профиля пользователя
                    </p>
                </div>

            </div>

            <div class="admin-panel-body">

                <div class="admin-user-profile-head">

                    @if($user->avatar)

                        <img
                            src="{{ asset('storage/avatars/' . $user->avatar) }}"
                            alt="{{ $user->name }}"
                            class="admin-user-profile-avatar"
                        >

                    @else

                        <span class="admin-user-profile-avatar admin-user-profile-avatar-fallback">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </span>

                    @endif

                    <div class="admin-user-profile-info">

                        <h3 class="admin-user-profile-name">
                            {{ $user->name }}
                        </h3>

                        <a href="mailto:{{ $user->email }}" class="admin-user-profile-email">
                            {{ $user->email }}
                        </a>

                        <p class="admin-user-profile-about">
                            {{ $user->about ?: 'Пользователь пока ничего о себе не рассказал.' }}
                        </p>

                        <div class="admin-user-profile-role">

                            <span class="admin-user-role {{ str_replace('bg-', '', $user->role?->badge_class ?? 'secondary') }}">
                                <i class="bi {{ $user->role?->icon_class }}"></i>
                                {{ $user->role?->display_name ?? 'Без роли' }}
                            </span>

                        </div>

                    </div>

                </div>


                <div class="admin-user-profile-meta">

                    <div class="admin-user-profile-meta-item">
                        <span class="admin-user-profile-label">ID</span>
                        <span class="admin-user-profile-value">{{ $user->id }}</span>
                    </div>

                    <div class="admin-user-profile-meta-item">
                        <span class="admin-user-profile-label">Роль</span>
                        <span class="admin-user-profile-value">{{ $user->role?->display_name ?? 'Без роли' }}</span>
                    </div>

                    <div class="admin-user-profile-meta-item">
                        <span class="admin-user-profile-label">Email</span>
                        <span class="admin-user-profile-value">{{ $user->email }}</span>
                    </div>

                    <div class="admin-user-profile-meta-item">
                        <span class="admin-user-profile-label">Email верифицирован</span>
                        <span class="admin-user-profile-value">
                            {{ $user->email_verified_at ? 'Да' : 'Нет' }}
                        </span>
                    </div>

                    <div class="admin-user-profile-meta-item">
                        <span class="admin-user-profile-label">Регистрация</span>
                        <span class="admin-user-profile-value">
                            {{ $user->created_at?->format('d.m.Y') }}
                        </span>
                    </div>

                    <div class="admin-user-profile-meta-item">
                        <span class="admin-user-profile-label">Последняя активность</span>
                        <span class="admin-user-profile-value">
                            {{ $lastActivityAt ? $lastActivityAt->diffForHumans() : 'нет данных' }}
                        </span>
                    </div>

                    @if($lastSession)
                        <div class="admin-user-profile-meta-item">
                            <span class="admin-user-profile-label">Последний сеанс</span>
                            <span class="admin-user-profile-value">
                                {{ $lastSession->ip_address ?? '—' }}
                            </span>
                        </div>

                        <div class="admin-user-profile-meta-item">
                            <span class="admin-user-profile-label">User Agent</span>
                            <span class="admin-user-profile-value">
                                {{ Str::limit($lastSession->user_agent ?? '', 60) }}
                            </span>
                        </div>
                    @endif

                </div>

            </div>

        </section>


        {{-- BAN --}}
        <section class="admin-panel" id="ban">

            <div class="admin-panel-header">

                <div>
                    <h2>
                        <i class="bi bi-slash-circle"></i>
                        Бан
                    </h2>

                    <p>
                        Ограничение доступа пользователя к форуму
                    </p>
                </div>

                @if($activeBan)
                    <span class="admin-ban-status active">
                        <i class="bi bi-slash-circle"></i>
                        Активен
                    </span>
                @endif

            </div>


            @if($activeBan)

                <div class="admin-panel-body">

                    <div class="admin-user-profile-meta">

                        <div class="admin-user-profile-meta-item">
                            <span class="admin-user-profile-label">Причина</span>
                            <span class="admin-user-profile-value">{{ $activeBan->reason }}</span>
                        </div>

                        <div class="admin-user-profile-meta-item">
                            <span class="admin-user-profile-label">Срок</span>
                            <span class="admin-user-profile-value">
                                {{ $activeBan->expires_at ? 'до ' . $activeBan->expires_at->format('d.m.Y H:i') : 'Навсегда' }}
                            </span>
                        </div>

                        <div class="admin-user-profile-meta-item">
                            <span class="admin-user-profile-label">Забанил</span>
                            <span class="admin-user-profile-value">{{ $activeBan->admin?->name ?? 'Система' }}</span>
                        </div>

                        <div class="admin-user-profile-meta-item">
                            <span class="admin-user-profile-label">Дата бана</span>
                            <span class="admin-user-profile-value">{{ $activeBan->created_at?->format('d.m.Y H:i') }}</span>
                        </div>

                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.users.unban', $user) }}"
                        class="admin-user-ban-action"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="admin-secondary-btn">
                            <i class="bi bi-unlock"></i>
                            Разбанить пользователя
                        </button>
                    </form>

                </div>

            @else

                <form method="POST" action="{{ route('admin.users.ban', $user) }}">

                    @csrf

                    <div class="admin-panel-body">

                        <div class="admin-form-grid">

                            <div class="admin-field">

                                <label for="reason" class="admin-label">
                                    Причина
                                    <span class="admin-required">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="reason"
                                    name="reason"
                                    class="admin-input @error('reason') is-invalid @enderror"
                                    value="{{ old('reason') }}"
                                    placeholder="Например: спам или оскорбления"
                                    maxlength="255"
                                    required
                                >

                                @error('reason')
                                    <div class="admin-field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="admin-field">

                                <label for="duration" class="admin-label">
                                    Срок
                                </label>

                                <select
                                    id="duration"
                                    name="duration"
                                    class="admin-select @error('duration') is-invalid @enderror"
                                >
                                    <option value="1" @selected(old('duration') === '1')>1 день</option>
                                    <option value="7" @selected(old('duration') === null || old('duration') === '7')>7 дней</option>
                                    <option value="30" @selected(old('duration') === '30')>30 дней</option>
                                    <option value="permanent" @selected(old('duration') === 'permanent')>Навсегда</option>
                                </select>

                                @error('duration')
                                    <div class="admin-field-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        <div class="admin-form-actions">

                            <div class="admin-form-actions-left"></div>

                            <div class="admin-form-actions-right">

                                <button type="submit" class="admin-danger-btn">
                                    <i class="bi bi-slash-circle"></i>
                                    Забанить
                                </button>

                            </div>

                        </div>

                    </div>

                </form>

            @endif

        </section>


        {{-- BAN HISTORY --}}
        @if($bans->isNotEmpty())

            <section class="admin-panel">

                <div class="admin-panel-header">

                    <div>
                        <h2>
                            <i class="bi bi-clock-history"></i>
                            История банов
                        </h2>

                        <p>
                            Все ограничения, выданные пользователю
                        </p>
                    </div>

                    <span class="admin-panel-count">
                        {{ $bans->count() }}
                    </span>

                </div>

                <div class="admin-table">

                    @foreach($bans as $ban)

                        <div class="admin-table-row">

                            <div class="admin-table-main">

                                <span class="admin-ban-reason">
                                    {{ $ban->reason }}
                                </span>

                                <span class="admin-ban-meta">
                                    {{ $ban->expires_at ? 'до ' . $ban->expires_at->format('d.m.Y H:i') : 'Навсегда' }}
                                    • {{ $ban->admin?->name ?? 'Система' }}
                                    • {{ $ban->created_at?->format('d.m.Y H:i') }}
                                </span>

                            </div>

                            <div class="admin-table-meta">
                                <span class="admin-ban-status {{ $ban->is_active ? 'active' : 'finished' }}">
                                    {{ $ban->is_active ? 'Активен' : 'Завершён' }}
                                </span>
                            </div>

                        </div>

                    @endforeach

                </div>

            </section>

        @endif


        {{-- LAST TOPICS --}}
        <section class="admin-panel">

            <div class="admin-panel-header">

                <div>
                    <h2>
                        <i class="bi bi-chat-square-text"></i>
                        Последние темы
                    </h2>

                    <p>
                        Темы, созданные пользователем
                    </p>
                </div>

            </div>

            <div class="admin-table">

                @forelse($topics as $topic)

                    <div class="admin-table-row">

                        <div class="admin-table-icon">
                            <i class="bi bi-chat-square-text"></i>
                        </div>

                        <div class="admin-table-main">
                            <a href="{{ route('topic.show', [$topic, $topic->slug]) }}">
                                {{ $topic->title }}
                            </a>

                            <span>
                                {{ $topic->category?->name ?? 'Без категории' }}
                                • {{ $topic->posts_count }} ответов
                            </span>
                        </div>

                        <div class="admin-table-meta">
                            <small>
                                {{ $topic->created_at?->diffForHumans() }}
                            </small>
                        </div>

                    </div>

                @empty

                    <div class="admin-empty">
                        <i class="bi bi-chat-square-text"></i>
                        <span>Пользователь пока не создавал тем</span>
                    </div>

                @endforelse

            </div>

        </section>


        {{-- LAST MESSAGES --}}
        <section class="admin-panel">

            <div class="admin-panel-header">

                <div>
                    <h2>
                        <i class="bi bi-chat"></i>
                        Последние сообщения
                    </h2>

                    <p>
                        Последнее что отправил пользователь
                    </p>
                </div>

            </div>

            <div class="admin-table">

                @forelse($messages as $message)

                    <div class="admin-table-row">

                        <div class="admin-table-icon">
                            <i class="bi bi-chat"></i>
                        </div>

                        <div class="admin-table-main">
                            @if($message->conversation?->userTwo)

                                <a href="{{ route('admin.users.show', $message->conversation->userTwo) }}">
                                    {{ $message->conversation->userTwo->name }}
                                </a>

                            @endif

                            <span>
                                {{ Str::limit($message->content, 90) }}
                            </span>
                        </div>

                        <div class="admin-table-meta">
                            <small>
                                {{ $message->created_at?->diffForHumans() }}
                            </small>
                        </div>

                    </div>

                @empty

                    <div class="admin-empty">
                        <i class="bi bi-chat"></i>
                        <span>Пользователь пока не отправлял сообщений</span>
                    </div>

                @endforelse

            </div>

        </section>


    </div>

@endsection
