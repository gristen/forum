@extends("admin.components.app")

@section("content")
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="mb-1">Роли</h2>
                <p class="text-muted mb-0">
                    Управление ролями пользователей
                </p>
            </div>

            <button class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>
                Добавить роль
            </button>

        </div>

        <div class="row g-4">

            {{-- Администратор --}}
            @foreach($roles as $role)
                <div class="col-xl-4 col-md-6">

                    <div class="admin-card role-card">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                        <span class="badge bg-danger mb-3">
                            <i class="bi bi-shield-fill me-1"></i>
                            {{$role->name}}
                        </span>

                                <h5 class="mb-1">
                                    id {{$role->id}}
                                </h5>

                                <p class="text-muted mb-0">
                                    Полный доступ ко всем разделам форума.
                                </p>

                            </div>

                            <div class="dropdown">

                                <button class="btn btn-light btn-sm"
                                        data-bs-toggle="dropdown">

                                    <i class="bi bi-three-dots"></i>

                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li>
                                        <a class="dropdown-item" href="{{route('roles.edit',$role)}}">
                                            <i class="bi bi-pencil me-2"></i>
                                            Редактировать
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="bi bi-trash me-2"></i>
                                            Удалить
                                        </a>
                                    </li>

                                </ul>

                            </div>

                        </div>

                        <hr>

                        <div class="row text-center">

                            <div class="col-6">

                                <div class="small text-muted">
                                    Пользователей
                                </div>

                                <div class="fw-bold fs-4">
                                    2
                                </div>

                            </div>

                            <div class="col-6">

                                <div class="small text-muted">
                                    Разрешений
                                </div>

                                <div class="fw-bold fs-4">
                                    18
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            @endforeach


            {{-- Модератор --}}
            {{--  <div class="col-xl-4 col-md-6">

                  <div class="admin-card role-card">

                      <div class="d-flex justify-content-between">

                          <div>

                          <span class="badge bg-success mb-3">
                              <i class="bi bi-person-check-fill me-1"></i>
                              Модератор
                          </span>

                              <h5 class="mb-1">
                                  moderator
                              </h5>

                              <p class="text-muted mb-0">
                                  Может модерировать форум и жалобы.
                              </p>

                          </div>

                          <a href="{{route('roles.edit',[])}}" class="btn btn-light btn-sm">

                             редактировать

                          </a>

                      </div>

                      <hr>

                      <div class="row text-center">

                          <div class="col-6">

                              <div class="small text-muted">
                                  Пользователей
                              </div>

                              <div class="fw-bold fs-4">
                                  6
                              </div>

                          </div>

                          <div class="col-6">

                              <div class="small text-muted">
                                  Разрешений
                              </div>

                              <div class="fw-bold fs-4">
                                  12
                              </div>

                          </div>

                      </div>

                  </div>

              </div>--}}

            {{-- Пользователь --}}
            {{-- <div class="col-xl-4 col-md-6">

                 <div class="admin-card role-card">

                     <div class="d-flex justify-content-between">

                         <div>

                         <span class="badge bg-primary mb-3">
                             <i class="bi bi-person-fill me-1"></i>
                             Пользователь
                         </span>

                             <h5 class="mb-1">
                                 user
                             </h5>

                             <p class="text-muted mb-0">
                                 Стандартная роль форума.
                             </p>

                         </div>

                         <button class="btn btn-light btn-sm">

                             <i class="bi bi-three-dots"></i>

                         </button>

                     </div>

                     <hr>

                     <div class="row text-center">

                         <div class="col-6">

                             <div class="small text-muted">
                                 Пользователей
                             </div>

                             <div class="fw-bold fs-4">
                                 243
                             </div>

                         </div>

                         <div class="col-6">

                             <div class="small text-muted">
                                 Разрешений
                             </div>

                             <div class="fw-bold fs-4">
                                 3
                             </div>

                         </div>

                     </div>

                 </div>

             </div>--}}

        </div>

    </div>
@endsection
