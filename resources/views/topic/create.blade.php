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
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>
    <script
        src="https://cdn.tiny.cloud/1/l2w0fozrnh6we40n11yla1lv7v5cre56nvum4qrph0uxwqva/tinymce/8/tinymce.min.js"
        referrerpolicy="origin"
        crossorigin="anonymous">
    </script>
    <script>
        tinymce.init({
            selector: '#editor',

            height: 500,

            plugins: 'image link lists code',

            toolbar:
                'undo redo | blocks | ' +
                'bold italic underline | ' +
                'alignleft aligncenter alignright | ' +
                'bullist numlist | ' +
                'link image | code',

            menubar: false,

            image_title: true,

            automatic_uploads: false,
            tinymceai_token_provider: async () => {
                await fetch(`https://demo.api.tiny.cloud/1/l2w0fozrnh6we40n11yla1lv7v5cre56nvum4qrph0uxwqva/auth/random`, { method: "POST", credentials: "include" });
                return { token: await fetch(`https://demo.api.tiny.cloud/1/l2w0fozrnh6we40n11yla1lv7v5cre56nvum4qrph0uxwqva/jwt/tinymceai`, { credentials: "include" }).then(r => r.text()) };
            },
            content_style: `
            body {
                font-family: Arial, sans-serif;
                font-size: 16px;
            }
        `
        });

        const form = document.querySelector('#topic-form');
        const content = document.querySelector('#content');

        form.addEventListener('submit', function () {
            content.value = tinymce.get('editor').getContent();
        });
    </script>
   {{-- <script>
        const quill = new Quill('#editor', {
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    ['link', 'image'],
                    [{ 'header': [1, 2, 3, false] }]
                ],

                imageResize: {
                    modules: ['Resize', 'DisplaySize']
                }
            },

            placeholder: 'Излагай...',
            theme: 'snow'
        });

        const form = document.querySelector('#topic-form');
        const content = document.querySelector('#content');

        form.addEventListener('submit', function () {
            content.value = quill.root.innerHTML;
        });
    </script>--}}
@endsection
