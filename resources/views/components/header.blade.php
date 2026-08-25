
    <header class="forum-header py-2 sticky-top">
        <div class="container">
            <div class="row align-items-center g-2">

                <!-- Логотип -->
                <div class="col-auto col-lg-3 d-flex align-items-center ">
                    <a href="#" class="text-decoration-none d-flex align-items-center gap-2">
                        <div class="bg-primary rounded-3 d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width: 38px; height: 38px; font-size: 1.2rem;">
                            F
                        </div>
                        <span class="fw-bold fs-5 text-dark d-none d-sm-inline">
                               <a class="text-decoration-none text-reset" href="{{route('home')}}">Форум<span class="text-primary">.hub</span></a>
                            </span>
                    </a>
                </div>

                <!-- Поиск -->
                <div class="col d-none d-md-block">
                    <div class="position-relative">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="form-control search-input w-100"
                               placeholder="Поиск по темам, авторам, тегам...">
                    </div>
                </div>

                <!-- Правая часть -->
                <div class="col-auto ms-auto d-flex align-items-center gap-3">
                    @if(\Illuminate\Support\Facades\Auth::check())
                    <!-- Уведомления -->
                    <a href="#" class="text-secondary position-relative" style="font-size: 1.25rem;">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                              style="font-size: 0.6rem; padding: 3px 6px;">
                                3
                            </span>
                    </a>

                    <!-- Сообщения -->
                    <a href="#" class="text-secondary" style="font-size: 1.25rem;">
                        <i class="bi bi-envelope"></i>
                    </a>
                    <!-- Аватар + имя -->
                    <div class="dropdown user-avatar-dropdown">
                        <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-dark"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{asset('storage/avatars/'. auth()->user()->avatar)}}"
                                 alt="avatar" class="avatar-big">
                            <span class="d-none d-sm-inline fw-medium">{{auth()->user()->name}}</span>
                            <i class="bi bi-chevron-down text-secondary-emphasis small"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                            <li><a class="dropdown-item" href="{{route('profile', ['value'=>Auth::user()->name])}}"><i class="bi bi-person me-2"></i>Профиль</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Настройки</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="{{route('logout')}}"><i class="bi bi-box-arrow-right me-2"></i>Выйти</a></li>
                        </ul>
                    </div>
                    @else
                        <a href="{{route('login')}}">Авторизоваться</a>
                    @endif
                </div>

            </div>
        </div>
    </header>

