@extends('admin.components.app')

@section('content')
    <div class="role-page">

        {{-- HEADER --}}
        <header class="role-page-header">
            <div class="role-page-heading">
                <div class="role-breadcrumb">
                    <a href="{{ route('admin.roles.index') }}">Управление ролями</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Редактирование</span>
                </div>

                <div class="role-title-row">
                    <div class="role-title-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>

                    <div>
                        <h1 class="role-page-title">Редактирование роли</h1>
                        <p class="role-page-subtitle">
                            Управляй доступом пользователей к возможностям форума.
                        </p>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.roles.index') }}" class="role-back-btn">
                <i class="bi bi-arrow-left"></i>
                <span>Назад к ролям</span>
            </a>
        </header>


        {{-- FORM --}}
        <form
            method="POST"
            action="{{ route('admin.roles.update', $role) }}"
            class="role-edit-form"
        >
            @csrf
            @method('PUT')

            {{-- ROLE SETTINGS --}}
            <section class="role-card role-settings-card">

                <div class="role-card-header">
                    <div class="role-card-heading-icon">
                        <i class="bi bi-sliders"></i>
                    </div>

                    <div>
                        <h2>Основные настройки</h2>
                        <p>Название и идентификатор роли</p>
                    </div>
                </div>

                <div class="role-card-body">
                    <div class="role-field">
                        <label for="role-name" class="role-field-label">
                            Название роли
                            <span class="role-required">*</span>
                        </label>

                        <div class="role-input-wrap">
                            <i class="bi bi-person-badge role-input-icon"></i>

                            <input
                                type="text"
                                id="role-name"
                                name="name"
                                class="role-input @error('name') is-invalid @enderror"
                                value="{{ old('name', $role->name) }}"
                                placeholder="Например, moderator"
                                required
                            >
                        </div>

                        @error('name')
                        <div class="role-error">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="role-field-hint">
                            <i class="bi bi-info-circle"></i>
                            Учитывай, что если приложение проверяет роль по её
                            названию, изменение значения может повлиять на такие проверки.
                        </div>
                    </div>
                </div>

            </section>


            {{-- PERMISSIONS --}}
            <section class="role-card role-permissions-card">

                <div class="role-card-header role-permissions-header">
                    <div class="role-card-heading-main">
                        <div class="role-card-heading-icon permissions-heading-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>

                        <div>
                            <h2>Права доступа</h2>
                            <p>Настрой, какие действия доступны этой роли</p>
                        </div>
                    </div>

                    <div class="role-permissions-total">
                        <span class="role-total-number" id="selected-permissions-count">0</span>
                        <span>выбрано</span>
                    </div>
                </div>


                <div class="role-permissions-toolbar">
                    <div class="role-permissions-search">
                        <i class="bi bi-search"></i>

                        <input
                            type="search"
                            id="permission-search"
                            placeholder="Поиск прав доступа..."
                            autocomplete="off"
                        >

                        <kbd>⌕</kbd>
                    </div>

                    <div class="role-selection-actions">
                        <button type="button" id="select-all-permissions">
                            <i class="bi bi-check2-all"></i>
                            Выбрать все
                        </button>

                        <button type="button" id="clear-all-permissions">
                            <i class="bi bi-x-circle"></i>
                            Снять все
                        </button>
                    </div>
                </div>


                <div class="role-permissions-content">


                    <div class="role-permissions-grid" id="permissions-grid">

                        @foreach($permissions as $group => $groupPermissions)

                            <section class="permission-group" data-permission-group>

                                <div class="permission-group-header">
                                    <div class="permission-group-title-wrap">

                                        <div class="permission-group-icon">
                                            <i class="bi {{ config("permissions[$group]['icon']") ?? 'bi-grid' }}"></i>
                                        </div>

                                        <div>
                                            <h3 class="permission-group-title">
                                                {{ __('permissions.groups.' . $group) }}
                                            </h3>

                                            <span class="permission-group-subtitle">
                                                Управление доступом
                                            </span>
                                        </div>
                                    </div>

                                    <div class="permission-group-actions">
                                        <span class="permission-group-count">
                                            {{ $groupPermissions->count() }}
                                        </span>

                                        <button
                                            type="button"
                                            class="permission-group-select"
                                            data-select-group
                                        >
                                            <i class="bi bi-check2-all"></i>
                                            Выбрать все
                                        </button>

                                        <button
                                            type="button"
                                            class="permission-group-clear"
                                            data-clear-group
                                        >
                                            <i class="bi bi-x-circle"></i>
                                            Снять все
                                        </button>
                                    </div>


                                </div>



                                <div class="permission-group-items">

                                    @foreach($groupPermissions as $permission)

                                        <label
                                            class="permission-item"
                                            for="perm{{ $permission->id }}"
                                            data-permission-item
                                            data-search-text="{{ mb_strtolower(__('permissions.' . $permission->name) . ' ' . $permission->name . ' ' . $group) }}"
                                        >
                                            <input
                                                class="permission-checkbox"
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permission->id }}"
                                                id="perm{{ $permission->id }}"
                                                @checked(in_array($permission->id, $selectedPermissions))
                                            >

                                            <span class="permission-custom-check">
                                                <i class="bi bi-check2"></i>
                                            </span>

                                            <span class="permission-item-content">
                                                <span class="permission-item-name">
                                                    {{ __('permissions.' . $permission->name) }}
                                                </span>

                                                <span class="permission-item-key">
                                                    {{ $permission->name }}
                                                </span>
                                            </span>
                                        </label>

                                    @endforeach

                                </div>

                            </section>

                        @endforeach

                    </div>

                    <div class="role-search-empty" id="permission-search-empty" hidden>
                        <i class="bi bi-search"></i>
                        <h3>Ничего не найдено</h3>
                        <p>Попробуй изменить поисковый запрос.</p>
                    </div>

                </div>


                <div class="role-permissions-footer">
                    <div class="role-footer-info">
                        <i class="bi bi-info-circle"></i>

                        <span>
                            Изменения прав вступят в силу после сохранения роли.
                        </span>
                    </div>

                    <div class="role-footer-count">
                        Выбрано:
                        <strong id="selected-permissions-count-footer">0</strong>
                    </div>
                </div>

            </section>


            {{-- FORM ACTIONS --}}
            <div class="role-form-actions">

                <a href="{{ route('admin.roles.index') }}" class="role-cancel-btn">
                    Отмена
                </a>

                <button type="submit" class="role-save-btn">
                    <i class="bi bi-check2-circle"></i>
                    Сохранить изменения
                </button>

            </div>

        </form>

    </div>


    {{-- INTERACTIONS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = Array.from(
                document.querySelectorAll('.permission-checkbox')
            );

            const searchInput = document.getElementById('permission-search');

            const selectAllButton = document.getElementById('select-all-permissions');
            const clearAllButton = document.getElementById('clear-all-permissions');

            const selectedCount = document.getElementById('selected-permissions-count');
            const selectedCountFooter = document.getElementById('selected-permissions-count-footer');

            const emptyState = document.getElementById('permission-search-empty');

            const groups = Array.from(
                document.querySelectorAll('[data-permission-group]')
            );

            // Обновляем количество выбранных разрешений.
            function updateSelectedCount() {
                const count = checkboxes.filter(
                    checkbox => checkbox.checked
                ).length;

                selectedCount.textContent = count;
                selectedCountFooter.textContent = count;
            }

            // Получаем чекбоксы внутри конкретной категории.
            function getGroupCheckboxes(group) {
                return Array.from(
                    group.querySelectorAll('.permission-checkbox')
                );
            }

            // Выбираем или снимаем разрешения.
            function toggleCheckboxes(checkboxesToToggle, checked) {
                checkboxesToToggle.forEach(checkbox => {
                    const item = checkbox.closest('[data-permission-item]');

                    // Не изменяем скрытые поиском разрешения.
                    if (item && !item.hidden) {
                        checkbox.checked = checked;
                    }
                });

                updateSelectedCount();
            }

            // Поиск разрешений.
            function filterPermissions() {
                const query = searchInput.value.trim().toLowerCase();

                let visibleItems = 0;

                groups.forEach(group => {
                    const items = Array.from(
                        group.querySelectorAll('[data-permission-item]')
                    );

                    let visibleInGroup = 0;

                    items.forEach(item => {
                        const text = item.dataset.searchText || '';
                        const matches = text.includes(query);

                        item.hidden = !matches;

                        if (matches) {
                            visibleInGroup++;
                            visibleItems++;
                        }
                    });

                    group.hidden = visibleInGroup === 0;
                });

                emptyState.hidden = visibleItems !== 0;
            }

            // Обновляем счётчик при ручном изменении чекбоксов.
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            // Выбираем все видимые разрешения во всех категориях.
            selectAllButton.addEventListener('click', function () {
                toggleCheckboxes(checkboxes, true);
            });

            // Снимаем все видимые разрешения во всех категориях.
            clearAllButton.addEventListener('click', function () {
                toggleCheckboxes(checkboxes, false);
            });

            // Выбираем все видимые разрешения только в нажатой категории.
            groups.forEach(group => {
                const selectButton = group.querySelector('[data-select-group]');
                const clearButton = group.querySelector('[data-clear-group]');

                if (selectButton) {
                    selectButton.addEventListener('click', function () {
                        toggleCheckboxes(getGroupCheckboxes(group), true);
                    });
                }

                if (clearButton) {
                    clearButton.addEventListener('click', function () {
                        toggleCheckboxes(getGroupCheckboxes(group), false);
                    });
                }
            });

            // Фильтруем разрешения при вводе текста.
            searchInput.addEventListener('input', filterPermissions);

            // Первоначальное количество выбранных разрешений.
            updateSelectedCount();
        });
    </script>

@endsection
