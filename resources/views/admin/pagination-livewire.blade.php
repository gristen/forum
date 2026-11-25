@if ($paginator->hasPages())

    <nav aria-label="Пагинация">

        <ul class="pagination">

            {{-- НАЗАД --}}
            @if ($paginator->onFirstPage())

                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">
                        <i class="bi bi-chevron-left"></i>
                    </span>
                </li>

            @else

                <li class="page-item">
                    <button
                        type="button"
                        class="page-link"
                        wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        rel="prev"
                        aria-label="Назад"
                    >
                        <i class="bi bi-chevron-left"></i>
                    </button>
                </li>

            @endif


            {{-- СТРАНИЦЫ --}}
            @foreach ($elements as $element)

                @if (is_string($element))

                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">{{ $element }}</span>
                    </li>

                @endif


                @if (is_array($element))

                    @foreach ($element as $page => $url)

                        @if ($page == $paginator->currentPage())

                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>

                        @else

                            <li class="page-item">
                                <button
                                    type="button"
                                    class="page-link"
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                >
                                    {{ $page }}
                                </button>
                            </li>

                        @endif

                    @endforeach

                @endif

            @endforeach


            {{-- ВПЕРЁД --}}
            @if ($paginator->hasMorePages())

                <li class="page-item">
                    <button
                        type="button"
                        class="page-link"
                        wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        rel="next"
                        aria-label="Вперёд"
                    >
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </li>

            @else

                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true">
                        <i class="bi bi-chevron-right"></i>
                    </span>
                </li>

            @endif

        </ul>

    </nav>

@endif
