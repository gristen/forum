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
                    ['label' => $user->name, 'url' => route('admin.users.show', $user)],
                    ['label' => 'Редактирование'],
                ]" />

                <h1 class="admin-title">
                    {{ $user->name }}
                </h1>

                <p class="admin-subtitle">
                    Редактирование профиля пользователя
                </p>

            </div>

            <a
                href="{{ route('admin.users.show', $user) }}"
                class="admin-secondary-btn"
            >
                <i class="bi bi-arrow-left"></i>
                К профилю
            </a>

        </div>


        {{-- FORM --}}
        <form
            method="POST"
            action="{{ route('admin.users.update', $user) }}"
        >
            @csrf
            @method('PUT')

            @include('admin.users._form', [
                'user' => $user,
                'roles' => $roles,
            ])

            <div class="admin-form-actions">

                <div class="admin-form-actions-left">

                    <button
                        type="submit"
                        form="delete-user-form"
                        class="admin-danger-btn"
                    >
                        <i class="bi bi-trash3"></i>
                        Удалить пользователя
                    </button>

                </div>

                <div class="admin-form-actions-right">

                    <a
                        href="{{ route('admin.users.show', $user) }}"
                        class="admin-secondary-btn"
                    >
                        Отмена
                    </a>

                    <button type="submit" class="admin-primary-button">
                        <i class="bi bi-check2"></i>
                        Сохранить изменения
                    </button>

                </div>

            </div>

        </form>


        {{-- Удаление вынесено в отдельную форму, чтобы не вкладывать формы --}}
        <form
            id="delete-user-form"
            method="POST"
            action="{{ route('admin.users.destroy', $user) }}"
            onsubmit="return confirm('Удалить пользователя «{{ $user->name }}»?')"
        >
            @csrf
            @method('DELETE')
        </form>

    </div>

@endsection
