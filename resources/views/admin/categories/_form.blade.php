@php
    /** @var \App\Models\Category|null $category */
    $category = $category ?? null;

    $currentName = old('name', $category->name ?? '');
    $currentDescription = old('description', $category->description ?? '');
    $currentIcon = old('icon', $category->icon ?? 'bi-folder-fill');
    $currentParent = old('parent_id', $category->parent_id ?? '');
    $currentOrder = old('order', $category->order ?? 0);
@endphp

<section class="admin-panel">

    <div class="admin-panel-header">

        <div>
            <h2>
                <i class="bi bi-folder2-open"></i>
                Основные настройки
            </h2>

            <p>
                Название, расположение и оформление раздела
            </p>
        </div>

        @if($category)
            <span class="admin-panel-count">
                #{{ $category->id }}
            </span>
        @endif

    </div>


    <div class="admin-panel-body">

        <div class="admin-form-grid">

            {{-- НАЗВАНИЕ --}}
            <div class="admin-field">

                <label for="name" class="admin-label">
                    Название
                    <span class="admin-required">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    class="admin-input @error('name') is-invalid @enderror"
                    value="{{ $currentName }}"
                    placeholder="Например: Laravel"
                    maxlength="100"
                    required
                >

                @error('name')
                    <div class="admin-field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- ИКОНКА --}}
            <div class="admin-field">

                <label for="icon" class="admin-label">
                    Иконка
                </label>

                <div class="admin-icon-input">

                    <span class="admin-icon-preview">
                        <i id="icon-preview" class="bi {{ $currentIcon ?: 'bi-folder-fill' }}"></i>
                    </span>

                    <input
                        type="text"
                        id="icon"
                        name="icon"
                        list="icon-suggestions"
                        class="admin-input @error('icon') is-invalid @enderror"
                        value="{{ $currentIcon }}"
                        placeholder="bi-folder-fill"
                        maxlength="60"
                    >

                </div>

                <datalist id="icon-suggestions">
                    <option value="bi-folder-fill"></option>
                    <option value="bi-folder2-open"></option>
                    <option value="bi-chat-square-text"></option>
                    <option value="bi-chat-fill"></option>
                    <option value="bi-code-slash"></option>
                    <option value="bi-braces"></option>
                    <option value="bi-database-fill"></option>
                    <option value="bi-terminal"></option>
                    <option value="bi-lightning-charge-fill"></option>
                    <option value="bi-question-circle-fill"></option>
                    <option value="bi-bug-fill"></option>
                    <option value="bi-rocket-takeoff-fill"></option>
                    <option value="bi-palette-fill"></option>
                    <option value="bi-cpu-fill"></option>
                    <option value="bi-shield-fill"></option>
                    <option value="bi-globe2"></option>
                </datalist>

                @error('icon')
                    <div class="admin-field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

                <div class="admin-field-hint">
                    <i class="bi bi-info-circle"></i>
                    Класс иконки Bootstrap Icons, например bi-folder-fill
                </div>

            </div>


            {{-- РОДИТЕЛЬСКАЯ КАТЕГОРИЯ --}}
            <div class="admin-field">

                <label for="parent_id" class="admin-label">
                    Родительская категория
                </label>

                <select
                    id="parent_id"
                    name="parent_id"
                    class="admin-select @error('parent_id') is-invalid @enderror"
                >
                    <option value="">Верхний уровень</option>

                    @foreach($parents as $parent)
                        <option
                            value="{{ $parent->id }}"
                            @selected((string) $currentParent === (string) $parent->id)
                        >
                            {{ $parent->name }}
                        </option>
                    @endforeach

                </select>

                @error('parent_id')
                    <div class="admin-field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

                <div class="admin-field-hint">
                    <i class="bi bi-info-circle"></i>
                    Оставьте пустым, чтобы категория была разделом верхнего уровня
                </div>

            </div>


            {{-- ПОРЯДОК --}}
            <div class="admin-field">

                <label for="order" class="admin-label">
                    Порядок вывода
                </label>

                <input
                    type="number"
                    id="order"
                    name="order"
                    class="admin-input @error('order') is-invalid @enderror"
                    value="{{ $currentOrder }}"
                    min="0"
                    max="9999"
                    step="1"
                >

                @error('order')
                    <div class="admin-field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

                <div class="admin-field-hint">
                    <i class="bi bi-info-circle"></i>
                    Чем меньше значение, тем выше категория в списке
                </div>

            </div>


            {{-- ОПИСАНИЕ --}}
            <div class="admin-field admin-field-wide">

                <label for="description" class="admin-label">
                    Описание
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    maxlength="500"
                    class="admin-textarea @error('description') is-invalid @enderror"
                    placeholder="Коротко о том, что обсуждают в этом разделе"
                >{{ $currentDescription }}</textarea>

                @error('description')
                    <div class="admin-field-error">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror

            </div>


            @if($category)
                <div class="admin-field admin-field-wide">

                    <span class="admin-label">
                        Адрес раздела
                    </span>

                    <div class="admin-slug">
                        <i class="bi bi-link-45deg"></i>
                        <code>{{ $category->slug }}</code>

                        <span>
                            Генерируется из названия и обновляется вместе с ним
                        </span>
                    </div>

                </div>
            @endif

        </div>

    </div>

</section>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('icon');
        const preview = document.getElementById('icon-preview');

        if (!input || !preview) {
            return;
        }

        const fallback = 'bi-folder-fill';

        input.addEventListener('input', function () {
            const value = input.value.trim();
            const iconClass = value === '' ? fallback : value;

            preview.className = iconClass.startsWith('bi-') ? 'bi ' + iconClass : 'bi ' + fallback;
        });
    });
</script>
