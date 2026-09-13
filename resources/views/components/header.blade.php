<header class="forum-header">

    <div class="container">

        <div class="forum-header-inner">

            {{-- =====================================================
                 ЛОГОТИП
            ====================================================== --}}

            <div class="forum-logo">

                <a
                    href="{{ route('home') }}"
                    class="forum-logo-link"
                >

                    <span class="forum-logo-icon">
                        F
                    </span>

                    <span class="forum-logo-text">
                        Форум<span>.hub</span>
                    </span>

                </a>

            </div>


            {{-- =====================================================
                 ПОИСК
            ====================================================== --}}

            <div class="forum-search">

                <div class="forum-search-box">

                    <input
                        type="text"
                        class="forum-search-input"
                        placeholder="Поиск по темам, авторам, тегам..."
                    >

                    <i class="bi bi-search forum-search-icon"></i>

                </div>

            </div>


            {{-- =====================================================
                 ПРАВАЯ ЧАСТЬ
            ====================================================== --}}

            <div class="forum-header-actions">

                @if(\Illuminate\Support\Facades\Auth::check())

                    {{-- =================================================
                         УВЕДОМЛЕНИЯ
                    ================================================== --}}

                    <a
                        href="#"
                        class="forum-header-action"
                        title="Уведомления"
                    >

                        <i class="bi bi-bell"></i>

                        <span class="forum-notification-badge">
                            3
                        </span>

                    </a>


                    {{-- =================================================
                         СООБЩЕНИЯ
                    ================================================== --}}

                    <a
                        href="{{route('messages')}}"
                        class="forum-header-action"
                        title="Сообщения"
                    >

                        <i class="bi bi-envelope"></i>

                    </a>


                    {{-- =================================================
                         PROFILE
                    ================================================== --}}

                    <div class="dropdown forum-user-dropdown">

                        <a
                            href="#"
                            class="forum-user"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >

                            <img
                                src="{{ auth()->user()->avatar
                                    ? asset('storage/avatars/' . auth()->user()->avatar)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=8b5cf6&color=fff&size=80'
                                }}"
                                alt="{{ auth()->user()->name }}"
                                class="forum-header-avatar"
                            >

                            <span class="forum-user-name">
                                {{ auth()->user()->name }}
                            </span>

                            <i class="bi bi-chevron-down forum-user-arrow"></i>

                        </a>


                        {{-- =================================================
                             DROPDOWN
                        ================================================== --}}

                        <ul class="dropdown-menu dropdown-menu-end forum-user-menu">

                            {{-- Профиль --}}

                            <li>

                                <a
                                    class="dropdown-item"
                                    href="{{ route('profile', ['value' => Auth::user()->name]) }}"
                                >

                                    <i class="bi bi-person"></i>

                                    <span>
                                        Профиль
                                    </span>

                                </a>

                            </li>


                            {{-- Настройки --}}

                            <li>

                                <a
                                    class="dropdown-item"
                                    href="#"
                                >

                                    <i class="bi bi-gear"></i>

                                    <span>
                                        Настройки
                                    </span>

                                </a>

                            </li>


                            {{-- Разделитель --}}

                            <li>

                                <hr class="dropdown-divider">

                            </li>


                            {{-- Выход --}}

                            <li>

                                <a
                                    class="dropdown-item forum-logout"
                                    href="{{ route('logout') }}"
                                >

                                    <i class="bi bi-box-arrow-right"></i>

                                    <span>
                                        Выйти
                                    </span>

                                </a>

                            </li>

                        </ul>

                    </div>

                @else

                    {{-- =================================================
                         LOGIN
                    ================================================== --}}

                    <a
                        href="{{ route('login') }}"
                        class="forum-login-button"
                    >

                        <i class="bi bi-box-arrow-in-right"></i>

                        Авторизоваться

                    </a>

                @endif

            </div>

        </div>

    </div>

</header>
