@extends('components.app')

@section('content')

    <div class="forum-page">

        <div class="container py-4">

            <x-breadcrumb :items="[
                ['label' => 'Форум', 'url' => route('home')],
                ['label' => 'Новая тема'],
            ]" />


            <div class="row justify-content-center">

                <div class="col-lg-8">

                    <div class="forum-topic-card">

                        {{-- Заголовок --}}
                        <div class="forum-create-header">

                            <span class="forum-create-header-icon">
                                <i class="bi bi-plus-circle"></i>
                            </span>

                            <div>
                                <h5 class="forum-create-header-title">
                                    Создание новой темы
                                </h5>

                                <div class="forum-create-header-subtitle">
                                    Создайте тему для обсуждения
                                </div>
                            </div>

                        </div>

                        {{-- Форма --}}
                        <div class="forum-create-body">

                            <form method="POST" id="topic-form" action="{{ route('topics.store') }}">
                                @csrf

                                {{-- Категория --}}
                                <div class="forum-create-field">

                                    <label for="category" class="forum-create-label">
                                        Категория
                                    </label>

                                    <select
                                        id="category"
                                        name="category_id"
                                        class="form-select"
                                    >

                                        <option selected disabled>
                                            Выберите категорию
                                        </option>

                                        @forelse($categories as $category)
                                            <option value="{{ $category->id }}">
                                                {{ $category->name }}
                                            </option>
                                        @empty
                                            <option disabled>
                                                Пока нет категорий
                                            </option>
                                        @endforelse

                                    </select>

                                    <div class="forum-create-hint">
                                        Выберите раздел, в котором будет создана тема.
                                    </div>

                                </div>


                                {{-- Название темы --}}
                                <div class="forum-create-field">

                                    <label for="title" class="forum-create-label">
                                        Название темы
                                    </label>

                                    <input
                                        type="text"
                                        id="title"
                                        name="title"
                                        class="form-control"
                                        placeholder="Например: Как работает Eloquent?"
                                        maxlength="255"
                                    >

                                    <div class="forum-create-hint">
                                        Постарайтесь кратко описать суть вопроса.
                                    </div>

                                </div>


                                {{-- Сообщение --}}
                                <div class="forum-create-field">

                                    <label for="editor" class="forum-create-label">
                                        Сообщение
                                    </label>

                                    <textarea name="content" id="editor"></textarea>

                                    <input type="hidden" name="content" id="content">

                                    <div class="forum-create-hint">
                                        Здесь можно подробно описать вопрос или начать обсуждение.
                                    </div>

                                </div>

                                {{-- Кнопки --}}
                                <div class="forum-create-actions">

                                    <a
                                        href="#"
                                        class="forum-btn-ghost"
                                    >
                                        <i class="bi bi-arrow-left"></i>
                                        Отмена
                                    </a>

                                    <button
                                        type="submit"
                                        class="forum-btn-accent"
                                    >
                                        <i class="bi bi-plus-lg"></i>
                                        Создать тему
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
