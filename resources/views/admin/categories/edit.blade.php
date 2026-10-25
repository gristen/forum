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
                    ['label' => 'Категории', 'url' => route('admin.categories.index')],
                    ['label' => $category->name],
                ]" />

                <h1 class="admin-title">
                    {{ $category->name }}
                </h1>

                <p class="admin-subtitle">
                    Редактирование раздела форума
                </p>

            </div>

            <a
                href="{{ route('admin.categories.index') }}"
                class="admin-action-btn"
            >
                <i class="bi bi-arrow-left"></i>
                Назад к категориям
            </a>

        </div>


        {{-- FORM --}}
        <form
            method="POST"
            action="{{ route('admin.categories.update', $category) }}"
            class="admin-category-form"
        >
            @csrf
            @method('PUT')

            @include('admin.categories._form', [
                'category' => $category,
                'parents' => $parents,
            ])

            <div class="admin-form-actions">

                <div class="admin-form-actions-left">

                    <button
                        type="submit"
                        form="delete-category-form"
                        class="admin-danger-btn"
                    >
                        <i class="bi bi-trash3"></i>
                        Удалить категорию
                    </button>

                </div>

                <div class="admin-form-actions-right">

                    <a
                        href="{{ route('admin.categories.index') }}"
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
            id="delete-category-form"
            method="POST"
            action="{{ route('admin.categories.destroy', $category) }}"
            onsubmit="return confirm('Удалить категорию «{{ $category->name }}»?')"
        >
            @csrf
            @method('DELETE')
        </form>

    </div>

@endsection
