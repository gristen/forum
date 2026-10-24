@extends("admin.components.app")

@section("content")

    <div class="container admin-page">

        {{-- HEADER --}}
        <div class="admin-header">

            <div>
                <div class="admin-breadcrumb">
                    <span>Админ-панель</span>
                    <i class="bi bi-chevron-right"></i>
                    <span>Пользователи</span>
                    <i class="bi bi-chevron-right"></i>
                    <span>Роли</span>
                </div>

                <h1 class="admin-title">
                    Роли
                </h1>

                <p class="admin-subtitle">
                    Управление ролями и правами доступа пользователей
                </p>
            </div>

            <a href="{{ route('admin.roles.create') }}" class="admin-primary-btn">
                <i class="bi bi-plus-lg"></i>
                Создать роль
            </a>

        </div>


        {{-- ROLES --}}
        <section class="admin-panel">

            <div class="admin-panel-header">

                <div>
                    <h2>
                        <i class="bi bi-person-badge-fill"></i>
                        Список ролей
                    </h2>

                    <p>
                        Роли пользователей форума
                    </p>
                </div>

                <span class="admin-panel-count">
                    {{ $roles->count() }}
                </span>

            </div>


            <div class="admin-roles-list">

                @forelse($roles as $role)

                    <div class="admin-role-row">

                        {{-- ICON --}}
                        <div class="admin-role-icon {{ $role->badge_class }}">
                            <i class="bi {{ $role->icon_class }}"></i>
                        </div>


                        {{-- INFO --}}
                        <div class="admin-role-info">

                            <div class="admin-role-name">
                                {{ $role->display_name }}
                            </div>

                            <div class="admin-role-slug">
                                {{ $role->name }}
                            </div>

                        </div>


                        {{-- USERS --}}
                        <div class="admin-role-stat">

                            <span class="admin-role-stat-label">
                                Пользователи
                            </span>

                            <strong>
                                {{ $role->users_count ?? $role->users->count() }}
                            </strong>

                        </div>

                        {{-- PERMISSIONS --}}
                        <div class="admin-role-stat">

                            <span class="admin-role-stat-label">
                                Права
                            </span>

                            <strong>
                                {{ $role->permissions_count ?? $role->permissions->count() }}
                            </strong>

                        </div>


                        {{-- ACTIONS --}}
                        <div class="admin-role-actions">

                            <a
                                href="{{ route('admin.roles.edit', $role) }}"
                                class="admin-role-action"
                                title="Редактировать"
                            >
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form
                                action="{{ route('admin.roles.destroy', $role) }}"
                                method="POST"
                                onsubmit="return confirm('Удалить эту роль?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="admin-role-action danger"
                                    title="Удалить"
                                >
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>

                        </div>

                    </div>

                @empty

                    <div class="admin-empty">

                        <i class="bi bi-person-badge"></i>

                        <span>
                            Ролей пока нет
                        </span>

                    </div>

                @endforelse

            </div>

        </section>

    </div>

@endsection
