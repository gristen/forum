<?php

use App\Models\Conversation;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

new class extends Component {

    public string $search = '';
    public array $models = [];
    public array $rows = [];

    public function updatedSearch(): void
    {
        /* каждый ввод пересобирает список заново */
        $this->rows = [];

        $search = trim($this->search);

        if ($search === '') {
            return;
        }

        foreach ($this->models as $config) {

             /** @var \Illuminate\Database\Eloquent\Model $model */
            $model = $config['model'];

            $rows = $model::query()
                ->search($search, $config['search-columns'])
                ->limit(5)
                ->get();

            foreach ($rows as $row) {

               $this->rows[] = [
                    'row' => $row,
                    'type' => $config['type'],
                    'display-field' => $config['display-field'],
                ];
            }

        }
    }


    public function select(string $type, int $id): void
    {
        debugbar()->info("type = $type");
        debugbar()->info("id = $id");

        $config = config("search.$type");
        debugbar()->info($config);

        $this->search = '';
        $this->redirect(
            route($config['route'], $id)
        );

    }


};
?>

<div class="position-relative">

    <input
        wire:model.live.debounce.300ms="search"
        type="text"
        class="form-control form-control-lg shadow-sm forum-search-input"
        placeholder="Поиск..."
    >

    @if($search)
        <div class="list-group position-absolute w-100 mt-1 shadow-lg rounded-3 overflow-hidden"
             style="z-index: 1050; max-height: 320px; overflow-y: auto;">

            @forelse($rows as $result)

                    @php
                        /** @var \Illuminate\Database\Eloquent\Model $row */
                          $row = $result['row'];

                          $displayFields = $result['display-field'];

                          $config = config("search.{$result['type']}");

                @endphp

                <a
                    wire:click="select('{{ $result['type'] }}', {{ $row->id }})"
                    class="list-group-item list-group-item-action border-0 py-2 px-3">

                    <div class="d-flex align-items-center gap-3">
                        {{-- ИКОНКА --}}

                        <div class="flex-shrink-0">

                            <i
                                class="bi {{ $config['icon'] }} {{ $config['color'] }}"
                            ></i>

                        </div>
                        <!-- text -->
                        <div class="flex-grow-1">
                            @foreach($displayFields as $field)
                                <div class="fw-semibold
                            @if($field === 'email') text-muted @endif">
                                    {{ data_get($row, $field) }}
                                </div>
                            @endforeach

                        </div>
                        {{-- ТИП РЕЗУЛЬТАТА --}}

                        <div class="flex-shrink-0">

                            <span class="text-muted small">
                                {{ $config['name'] }}
                            </span>

                        </div>

                    </div>

                </a>

            @empty

                <div class="text-center py-3 text-muted">
                    Ничего не найдено 😢
                </div>

            @endforelse

        </div>
    @endif

</div>
