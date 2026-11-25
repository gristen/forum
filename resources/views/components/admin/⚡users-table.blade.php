<?php

use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {

    use WithPagination;

    #[Url(as: 'search')]
    public string $search = '';

    #[Url(as: 'role', history: true)]
    public string $role = '';

    #[Url(as: 'sort', history: true)]
    public string $sort = 'latest';

    public int $perPage = 20;

    public function mount(): void
    {
        $this->authorizeAdmin();
    }

    /**
     * Экшены Livewire идут мимо middleware маршрута — проверяем доступ сами.
     */
    public function hydrate(): void
    {
        $this->authorizeAdmin();
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->hasPermission('admin.access'), 403);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    /**
     * Явное применение фильтров (кнопка / Enter в поиске).
     */
    public function applyFilters(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->role = '';
        $this->sort = 'latest';

        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'users' => User::query()
                ->with('role')
                ->withCount(['topics', 'posts'])
                ->with(['bans' => fn ($query) => $query->active()])
                ->search($this->search)
                ->filterByRole($this->role)
                ->sorted($this->sort)
                ->paginate($this->perPage),

            'roles' => Role::query()
                ->withCount('users')
                ->orderBy('name')
                ->get(),
        ];
    }
};
?>

<section class="admin-panel" id="users">

    <div class="admin-panel-header">

        <div>
            <h2>
                <i class="bi bi-people-fill"></i>
                Список пользователей
            </h2>

            <p>
                Найдено: {{ $users->total() }}
            </p>
        </div>

        <span class="admin-panel-count" wire:loading.class="is-loading">
            {{ $users->total() }}
        </span>

    </div>


    {{-- TOOLBAR --}}
    <form
        wire:submit.prevent="applyFilters"
        class="admin-users-toolbar"
    >

        <div class="admin-users-search">

            <i class="bi bi-search"></i>

            <input
                type="search"
                wire:model.live.debounce.400ms="search"
                value="{{ $search }}"
                class="admin-input"
                placeholder="Имя или email..."
                aria-label="Поиск пользователей"
            >

        </div>


        <select
            wire:model.live="role"
            class="admin-select"
            aria-label="Фильтр по роли"
        >

            <option value="">Все роли</option>

            @foreach($roles as $roleOption)

                <option
                    value="{{ $roleOption->name }}"
                    @selected($role === $roleOption->name)
                >
                    {{ $roleOption->display_name }} ({{ $roleOption->users_count }})
                </option>

            @endforeach

        </select>


        <select
            wire:model.live="sort"
            class="admin-select"
            aria-label="Сортировка"
        >

            <option value="latest" @selected($sort === 'latest')>Сначала новые</option>
            <option value="oldest" @selected($sort === 'oldest')>Сначала старые</option>
            <option value="name" @selected($sort === 'name')>По имени</option>

        </select>


        <button type="submit" class="admin-primary-btn">

            <i class="bi bi-funnel"></i>
            Применить

        </button>


        @if($search !== '' || $role !== '' || $sort !== 'latest')

            <button
                type="button"
                wire:click="resetFilters"
                class="admin-secondary-btn"
            >

                <i class="bi bi-x-lg"></i>
                Сбросить

            </button>

        @endif

    </form>


    {{-- TABLE --}}
    <div class="admin-users-table" wire:loading.class="is-loading">

        <div class="admin-users-head">

            <span>Пользователь</span>
            <span>Роль</span>
            <span>Активность</span>
            <span>Регистрация</span>
            <span>Статус</span>
            <span></span>

        </div>


        @forelse($users as $user)

            <div class="admin-users-row" wire:key="user-{{ $user->id }}">

                {{-- USER --}}
                <div class="admin-user-cell">

                    @if($user->avatar)

                        <img
                            src="{{ asset('storage/avatars/' . $user->avatar) }}"
                            alt="{{ $user->name }}"
                            class="admin-user-avatar"
                            loading="lazy"
                        >

                    @else

                        <span class="admin-user-avatar admin-user-avatar-fallback">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </span>

                    @endif


                    <div class="admin-user-info">

                        <a
                            href="{{ route('admin.users.show', $user) }}"
                            class="admin-user-name"
                            title="{{ $user->name }}"
                        >
                            {{ $user->name }}
                        </a>

                        <span class="admin-user-email" title="{{ $user->email }}">
                            {{ $user->email }}
                        </span>

                    </div>

                </div>


                {{-- ROLE --}}
                <div class="admin-user-role-cell">

                    @if($user->role)

                        <span class="admin-user-role {{ str_replace('bg-', '', $user->role->badge_class) }}">

                            <i class="bi {{ $user->role->icon_class }}"></i>

                            {{ $user->role->display_name }}

                        </span>

                    @else

                        <span class="admin-user-role secondary">
                            Без роли
                        </span>

                    @endif

                </div>


                {{-- ACTIVITY --}}
                <div class="admin-user-activity">

                    <span title="Темы">
                        <i class="bi bi-chat-square-text"></i>
                        {{ $user->topics_count }}
                    </span>

                    <span title="Сообщения">
                        <i class="bi bi-chat"></i>
                        {{ $user->posts_count }}
                    </span>

                </div>


                {{-- REGISTERED --}}
                <div class="admin-user-date">

                    <span>
                        {{ $user->created_at?->format('d.m.Y') }}
                    </span>

                    <small>
                        {{ $user->created_at?->diffForHumans() }}
                    </small>

                </div>


                {{-- STATUS / BAN --}}
                <div class="admin-user-status">

                    @if($user->bans->isNotEmpty())

                        <span class="admin-ban-badge active" title="Забанен">
                            <i class="bi bi-slash-circle"></i>
                            Забанен
                        </span>

                    @else

                        <span class="admin-ban-badge" title="Активен">
                            <i class="bi bi-check-circle"></i>
                            Активен
                        </span>

                    @endif

                </div>


                {{-- ACTION --}}
                <a
                    href="{{ route('admin.users.show', $user) }}"
                    class="admin-user-action"
                    title="Открыть профиль"
                    aria-label="Открыть профиль {{ $user->name }}"
                >
                    <i class="bi bi-arrow-up-right"></i>
                </a>

            </div>

        @empty

            <div class="admin-empty">

                <i class="bi bi-people"></i>

                <span>
                    Пользователи не найдены
                </span>

            </div>

        @endforelse

    </div>


    {{-- PAGINATION --}}
    @if($users->hasPages())

        <div class="admin-pagination">

            <span class="admin-pagination-info">
                Показано {{ $users->firstItem() }}–{{ $users->lastItem() }}
                из {{ $users->total() }}
            </span>

            {{ $users->links('admin.pagination-livewire') }}
            {{ $users->links() }}

        </div>

    @endif

</section>
