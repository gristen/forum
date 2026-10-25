@extends('admin.components.app')

@section('content')

    <div class="container admin-page">

        {{-- HEADER --}}
        <div class="admin-header">

            <div>

                <x-admin.breadcrumb :items="[
                    ['label' => 'Категории', 'url' => route('admin.categories.index')],
                    ['label' => 'Новая категория'],
                ]" />

                <h1 class="admin-title">
                    Новая категория
                </h1>

                <p class="admin-subtitle">
                    Создание нового раздела форума
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
            action="{{ route('admin.categories.store') }}"
            class="admin-category-form"
        >
            @csrf

            @include('admin.categories._form', [
                'category' => null,
                'parents' => $parents,
            ])

            <div class="admin-form-actions">

                <div class="admin-form-actions-left"></div>

                <div class="admin-form-actions-right">

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="admin-secondary-btn"
                    >
                        Отмена
                    </a>

                    <button type="submit" class="admin-primary-button">
                        <i class="bi bi-check2"></i>
                        Создать категорию
                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection
