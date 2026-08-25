@extends('admin.components.app')

@section('content')
    <div class="admin-sidebar">

        <div class="logo">

            <i class="bi bi-shield-lock-fill me-2"></i>

            Forum Admin

        </div>

        <ul>

            <li>
                <a href="#" class="active">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="bi bi-people"></i>
                    Пользователи
                </a>
            </li>
            <li>
                <a href="{{route('roles.index')}}">
                    <i class="bi bi-key"></i>
                    Доступы
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="bi bi-folder2-open"></i>
                    Категории
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="bi bi-chat-square-text"></i>
                    Темы
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="bi bi-chat-left-text"></i>
                    Сообщения
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="bi bi-flag"></i>
                    Жалобы
                </a>
            </li>

            <li>
                <a href="#">
                    <i class="bi bi-gear"></i>
                    Настройки
                </a>
            </li>

        </ul>

    </div>

    <div class="admin-content">

        <div class="admin-navbar">

            <h4 class="m-0">
                Dashboard
            </h4>

            <div class="d-flex align-items-center gap-3">

                <button class="btn btn-light">

                    <i class="bi bi-bell"></i>

                </button>

                <div class="admin-avatar">
                    D
                </div>

            </div>

        </div>

        {{-- Статистика --}}

        <div class="row g-4 mb-4">

            <div class="col-md-3">

                <div class="stat-card">

                    <div>

                        <div class="label">
                            Пользователи
                        </div>

                        <div class="number">
                            154
                        </div>

                    </div>

                    <div class="icon">
                        <i class="bi bi-people"></i>
                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <div>

                        <div class="label">
                            Темы
                        </div>

                        <div class="number">
                            532
                        </div>

                    </div>

                    <div class="icon">
                        <i class="bi bi-chat-square-text"></i>
                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <div>

                        <div class="label">
                            Сообщения
                        </div>

                        <div class="number">
                            4812
                        </div>

                    </div>

                    <div class="icon">
                        <i class="bi bi-chat-left-text"></i>
                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <div>

                        <div class="label">
                            Жалобы
                        </div>

                        <div class="number text-danger">
                            8
                        </div>

                    </div>

                    <div class="icon">
                        <i class="bi bi-flag"></i>
                    </div>

                </div>

            </div>

        </div>

        {{-- Таблица --}}

        <div class="admin-card">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h5 class="mb-0">

                    Последние пользователи

                </h5>

                <button class="btn btn-primary">

                    Добавить

                </button>

            </div>

            <table class="table">

                <thead>

                <tr>

                    <th>ID</th>

                    <th>Имя</th>

                    <th>Email</th>

                    <th>Роль</th>

                    <th>Дата</th>

                    <th></th>

                </tr>

                </thead>

                <tbody>

                <tr>

                    <td>1</td>

                    <td>Admin</td>

                    <td>admin@mail.ru</td>

                    <td>

                    <span class="badge bg-primary">

                        Администратор

                    </span>

                    </td>

                    <td>

                        Сегодня

                    </td>

                    <td class="text-end">

                        <button class="btn btn-sm btn-outline-primary">

                            <i class="bi bi-pencil"></i>

                        </button>

                        <button class="btn btn-sm btn-outline-danger">

                            <i class="bi bi-trash"></i>

                        </button>

                    </td>

                </tr>

                </tbody>

            </table>

        </div>

    </div>
@endsection
