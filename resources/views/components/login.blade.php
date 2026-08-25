@section("content")
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">

                <div class="forum-card">

                    <div class="category-header">
                        <h4 class="mb-0">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Вход
                        </h4>
                    </div>

                    <div class="p-4">

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Введите email"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Пароль</label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Введите пароль"
                                    required>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">

                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="remember"
                                        id="remember">

                                    <label class="form-check-label" for="remember">
                                        Запомнить меня
                                    </label>
                                </div>

                                <a href="#" class="footer-link">
                                    Забыли пароль?
                                </a>

                            </div>

                            <button class="btn btn-primary w-100">
                                Войти
                            </button>

                        </form>

                    </div>

                </div>

                <div class="text-center mt-3">
                    Нет аккаунта?

                    <a href="{{ route('register') }}" class="footer-link fw-semibold">
                        Зарегистрироваться
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
