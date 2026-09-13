<?php

use App\Models\Conversation;
use Livewire\Component;

new class extends Component {
    public string $search = '';
//    public string $handler;
    public string $model;
    public array $searchColumns;
    public ?string $subField = null;
    public $rows = [];

    public $displayField;

    public function updatedSearch()
    {
        debugbar()->info($this->search);
        $this->rows = $this->model::query()
            ->when($this->search, function ($query, $search) {
                $query->search($search, $this->searchColumns);
            })
            ->limit(10)
            ->get();
    }

    public function selectUser(int $userId):void
    {
        $this->search = '';

        $this->dispatch('user-selected', userId: $userId);
    }


};
?>

<div class="position-relative">

    <input
        wire:model.live.debounce.300ms="search"
        type="text"
        class="form-control form-control-lg shadow-sm"
        placeholder="Поиск пользователя..."
    >

    @if($search)
        <div class="list-group position-absolute w-100 mt-1 shadow-lg rounded-3 overflow-hidden"
             style="z-index: 1050; max-height: 320px; overflow-y: auto;">

            @forelse($rows as $row)

                <a
                    wire:click="selectUser({{$row->id}})"
                    class="list-group-item list-group-item-action border-0 py-2 px-3">

                    <div class="d-flex align-items-center gap-3">
                        <!-- text -->
                        <div class="flex-grow-1">
                            @foreach($displayField as $field)
                                <div class="fw-semibold
                            @if($field === 'email') text-muted @endif">
                                    {{ data_get($row, $field) }}
                                </div>
                            @endforeach

                        </div>

                        @if($subField)
                            <span class="badge {{$row->role->getBadgeClass()}}">
                                {{ data_get($row, $subField) }}
                            </span>
                        @endif

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
