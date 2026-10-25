@php
    /** @var \App\Models\User $user */
    $currentName = old('name', $user->name ?? '');
    $currentEmail = old('email', $user->email ?? '');
    $currentAbout = old('about', $user->about ?? '');
    $currentRole = old('role_id', $user->role_id ?? '');
@endphp

<section class="admin-panel">

    <div class="admin-panel-header">

        <div>
            <h2>
                <i class="bi bi-person-gear"></i>
                Основные настройки
            </h2>

            <p>
                Данные профиля и доступ пользователя
            </p>
        </div>

        <span class="admin-panel-count">
            #{{ $user->id }}
        </span>

    </div>


    <div class="admin-panel-body">

        <div class="admin-form-grid">

            {{-- ИМЯ --}}
            <div class="admin-field">

                <label for="name" class="admin-label">
                    Имя
                    <span class="admin-required">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    class="admin-input @error('name') is-invalid @enderror"
                    value="{{ $currentName }}"
                    maxlength="255"
                    required
                >

                @error('name')
                    <div class="admin-field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- EMAIL --}}
            <div class="admin-field">

                <label for="email" class="admin-label">
                    Email
                    <span class="admin-required">*</span>
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="admin-input @error('email') is-invalid @enderror"
                    value="{{ $currentEmail }}"
                    maxlength="255"
                    required
                >

                @error('email')
                    <div class="admin-field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- РОЛЬ --}}
            <div class="admin-field">

                <label for="role_id" class="admin-label">
                    Роль
                    <span class="admin-required">*</span>
                </label>

                <select
                    id="role_id"
                    name="role_id"
                    class="admin-select @error('role_id') is-invalid @enderror"
                    required
                >
                    @foreach($roles as $role)

                        <option
                            value="{{ $role->id }}"
                            @selected((string) $currentRole === (string) $role->id)
                        >
                            {{ $role->display_name }}
                        </option>

                    @endforeach
                </select>

                @error('role_id')
                    <div class="admin-field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- ПАРОЛЬ --}}
            <div class="admin-field">

                <label for="password" class="admin-label">
                    Новый пароль
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="admin-input @error('password') is-invalid @enderror"
                    placeholder="Оставьте пустым, чтобы не менять"
                    autocomplete="new-password"
                >

                @error('password')
                    <div class="admin-field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

                <div class="admin-field-hint">
                    <i class="bi bi-info-circle"></i>
                    Минимум 8 символов
                </div>

            </div>


            {{-- ПОВТОР ПАРОЛЯ --}}
            <div class="admin-field">

                <label for="password_confirmation" class="admin-label">
                    Повтор пароля
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="admin-input"
                    placeholder="Повторите новый пароль"
                    autocomplete="new-password"
                >

            </div>


            {{-- О СЕБЕ --}}
            <div class="admin-field admin-field-wide">

                <label for="about" class="admin-label">
                    О себе
                </label>

                <textarea
                    id="about"
                    name="about"
                    rows="3"
                    maxlength="255"
                    class="admin-textarea @error('about') is-invalid @enderror"
                    placeholder="Коротко о пользователе"
                >{{ $currentAbout }}</textarea>

                @error('about')
                    <div class="admin-field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

    </div>

</section>
