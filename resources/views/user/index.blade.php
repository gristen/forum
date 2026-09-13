@extends('components.app')
@section('content')

    <div class="container py-4">

    {{-- Breadcrumb --}}
    <nav class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    Форум
                </a>
            </li>

            <li class="breadcrumb-item active">
                Пользователи
            </li>
        </ol>
    </nav>


    <div class="forum-card">

        {{-- Заголовок --}}
        <div class="p-4 border-bottom">

            <div class="d-flex flex-column flex-sm-row
                        align-items-sm-center justify-content-between gap-3">

                <div>
                    <h4 class="fw-bold mb-1">
                        Пользователи
                    </h4>

                    <div class="text-secondary small">
                        Выберите пользователя, чтобы начать переписку
                    </div>
                </div>

                <a href="{{--{{ route('messages.index') }}--}}"
                   class="btn btn-outline-primary">

                    <i class="bi bi-chat-square-text me-1"></i>

                    Мои сообщения
                </a>

            </div>

        </div>


        {{-- Поиск --}}
        <div class="p-3 border-bottom">

            <form action="{{ route('users.index') }}" method="GET">

                <div class="input-group">

                    <span class="input-group-text bg-white">
                        <i class="bi bi-search text-secondary"></i>
                    </span>

                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control"
                        placeholder="Найти пользователя..."
                    >

                    <button class="btn btn-primary">
                        Найти
                    </button>

                </div>

            </form>

        </div>


        {{-- Список пользователей --}}
        <div class="user-list">

            @forelse($users as $user)

                <div class="user-item p-3 border-bottom">

                    <div class="d-flex align-items-center gap-3">

                        {{-- Аватар --}}
                        <div class="flex-shrink-0">

                            @if($user->avatar)

                                <img
                                    src="{{ asset('storage/avatars/' . $user->avatar) }}"
                                    class=" avatar avatar-lg "
                                    alt="{{ $user->name }}"
                                >

                            @else

                                <div class="user-avatar">
                                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                </div>

                            @endif

                        </div>


                        {{-- Информация --}}
                        <div class="flex-grow-1 min-w-0">

                            <div class="d-flex align-items-center gap-2">

                                <a
                                    href="{{--{{ route('profile.show', $user) }}--}}"
                                    class="fw-semibold text-dark text-decoration-none text-truncate"
                                >
                                    {{ $user->name }}
                                </a>

                                @if($user->role)
                                    <span class="badge {{ $user->role->badge_class }}">
                                        {{ $user->role->display_name }}
                                    </span>
                                @endif

                            </div>


                            @if($user->about)

                                <div class="text-secondary small text-truncate mt-1">
                                    {{ $user->about }}
                                </div>

                            @else

                                <div class="text-secondary small mt-1">
                                    Пользователь форума
                                </div>

                            @endif

                        </div>


                        {{-- Действия --}}
                        <div class="flex-shrink-0">

                            @if($user->id !== auth()->id())

                                <a
                                    href="{{--{{ route('messages.create', $user) }}--}}"
                                    class="btn btn-outline-primary btn-sm"
                                >
                                    <i class="bi bi-chat-dots me-1"></i>

                                    <span class="d-none d-sm-inline">
                                        Написать
                                    </span>
                                </a>

                            @else

                                <span class="text-secondary small">
                                    Это вы
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center py-5">

                    <i class="bi bi-people fs-1 text-secondary"></i>

                    <h5 class="mt-3">
                        Пользователи не найдены
                    </h5>

                    <p class="text-secondary mb-0">
                        Попробуйте изменить поисковый запрос
                    </p>

                </div>

            @endforelse

        </div>


        {{-- Пагинация --}}
        @if($users->hasPages())

            <div class="p-3">
                {{ $users->links() }}
            </div>

        @endif

    </div>

</div>
@endsection
