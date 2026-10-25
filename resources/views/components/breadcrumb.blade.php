@props(['items' => []])

{{-- Хлебные крошки форума.

     Использование:
     <x-breadcrumb :items="[
         ['label' => 'Форум', 'url' => route('home')],
         ['label' => $topic->short_title],
     ]" />

     Последний элемент всегда считается активным (без ссылки). --}}

<nav {{ $attributes->merge(['class' => 'forum-breadcrumb mb-3']) }}>

    <ol class="breadcrumb mb-0">

        @foreach ($items as $item)

            @php
                $label = is_array($item) ? ($item['label'] ?? '') : $item;
                $url = is_array($item) ? ($item['url'] ?? null) : null;
            @endphp

            <li
                class="breadcrumb-item {{ $loop->last ? 'active' : '' }}"
                @if ($loop->last) aria-current="page" @endif
            >

                @if ($url && ! $loop->last)

                    <a href="{{ $url }}">
                        {{ $label }}
                    </a>

                @else

                    {{ $label }}

                @endif

            </li>

        @endforeach

    </ol>

</nav>
