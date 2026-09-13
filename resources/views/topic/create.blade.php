@extends('components.app')

@section('content')

    <div class="container py-4">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="forum-card">

                    {{-- Заголовок --}}
                    <div class="category-header d-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle text-primary"></i>

                        <div>
                            <h5 class="mb-0">Создание новой темы</h5>
                            <small class="text-secondary-emphasis">
                                Создайте тему для обсуждения
                            </small>
                        </div>
                    </div>

                    {{-- Форма --}}
                    <div class="p-4">

                        <form method="POST"  id="topic-form" action="{{route('topics.store')}}">
                        @csrf
                            {{-- Категория --}}
                            <div class="mb-4">

                                <label for="category" class="form-label fw-medium">
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
                                    <option value="{{$category->id}}">
                                       {{$category->name}}
                                    </option>
                                    @empty
                                        <p>Пока нет категорий</p>
                                    @endforelse

                                </select>

                                <div class="form-text">
                                    Выберите раздел, в котором будет создана тема.
                                </div>

                            </div>


                            {{-- Название темы --}}
                            <div class="mb-4">

                                <label for="title" class="form-label fw-medium">
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

                                <div class="form-text">
                                    Постарайтесь кратко описать суть вопроса.
                                </div>

                            </div>


                            {{-- Сообщение --}}
                            <div class="mb-4">

                                <label for="content" class="form-label fw-medium">
                                    Сообщение
                                </label>
                                {{--INFO: 123--}}
                               {{-- <div id="editor"></div>--}}
                                <textarea name="content" id="editor"></textarea>


                                <input type="hidden" name="content" id="content">

                                <div class="form-text">
                                    Здесь можно подробно описать вопрос или начать обсуждение.
                                </div>

                            </div>

                            {{-- Кнопки --}}
                            <div class="d-flex justify-content-between align-items-center">

                                <a
                                    href="#"
                                    class="btn btn-light border"
                                >
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Отмена
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary px-4"
                                >
                                    <i class="bi bi-plus-lg me-1"></i>
                                    Создать тему
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


@endsection
