@extends('components.app')

@section('content')

    <div class="container py-4">

        {{-- Breadcrumbs --}}
        <nav class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none">Форум</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none">Вопросы</a>
                </li>
                <li class="breadcrumb-item active">
                    Laravel
                </li>
            </ol>
        </nav>


        {{-- Topic header --}}
        <div class="card border-0 shadow-sm mb-3">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start gap-3">

                    <div>
                        <div class="text-muted small mb-2">
                            Вопрос
                        </div>

                        <h1 class="h3 fw-bold mb-2">
                            {{$topic->title}}
                        </h1>

                        <div class="d-flex align-items-center gap-3 text-muted small">

                        <span>
                            <i class="bi bi-person me-1"></i>
                            {{$topic->user->name}}
                        </span>

                            <span>
                            <i class="bi bi-clock me-1"></i>
                           {{ $topic->created_at->diffForHumans() }}
                        </span>

                            <span>
                            <i class="bi bi-chat-left-text me-1"></i>
                            12 сообщений \ доделать
                        </span>

                        </div>
                    </div>

                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-three-dots"></i>
                    </button>

                </div>

            </div>

        </div>


        {{-- Posts --}}
        <div class="d-flex flex-column gap-3">

            {{-- Post --}}
            @foreach($posts as $post)
                <div class="card border-0 shadow-sm overflow-hidden">

                    <div class="row g-0">

                        {{-- User --}}
                        <div class="col-md-2 border-end bg-light">

                            <div class="p-4 text-center">

                                <div class="rounded-circle bg-primary text-white
                                    d-flex align-items-center justify-content-center
                                    mx-auto mb-3"
                                     style="width: 72px; height: 72px; font-size: 28px;">

                                    A

                                </div>

                                <div class="fw-semibold mb-1">
                                    {{$post->user->name}}
                                </div>

                                <span class="badge {{$topic->user->role->badge_class}} mb-3">
                            {{$topic->user->role->display_name}}
                            </span>

                                <div class="small text-muted">
                                    Сообщений: 128
                                </div>

                                <div class="small text-muted">
                                    На форуме с 2025
                                </div>

                            </div>

                        </div>


                        {{-- Message --}}
                        <div class="col-md-10">

                            <div class="p-4">

                                <div class="d-flex justify-content-between
                                    align-items-center mb-3">

                            <span class="text-muted small">

                            </span>

                                    <span class="text-muted small">
                                {{$post->created_at->diffForHumans()}}
                            </span>

                                </div>

                                <div class="fs-6" style="line-height: 1.7;">
                                    {!! $post->content !!}

                                </div>

                            </div>


                            {{-- Post footer --}}
                            <div class="px-4 py-3 border-top
                                d-flex justify-content-between
                                align-items-center">

                                <div class="d-flex gap-2">

                                    <button class="btn btn-sm btn-light">
                                        <i class="bi bi-hand-thumbs-up me-1"></i>
                                        5
                                    </button>

                                    <button class="btn btn-sm btn-light">
                                        <i class="bi bi-quote me-1"></i>
                                        Цитировать
                                    </button>

                                </div>

                                <button class="btn btn-sm btn-light">
                                    <i class="bi bi-link-45deg"></i>
                                </button>

                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>


        {{-- Pagination --}}
        <div class="d-flex justify-content-center my-4">

            <nav>
                <ul class="pagination mb-0">

                    <li class="page-item disabled">
                        <a class="page-link" href="#">
                            &laquo;
                        </a>
                    </li>

                    <li class="page-item active">
                        <a class="page-link" href="#">
                            1
                        </a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="#">
                            2
                        </a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="#">
                            3
                        </a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="#">
                            &raquo;
                        </a>
                    </li>

                </ul>
            </nav>

        </div>


        {{-- Reply --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <h5 class="fw-bold mb-3">
                    Ответить в теме
                </h5>

                <textarea
                    class="form-control mb-3"
                    rows="6"
                    placeholder="Напишите сообщение..."
                ></textarea>

                <div class="d-flex justify-content-end">

                    <button class="btn btn-primary px-4">
                        <i class="bi bi-send me-2"></i>
                        Отправить
                    </button>

                </div>

            </div>

        </div>

    </div>

@endsection
