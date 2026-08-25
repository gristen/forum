@extends('admin.components.app')

@section('content')

    <div class="container-fluid">

        {{-- Заголовок --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">
                <span class="badge bg-danger me-2">
                    <i class="bi bi-shield-fill"></i>
                </span>

                    Администратор
                </h2>

                <p class="text-muted mb-0">
                    Управление разрешениями роли
                </p>

            </div>

            <div>

                <button class="btn btn-outline-secondary">
                    Отмена
                </button>

                <button class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>
                    Сохранить
                </button>

            </div>

        </div>

        <div class="row g-4">

            {{-- Информация --}}
            <div class="col-lg-4">

                <div class="admin-card">

                    <h5 class="mb-4">
                        Информация
                    </h5>

                    <div class="mb-3">

                        <label class="form-label">
                            Название
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="Администратор">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Системное имя
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="admin">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Цвет
                        </label>

                        <select class="form-select">

                            <option>Danger</option>
                            <option>Primary</option>
                            <option>Success</option>

                        </select>

                    </div>

                    <div>

                        <label class="form-label">
                            Иконка
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="bi-shield-fill">

                    </div>

                </div>

            </div>

            {{-- Permissions --}}
            <div class="col-lg-8">

                <div class="admin-card">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <h5 class="mb-0">

                            Разрешения

                        </h5>

                        <button class="btn btn-sm btn-outline-primary">

                            Выбрать всё

                        </button>

                    </div>

             @foreach($permissions as $group => $groupPermission)

                    <div class="permission-group mb-4">

                        <h6 class="border-bottom pb-2 mb-3">
                            {{__('permissions.groups.' . $group)}}
                        </h6>

                        <div class="row">
                            <form method="POST" action="{{route('roles.update',$role)}}">
                            @csrf
                            @method('PUT')

                            <div class="col-md-6">
                                @foreach($groupPermission as $permission)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input"
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{$permission->id}}"
                                        {{$role->permissions->contains($permission->id) ? 'checked' : ''}}
                                        >
                                        <label class="form-check-label">
                                         {{__('permissions.'.$permission->name)}}
                                        </label>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                    </div>
                    @endforeach
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Сохранить
                        </button>
                    </div>
                    </form>
                </div>

            </div>

        </div>

    </div>

@endsection
