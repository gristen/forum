<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0,
                   maximum-scale=1.0, minimum-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>
        @yield('title', 'Админ-панель')
    </title>

    @vite([
        'resources/css/admin.css',
        'resources/js/app.js'
    ])
</head>

<body>

<div class="admin-layout">
    @if (session('success'))
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div
                id="successToast"
                class="toast text-bg-success border-0"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
            >
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>

                    <button
                        type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"
                        aria-label="Закрыть"
                    ></button>
                </div>
            </div>
        </div>
    @endif
    {{-- SIDEBAR --}}
    @include('admin.components.sidebar')
    {{-- ОСНОВНОЙ КОНТЕНТ --}}
    <main class="admin-content">
        @yield('content')
    </main>

</div>

</body>
</html>
