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
                    ['label' => 'Категории'],
                ]" />

                <h1 class="admin-title">
                    Категории
                </h1>

                <p class="admin-subtitle">
                    Управление разделами форума
                </p>

            </div>

            <a
                href="{{ route('admin.categories.create') }}"
                class="admin-primary-button"
            >
                <i class="bi bi-plus-lg"></i>
                Создать категорию
            </a>

        </div>


        {{-- CATEGORIES --}}
        <section class="admin-panel">

            <div class="admin-panel-header">

                <div>
                    <h2>
                        <i class="bi bi-folder-fill"></i>
                        Все категории
                    </h2>

                    <p>
                        {{ $categories->count() }} категорий
                    </p>
                </div>

                <span class="admin-panel-count">
                    {{ $categories->count() }}
                </span>

            </div>


            <div class="admin-category-list">

                @forelse($categories as $category)

                    <div class="admin-category-row">

                        {{-- ICON --}}
                        <div class="admin-category-icon">
                            <i class="bi {{ $category->icon ?? 'bi-folder-fill' }}"></i>
                        </div>


                        {{-- INFO --}}
                        <div class="admin-category-main">

                            <div class="admin-category-name">

                                <a href="{{ route('admin.categories.edit', $category) }}">
                                    {{ $category->name }}
                                </a>

                                @if($category->parent)
                                    <span class="admin-category-parent">
                                        {{ $category->parent->name }}
                                    </span>
                                @endif

                            </div>

                            @if($category->description)

                                <span class="admin-category-description">
                                    {{ $category->description }}
                                </span>

                            @endif

                            <span class="admin-category-slug">
                                /{{ $category->slug }}
                            </span>

                        </div>


                        {{-- TOPICS --}}
                        <div class="admin-category-stat">

                            <strong>
                                {{ $category->topics_count }}
                            </strong>

                            <span>
                                тем
                            </span>

                        </div>


                        {{-- ORDER --}}
                        <div class="admin-category-order">

                            <span>
                                #{{ $category->order }}
                            </span>

                        </div>


                        {{-- ACTIONS --}}
                        <div class="admin-category-actions">

                            <a
                                href="{{ route('admin.categories.edit', $category) }}"
                                class="admin-icon-button"
                                title="Редактировать"
                            >
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form
                                action="{{ route('admin.categories.destroy', $category) }}"
                                method="POST"
                                onsubmit="return confirm('Удалить категорию «{{ $category->name }}»?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="admin-icon-button danger"
                                    title="Удалить"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </div>

                    </div>

                @empty

                    <div class="admin-empty">

                        <i class="bi bi-folder-x"></i>

                        <span>
                            Категорий пока нет
                        </span>

                        <a href="{{ route('admin.categories.create') }}">
                            Создать первую категорию
                        </a>

                    </div>

                @endforelse

            </div>

        </section>

    </div>

@endsection
