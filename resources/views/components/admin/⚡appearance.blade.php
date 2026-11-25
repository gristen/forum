<?php

use App\Models\Setting;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component {

    public string $selected = '';

    public bool $saved = false;

    public function mount(): void
    {
        $this->guard();

        $this->selected = Setting::get('forum.theme', array_key_first(config('themes'))) ?? 'default';
    }

    /**
     * Компонент доступен и через /livewire/update — проверяем право сами.
     */
    public function hydrate(): void
    {
        $this->guard();
    }

    public function updatedSelected(): void
    {
        $this->saved = false;
    }

    public function apply(): void
    {
        $this->guard();

        $this->validate([
            'selected' => ['required', 'string', Rule::in(array_keys(config('themes')))],
        ]);

        Setting::put('forum.theme', $this->selected);

        $this->saved = true;
    }

    public function stopPreview(): void
    {
        $this->guard();

        session()->forget('theme.preview');
    }

    private function guard(): void
    {
        abort_unless(auth()->user()?->hasPermission('admin.access'), 403);
    }
};
?>

<div class="appearance">

    @if(session('theme.preview'))

        <div class="appearance-banner">

            <i class="bi bi-eye"></i>

            <span>
                Включён предпросмотр темы
                «{{ config('themes.' . session('theme.preview') . '.label', session('theme.preview')) }}».
                Она видна только вам.
            </span>

            <button
                type="button"
                wire:click="stopPreview"
                class="appearance-banner-off"
            >
                Выключить
            </button>

        </div>

    @endif


    <div class="appearance-grid">

        @foreach(config('themes', []) as $key => $themeOption)

            <div
                class="appearance-card {{ $selected === $key ? 'is-active' : '' }}"
                wire:key="theme-{{ $key }}"
            >

                <label class="appearance-card-main">

                    <input
                        type="radio"
                        name="theme"
                        value="{{ $key }}"
                        wire:model.live="selected"
                    >

                    <span
                        class="appearance-swatch"
                        style="
                            --swatch-bg: {{ $themeOption['swatches']['bg'] }};
                            --swatch-surface: {{ $themeOption['swatches']['surface'] }};
                            --swatch-text: {{ $themeOption['swatches']['text'] }};
                            --swatch-accent: {{ $themeOption['swatches']['accent'] }};
                        "
                    >
                        <span class="appearance-swatch-bar"></span>
                        <span class="appearance-swatch-line"></span>
                        <span class="appearance-swatch-line is-short"></span>
                        <span class="appearance-swatch-dot"></span>
                    </span>

                    <span class="appearance-card-info">

                        <strong>
                            {{ $themeOption['label'] }}
                        </strong>

                        <small>
                            {{ $themeOption['description'] }}
                        </small>

                    </span>

                </label>


                <a
                    href="{{ route('home', ['theme' => $key]) }}"
                    target="_blank"
                    rel="noopener"
                    class="appearance-preview-link"
                >
                    <i class="bi bi-box-arrow-up-right"></i>
                    Предпросмотр
                </a>

            </div>

        @endforeach

    </div>


    <div class="appearance-actions">

        @error('selected')
            <p class="appearance-error">
                <i class="bi bi-exclamation-triangle"></i>
                {{ $message }}
            </p>
        @enderror

        <button
            type="button"
            class="admin-primary-btn"
            wire:click="apply"
        >
            <i class="bi bi-check-lg"></i>
            Применить для всех
        </button>

        @if($saved)

            <span class="appearance-saved">
                <i class="bi bi-check-circle"></i>
                Сохранено
            </span>

        @endif

        <p class="appearance-hint">
            Новую тему пользователи увидят при следующем заходе.
            Если пользователь выбрал тему в своём меню, его выбор важнее глобальной настройки.
        </p>

    </div>

</div>
