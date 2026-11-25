<?php

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component {

    public string $theme = 'dark';

    public bool $open = false;

    public function mount(): void
    {
        $this->guard();

        $this->theme = auth()->user()->admin_theme ?? 'dark';
    }

    /**
     * Компонент доступен и через /livewire/update — проверяем право сами.
     */
    public function hydrate(): void
    {
        $this->guard();
    }

    /**
     * Сохраняет личную тему админки.
     *
     * Мгновенно тему применяет Alpine (document.documentElement),
     * сервер фиксирует выбор и перерисовывает активный пункт.
     */
    public function setTheme(string $theme): void
    {
        $this->guard();

        Validator::make(
            ['theme' => $theme],
            ['theme' => ['required', 'string', Rule::in(['dark', 'light'])]],
        )->validate();

        auth()->user()->update([
            'admin_theme' => $theme,
        ]);

        $this->theme = $theme;

        /* Страховка, если Alpine-обработчик почему-то не сработал. */
        $this->js("document.documentElement.dataset.adminTheme = '{$theme}'");
    }
    public function toggle() : void
    {
        $this->open = !$this->open;
    }

    private function guard(): void
    {
        abort_unless(auth()->user()?->hasPermission('admin.access'), 403);
    }
};
?>

@php
    $adminUser = auth()->user();
    $adminTheme = $this->theme;
    $adminRole = data_get(config('roles'), ($adminUser?->role?->name ?? '') . '.name', 'Администратор');
@endphp

<div
    class="admin-sidebar-user"
    x-on:click.outside="open = false"
    x-on:keydown.escape.window="open = false"
>

    <div class="admin-user-card">

        <span class="admin-user-avatar">
            @if($adminUser?->avatar)
                <img
                    src="{{ asset('storage/avatars/' . $adminUser->avatar) }}"
                    alt="{{ $adminUser->name }}"
                >
            @else
                {{ mb_strtoupper(mb_substr($adminUser?->name ?? 'A', 0, 1)) }}
            @endif
        </span>

        <span class="admin-user-meta">
            <strong title="{{ $adminUser?->name }}">{{ $adminUser?->name }}</strong>
            <small>{{ $adminRole }}</small>
        </span>

        <button
            type="button"
            class="admin-user-gear"
            wire:click="toggle"

            aria-controls="admin-theme-popover"
            title="Тема админки"
        >
            <i class="bi bi-gear-fill"></i>
        </button>

    </div>


    {{-- МИНИ-ПАНЕЛЬ ТЕМЫ --}}
    <div
        class="admin-theme-popover"
        id="admin-theme-popover"
        wire:show="open"
        wire:transition
        wire:click.outside="toggle"
    >

        <div class="admin-theme-popover-head">
            <span>Тема админки</span>

            <button
                type="button"
                class="admin-theme-popover-close"
                wire:click="toggle"
                aria-label="Закрыть"
            >
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="admin-theme-options">

            <button
                type="button"
                class="admin-theme-option {{ $adminTheme === 'light' ? 'is-active' : '' }}"
                wire:click="setTheme('light')"
            >
                <i class="bi bi-sun-fill"></i>
                <span>Светлая</span>
                <i class="bi bi-check-lg admin-theme-option-check"></i>
            </button>

            <button
                type="button"
                class="admin-theme-option {{ $adminTheme === 'dark' ? 'is-active' : '' }}"
                wire:click="setTheme('dark')"
            >
                <i class="bi bi-moon-stars-fill"></i>
                <span>Тёмная</span>
                <i class="bi bi-check-lg admin-theme-option-check"></i>
            </button>

        </div>

        <p class="admin-theme-hint">
            Видно только вам
        </p>

    </div>

</div>
