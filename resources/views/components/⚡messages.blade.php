<?php

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public $conversations;
    public $users = null;
    public $conversation = null;
    public $content = null;

    public function mount(): void
    {
    $this->loadConversations();
    }

    public function loadConversations()
    {
        $this->conversations = auth()->user()
            ->conversations()
            ->wherePivotNull('deleted_at')
            ->get();
    }

    public function openConversation($conversationId, $userId = null)
    {

        $this->conversation = Conversation::query()
            ->with(['userTwo',
                'userOne',
                'messages.user'])
            ->findOrFail($conversationId);
    }

    #[On('user-selected')]
    public function startConversation(int $userId)
    {
        $currentUserId = auth()->user()->id;

        [$userOne, $userTwo] = collect([
            $currentUserId,
            $userId,
        ])->sort();

        $this->conversation = Conversation::query()
            ->firstOrCreate([
                'user_one_id' => $userOne,
                'user_two_id' => $userTwo,
            ]);

        $this->conversation->users()->sync([
            $userOne => ['deleted_at' => null],
            $userTwo => ['deleted_at' => null],
        ]);

        $this->conversation->load([
            'userOne',
            'userTwo',
            'messages.user'
        ]);
    }

    public function deleteConversation(int $conversationId):void
    {
        $conversation = Conversation::query()->findOrFail($conversationId);

        $conversation->users()->updateExistingPivot(auth()->user()->id,[
            'deleted_at'
        ]);

        $this->loadConversations();
    }

    #[On('message-received')]
    public function messageReceived(int $messageId, int $conversationId): void
    {
        if ($this->conversation?->id !== $conversationId) {
            return;
        }

        $this->conversation->load('messages.user');
    }

    public function store()
    {
        $this->validate([
            'content' => ['required', 'string', 'max:5000'],
        ]);
        $message = $this->conversation->messages()->create([
            'user_id' => auth()->id(),
            'content' => $this->content,
        ]);
        $this->content = null;
        broadcast(new MessageSent($message));
    }

};

?>

<div>

    <div class="container py-4">

        {{-- Breadcrumb --}}
        <nav class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        Форум
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Личные сообщения
                </li>
            </ol>
        </nav>


        <div class="forum-card overflow-hidden">

            <div class="row g-0" style="min-height: 650px;">

                {{-- ========================= --}}
                {{-- СПИСОК ДИАЛОГОВ --}}
                {{-- ========================= --}}

                <div class="col-lg-4 border-end">

                    {{-- Заголовок --}}
                    <div class="p-3 border-bottom">

                        <div class="d-flex align-items-center justify-content-between">

                            <div>
                                <h5 class="fw-bold mb-1">
                                    Сообщения
                                </h5>

                                <div class="small text-secondary">
                                    Ваши личные переписки
                                </div>
                            </div>

                            <a href="{{ route('users.index') }}"
                               class="btn btn-primary btn-sm"
                               title="Новое сообщение">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                        </div>

                    </div>
                    <livewire:search
                        model="\App\Models\User"
                        :search-columns="['name', 'email']"
                        :display-field="['name', 'email']"
                    />

                    {{-- Диалоги --}}
                    <div class="conversation-list">

                        @forelse($conversations as $item)

                            @php
                                $otherUser = $item->user_one_id === auth()->id()
                                    ? $item->userTwo
                                    : $item->userOne;

                                $lastMessage = $item->messages->first();
                            @endphp


                            <a
                                wire:click="openConversation({{ $item->id }})"
                                class="conversation-item d-flex gap-3 p-3 text-decoration-none
                            {{ isset($conversation) && $conversation->id === $item->id ? 'active' : '' }}"
                            >

                                {{-- Аватар --}}
                                <div class="position-relative flex-shrink-0">

                                    <img
                                        src="{{ $otherUser->avatar
                                        ? asset('storage/avatars/' . $otherUser->avatar)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) . '&background=0d6efd&color=fff&size=45'
                                    }}"
                                        class="avatar-md"
                                        alt="{{ $otherUser->name }}"
                                    >

                                    <span class="online-dot"></span>

                                </div>


                                {{-- Информация --}}
                                <div class="flex-grow-1 min-w-0">

                                    <div class="d-flex justify-content-between gap-2">

                                    <span class="fw-semibold text-dark text-truncate">
                                        {{ $otherUser->name }}
                                    </span>

                                        @if($lastMessage)
                                            <small class="text-secondary flex-shrink-0">
                                                {{ $lastMessage->created_at->diffForHumans(null, true) }}
                                            </small>
                                        @endif

                                    </div>


                                    @if($lastMessage)

                                        <div class="small text-secondary text-truncate mt-1">
                                            {{ $lastMessage->sender_id === auth()->id() ? 'Вы: ' : '' }}
                                            {{ strip_tags($lastMessage->content) }}
                                        </div>

                                    @else

                                        <div class="small text-secondary mt-1">
                                            Нет сообщений
                                        </div>

                                    @endif

                                </div>

                            </a>

                        @empty

                            <div class="text-center text-secondary p-5">

                                <i class="bi bi-chat-square-text fs-1 d-block mb-3"></i>

                                <div class="fw-semibold">
                                    Пока нет сообщений
                                </div>

                                <div class="small mt-1">
                                    Начните переписку с пользователем
                                </div>

                            </div>

                        @endforelse

                    </div>

                </div>


                {{-- ========================= --}}
                {{-- ТЕКУЩИЙ ЧАТ --}}
                {{-- ========================= --}}

                <div class="col-lg-8 d-flex flex-column">

                    @if(isset($conversation))

                        @php
                            $otherUser = $conversation->user_one_id === auth()->id()
                                ? $conversation->userTwo
                                : $conversation->userOne;
                        @endphp


                        {{-- Шапка чата --}}
                        <div class="p-3 border-bottom">

                            <div class="d-flex align-items-center gap-3">

                                <img
                                    src="{{ $otherUser->avatar
                                    ? asset('storage/avatars/' . $otherUser->avatar)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) . '&background=0d6efd&color=fff&size=45'
                                }}"
                                    class="avatar-md"
                                    alt="{{ $otherUser->name }}"
                                >

                                <div>

                                    <a
                                        href="{{--{{ route('profile.show', $otherUser) }}--}}"
                                        class="fw-semibold text-dark text-decoration-none"
                                    >
                                        {{ $otherUser->name }}
                                    </a>

                                    <div class="small text-success">
                                        <span class="online-dot-static"></span>
                                        В сети
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Сообщения --}}
                        <div
                            class="chat-messages flex-grow-1 p-4"
                            id="messages"
                        >

                            @forelse($conversation->messages as $message)

                                <div
                                    class="d-flex mb-3
                                    {{ $message->user_id === auth()->id()
                                        ? 'justify-content-end'
                                        : 'justify-content-start' }}"
                                >

                                    <div
                                        class="message-bubble
                                    {{ $message->user_id === auth()->id()
                                        ? 'message-own'
                                        : 'message-other' }}"
                                    >

                                        <div>
                                            {!! $message->content !!}
                                        </div>

                                        <div class="message-time">
                                            {{ $message->created_at->format('H:i') }}

                                            @if($message->sender_id === auth()->id())
                                                <i class="bi bi-check2-all ms-1"></i>
                                            @endif
                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="h-100 d-flex align-items-center justify-content-center">

                                    <div class="text-center text-secondary">

                                        <i class="bi bi-chat-dots fs-1 d-block mb-3"></i>

                                        <div class="fw-semibold">
                                            Начните общение
                                        </div>

                                        <div class="small">
                                            Напишите первое сообщение
                                        </div>

                                    </div>

                                </div>

                            @endforelse

                        </div>


                        {{-- Поле отправки --}}
                        <div class="p-3 border-top">

                                <div class="input-group">

                                <textarea
                                    wire:model="content"
                                    name="content"
                                    class="form-control"
                                    rows="2"
                                    placeholder="Напишите сообщение..."
                                    style="resize: none;"
                                ></textarea>

                                    <button
                                        wire:click="store()"
                                        class="btn btn-primary px-4"
                                        type="submit"
                                    >
                                        <i class="bi bi-send"></i>
                                    </button>

                                </div>

                        </div>

                    @else

                        {{-- Нет выбранного диалога --}}
                        <div class="flex-grow-1 d-flex align-items-center justify-content-center">

                            <div class="text-center text-secondary">

                                <i class="bi bi-chat-square-text fs-1 d-block mb-3"></i>

                                <h5 class="fw-semibold">
                                    Личные сообщения
                                </h5>

                                <p class="small mb-0">
                                    Выберите диалог слева
                                </p>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>
@script
<script>
    Echo.private(`user.{{ auth()->id() }}`)
        .listen('.MessageSent', event => {

            $wire.dispatch('message-received', {
                messageId: event.message.id,
                conversationId: event.message.conversation_id,
            });

        });
</script>
@endscript
