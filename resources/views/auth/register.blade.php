@extends('components.app')
@section("content")
    <div class="container py-5">
        <div class="row justify-content-center">

            <div class="col-lg-5 col-md-7">

                <div class="forum-card">

                    <div class="category-header">
                        <h4 class="mb-0">
                            <i class="bi bi-person-plus me-2"></i>
                            Регистрация
                        </h4>
                    </div>

                    <div class="p-4">

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Имя</label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="Введите имя">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Введите email">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Пароль</label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Минимум 8 символов">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    Повторите пароль
                                </label>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="Повторите пароль">
                            </div>

                            <button class="btn btn-primary w-100">
                                Зарегистрироваться
                            </button>

                        </form>

                    </div>

                </div>

                <div class="text-center mt-3">

                    Уже есть аккаунт?

                    <a href="{{ route('login') }}" class="footer-link fw-semibold">
                        Войти
                    </a>

                </div>

            </div>

        </div>
    </div>

@endsection
