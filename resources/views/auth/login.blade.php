@extends("components.app")

@section("content")
    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="forum-card overflow-hidden">

                    <div class="row g-0">

                        {{-- Левая часть --}}
                        <div class="col-lg-6">

                            <div class="h-100 d-flex flex-column justify-content-center p-5 bg-light">

                                <h2 class="fw-bold mb-3">
                                    Добро пожаловать!
                                </h2>

                                <p class="text-muted mb-4">
                                    Войдите в свой аккаунт, чтобы создавать темы,
                                    отвечать другим пользователям и участвовать в жизни форума.
                                </p>

                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-chat-dots fs-4 text-primary me-3"></i>

                                    <div>
                                        <strong>Общайтесь</strong><br>
                                        <small class="text-muted">
                                            Создавайте темы и отвечайте другим участникам.
                                        </small>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-hand-thumbs-up fs-4 text-primary me-3"></i>

                                    <div>
                                        <strong>Получайте репутацию</strong><br>
                                        <small class="text-muted">
                                            Собирайте лайки и достижения.
                                        </small>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <i class="bi bi-people fs-4 text-primary me-3"></i>

                                    <div>
                                        <strong>Будьте частью сообщества</strong><br>
                                        <small class="text-muted">
                                            Тысячи интересных обсуждений уже ждут вас.
                                        </small>
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- Правая часть --}}
                        <div class="col-lg-6">

                            <div class="p-5">

                                <h3 class="fw-bold mb-4">
                                    Вход
                                </h3>

                                <form method="POST" action="{{ route('login') }}">

                                    @csrf

                                    <div class="mb-3">

                                        <label class="form-label">
                                            Email
                                        </label>

                                        <input
                                            type="email"
                                            name="email"
                                            class="form-control"
                                            placeholder="Введите Email">

                                    </div>

                                    <div class="mb-4">

                                        <label class="form-label">
                                            Пароль
                                        </label>

                                        <input
                                            type="password"
                                            name="password"
                                            class="form-control"
                                            placeholder="Введите пароль">

                                    </div>


                                    <button class="btn btn-primary w-100 mb-3">

                                        Войти

                                    </button>

                                    <div class="text-center">

                                        Нет аккаунта?

                                        <a href="{{ route('register') }}" class="footer-link fw-semibold">
                                            Зарегистрироваться
                                        </a>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
