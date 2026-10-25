
<aside class="admin-sidebar">

    {{-- ЛОГО --}}
    <div class="admin-sidebar-brand">

        <div class="admin-sidebar-logo">
            F
        </div>

        <div class="admin-sidebar-brand-text">
            <strong>Форум.hub</strong>
            <span>Админ-панель</span>
        </div>

    </div>


    {{-- МЕНЮ --}}
    <nav class="admin-sidebar-nav">
    @foreach(config('adminMenu.menu') as $item)
            <a
                href="{{route($item['route'])}}"
                class="admin-nav-item {{ request()->routeIs($item['active']) ? 'active' : '' }}"
                title="{{ $item['title'] }}"
            >
                <i class="bi {{$item['icon']}}"></i>
                <span>{{$item['title']}}</span>
            </a>
    @endforeach



       {{-- <div class="admin-nav-section">
            Форум
        </div>

        <a
            href=""
            class="admin-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
        >
            <i class="bi bi-folder-fill"></i>
            <span>Категории</span>
        </a>

        <a
            href=""
            class="admin-nav-item {{ request()->routeIs('admin.topics.*') ? 'active' : '' }}"
        >
            <i class="bi bi-chat-square-text-fill"></i>
            <span>Темы</span>
        </a>

        <a
            href=""
            class="admin-nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}"
        >
            <i class="bi bi-chat-fill"></i>
            <span>Сообщения</span>
        </a>


        <div class="admin-nav-section">
            Пользователи
        </div>

        <a
            href=""
            class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
        >
            <i class="bi bi-people-fill"></i>
            <span>Пользователи</span>
        </a>

        <a
            href=""
            class="admin-nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
        >
            <i class="bi bi-person-badge-fill"></i>
            <span>Роли</span>
        </a>

        <a
            href=""
            class="admin-nav-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"
        >
            <i class="bi bi-shield-lock-fill"></i>
            <span>Права</span>
        </a>


        <div class="admin-nav-section">
            Модерация
        </div>

        <a
            href=""
            class="admin-nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
        >
            <i class="bi bi-flag-fill"></i>
            <span>Жалобы</span>
        </a>

    </nav>
--}}

    {{-- НИЗ SIDEBAR --}}
    <div class="admin-sidebar-bottom">

        <a href="{{ route('home') }}" class="admin-nav-item" title="На форум">
            <i class="bi bi-arrow-left"></i>
            <span>На форум</span>
        </a>

    </div>

</aside>
