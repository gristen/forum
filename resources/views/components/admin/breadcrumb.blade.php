@props([
    'items' => [],
    'class' => 'admin-breadcrumb',
])

{{-- Хлебные крошки админ-панели.

     Использование:
     <x-admin.breadcrumb :items="[
         ['label' => 'Админ-панель'],
         ['label' => 'Категории', 'url' => route('admin.categories.index')],
         ['label' => $category->name],
     ]" />

     Элемент без url выводится как текст, с url — как ссылка.
     Через class можно передать другой класс-обёртки (например role-breadcrumb). --}}

<div class="{{ $class }}">

    @foreach ($items as $item)

        @if (! $loop->first)
            <i class="bi bi-chevron-right"></i>
        @endif

        @php
            $label = is_array($item) ? ($item['label'] ?? '') : $item;
            $url = is_array($item) ? ($item['url'] ?? null) : null;
        @endphp

        @if ($url)

            <a
                href="{{ $url }}"
                @if ($loop->last) aria-current="page" @endif
            >
                {{ $label }}
            </a>

        @else

            <span @if ($loop->last) aria-current="page" @endif>
                {{ $label }}
            </span>

        @endif

    @endforeach

</div>
