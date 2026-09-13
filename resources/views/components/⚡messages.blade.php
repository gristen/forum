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


    /*
    |--------------------------------------------------------------------------
    | MOUNT
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->loadConversations();
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD CONVERSATIONS
    |--------------------------------------------------------------------------
    */

    public function loadConversations(): void
    {
        $this->conversations = auth()->user()
            ->conversations()
            ->wherePivotNull('deleted_at')
            ->with([
                'userOne',
                'userTwo',
                'messages' => fn ($query) => $query
                    ->latest()
                    ->limit(1),
            ])
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | OPEN CONVERSATION
    |--------------------------------------------------------------------------
    */

    public function openConversation(int $conversationId): void
    {
        $this->conversation = Conversation::query()
            ->with([
                'userOne',
                'userTwo',
                'messages.user',
            ])
            ->findOrFail($conversationId);
    }


    /*
    |--------------------------------------------------------------------------
    | START CONVERSATION
    |--------------------------------------------------------------------------
    */

    #[On('user-selected')]
    public function startConversation(int $userId): void
    {
        $currentUserId = auth()->id();

        [$userOne, $userTwo] = collect([
            $currentUserId,
            $userId,
        ])->sort()->values();


        $this->conversation = Conversation::query()
            ->firstOrCreate([
                'user_one_id' => $userOne,
                'user_two_id' => $userTwo,
            ]);


        /*
        |--------------------------------------------------------------------------
        | Возвращаем обоим пользователям доступ к диалогу
        |--------------------------------------------------------------------------
        */

        $this->conversation->users()->sync([
            $userOne => [
                'deleted_at' => null,
            ],

            $userTwo => [
                'deleted_at' => null,
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Загружаем пользователей и сообщения
        |--------------------------------------------------------------------------
        */

        $this->conversation->load([
            'userOne',
            'userTwo',
            'messages.user',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Обновляем список диалогов слева
        |--------------------------------------------------------------------------
        */

        $this->loadConversations();
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE CONVERSATION
    |--------------------------------------------------------------------------
    */

    public function deleteConversation(int $conversationId): void
    {
        $conversation = Conversation::query()
            ->findOrFail($conversationId);


        $conversation->users()->updateExistingPivot(
            auth()->id(),
            [
                'deleted_at' => now(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Если удалили текущий открытый чат —
        | закрываем его
        |--------------------------------------------------------------------------
        */

        if ($this->conversation?->id === $conversationId) {
            $this->conversation = null;
        }


        $this->loadConversations();
    }


    /*
    |--------------------------------------------------------------------------
    | REALTIME MESSAGE
    |--------------------------------------------------------------------------
    */

    #[On('message-received')]
    public function messageReceived(
        int $messageId,
        int $conversationId
    ): void {

        if ($this->conversation?->id !== $conversationId) {
            return;
        }


        $this->conversation->load([
            'userOne',
            'userTwo',
            'messages.user',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | STORE MESSAGE
    |--------------------------------------------------------------------------
    */

    public function store(): void
    {
        $this->validate([
            'content' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);


        if (!$this->conversation) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Создаём сообщение
        |--------------------------------------------------------------------------
        */

        $message = $this->conversation
            ->messages()
            ->create([
                'user_id' => auth()->id(),
                'content' => $this->content,
            ]);


        /*
        |--------------------------------------------------------------------------
        | Очищаем textarea
        |--------------------------------------------------------------------------
        */

        $this->content = null;


        /*
        |--------------------------------------------------------------------------
        | Отправляем WebSocket событие
        |--------------------------------------------------------------------------
        */

        broadcast(new MessageSent($message));


        /*
        |--------------------------------------------------------------------------
        | Сразу обновляем сообщения у отправителя
        |--------------------------------------------------------------------------
        */

        $this->conversation->load([
            'userOne',
            'userTwo',
            'messages.user',
        ]);
    }

};

?>


<div class="forum-messages-page">

    <div class="container py-4">


        {{-- =====================================================
             BREADCRUMB
        ====================================================== --}}

        <nav class="forum-breadcrumb mb-3">

            <ol class="breadcrumb mb-0">

                <li class="breadcrumb-item">

                    <a href="{{ route('home') }}">
                        Форум
                    </a>

                </li>

                <li class="breadcrumb-item active">
                    Личные сообщения
                </li>

            </ol>

        </nav>



        {{-- =====================================================
             MESSAGES CARD
        ====================================================== --}}

        <div class="forum-chat-card">

            <div
                class="row g-0"
                style="height: 680px;"
            >


                {{-- =================================================
                     LEFT — CONVERSATIONS
                ================================================== --}}

                <div class="col-lg-4 forum-conversations-column">


                    {{-- =================================================
                         HEADER
                    ================================================== --}}

                    <div class="forum-conversations-header">

                        <div>

                            <h5 class="forum-chat-title">
                                Сообщения
                            </h5>

                            <div class="forum-chat-subtitle">
                                Ваши личные переписки
                            </div>

                        </div>


                        <a
                            href="{{ route('users.index') }}"
                            class="forum-new-message-button"
                            title="Новое сообщение"
                        >

                            <i class="bi bi-pencil-square"></i>

                        </a>

                    </div>


                    {{-- =================================================
                         SEARCH
                    ================================================== --}}

                    <div class="forum-chat-search">

                        <livewire:search
                            model="\App\Models\User"
                            :search-columns="['name', 'email']"
                            :display-field="['name', 'email']"
                        />

                    </div>


                    {{-- =================================================
                         CONVERSATION LIST
                    ================================================== --}}

                    <div class="conversation-list">

                        @forelse($conversations as $item)

                            @php

                                $otherUser = $item->user_one_id === auth()->id()
                                    ? $item->userTwo
                                    : $item->userOne;

                                $lastMessage = $item->messages->first();

                            @endphp


                            <button
                                type="button"
                                wire:click="openConversation({{ $item->id }})"
                                class="
                                    conversation-item
                                    {{ $conversation?->id === $item->id ? 'active' : '' }}
                                "
                            >


                                {{-- Avatar --}}

                                <div class="conversation-avatar-wrapper">

                                    @if($otherUser->avatar)

                                        <img
                                            src="{{ asset('storage/avatars/' . $otherUser->avatar) }}"
                                            class="conversation-avatar"
                                            alt="{{ $otherUser->name }}"
                                        >

                                    @else

                                        <div class="conversation-avatar conversation-avatar-placeholder">

                                            {{ mb_strtoupper(mb_substr($otherUser->name, 0, 1)) }}

                                        </div>

                                    @endif


                                    <span class="conversation-online-dot"></span>

                                </div>



                                {{-- Info --}}

                                <div class="conversation-info">

                                    <div class="conversation-top">

                                        <span class="conversation-name">

                                            {{ $otherUser->name }}

                                        </span>


                                        @if($lastMessage)

                                            <span class="conversation-time">

                                                {{ $lastMessage->created_at->diffForHumans(null, true) }}

                                            </span>

                                        @endif

                                    </div>


                                    @if($lastMessage)

                                        <div class="conversation-preview">

                                            @if($lastMessage->user_id === auth()->id())
                                                Вы:
                                            @endif

                                            {{ strip_tags($lastMessage->content) }}

                                        </div>

                                    @else

                                        <div class="conversation-preview">
                                            Нет сообщений
                                        </div>

                                    @endif

                                </div>

                            </button>


                        @empty

                            <div class="conversation-empty">

                                <i class="bi bi-chat-square-text"></i>

                                <div class="conversation-empty-title">
                                    Пока нет сообщений
                                </div>

                                <div class="conversation-empty-text">
                                    Начните переписку с пользователем
                                </div>

                            </div>

                        @endforelse

                    </div>

                </div>



                {{-- =================================================
                     RIGHT — CURRENT CHAT
                ================================================== --}}

                <div class="col-lg-8 forum-current-chat">

                    @if($conversation)

                        @php

                            $otherUser = $conversation->user_one_id === auth()->id()
                                ? $conversation->userTwo
                                : $conversation->userOne;

                        @endphp


                        {{-- =================================================
                             CHAT HEADER
                        ================================================== --}}

                        <div class="forum-current-chat-header">


                            <div class="d-flex align-items-center gap-3">


                                {{-- Avatar --}}

                                @if($otherUser->avatar)

                                    <img
                                        src="{{ asset('storage/avatars/' . $otherUser->avatar) }}"
                                        class="chat-user-avatar"
                                        alt="{{ $otherUser->name }}"
                                    >

                                @else

                                    <div class="chat-user-avatar chat-user-avatar-placeholder">

                                        {{ mb_strtoupper(mb_substr($otherUser->name, 0, 1)) }}

                                    </div>

                                @endif


                                {{-- User info --}}

                                <div>

                                    <div class="chat-user-name">
                                        {{ $otherUser->name }}
                                    </div>

                                    <div class="chat-user-status">

                                        <span></span>

                                        В сети

                                    </div>

                                </div>

                            </div>


                            {{-- Chat actions --}}

                            <div class="chat-header-actions">

                                <button
                                    type="button"
                                    class="chat-header-button"
                                    title="Удалить диалог"
                                    wire:click="deleteConversation({{ $conversation->id }})"
                                >

                                    <i class="bi bi-trash3"></i>

                                </button>

                            </div>

                        </div>



                        {{-- =================================================
                             MESSAGES
                        ================================================== --}}

                        <div
                            class="chat-messages"
                            id="messages"
                        >

                            @forelse($conversation->messages as $message)

                                <div
                                    class="
                                        chat-message-row
                                        {{ $message->user_id === auth()->id()
                                            ? 'own'
                                            : 'other' }}
                                    "
                                >

                                    <div
                                        class="
                                            message-bubble
                                            {{ $message->user_id === auth()->id()
                                                ? 'message-own'
                                                : 'message-other' }}
                                        "
                                    >

                                        <div class="message-text">

                                            {!! $message->content !!}

                                        </div>


                                        <div class="message-time">

                                            {{ $message->created_at->format('H:i') }}


                                            @if($message->user_id === auth()->id())

                                                <i class="bi bi-check2-all"></i>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <div class="chat-empty">

                                    <div class="chat-empty-icon">

                                        <i class="bi bi-chat-dots"></i>

                                    </div>

                                    <div class="chat-empty-title">
                                        Начните общение
                                    </div>

                                    <div class="chat-empty-text">
                                        Напишите первое сообщение
                                    </div>

                                </div>

                            @endforelse

                        </div>



                        {{-- =================================================
                             SEND MESSAGE
                        ================================================== --}}

                        <div class="chat-input-wrapper">

                            <div class="chat-input-group">


                                <textarea
                                    wire:model="content"
                                    class="chat-input"
                                    rows="2"
                                    placeholder="Напишите сообщение..."
                                ></textarea>


                                <button
                                    type="button"
                                    wire:click="store"
                                    class="chat-send-button"
                                >

                                    <i class="bi bi-send-fill"></i>

                                </button>

                            </div>


                            @error('content')

                            <div class="chat-input-error">
                                {{ $message }}
                            </div>

                            @enderror

                        </div>


                    @else


                        {{-- =================================================
                             NO CHAT SELECTED
                        ================================================== --}}

                        <div class="chat-no-selection">

                            <div class="chat-no-selection-icon">

                                <i class="bi bi-chat-square-text"></i>

                            </div>

                            <h5>
                                Личные сообщения
                            </h5>

                            <p>
                                Выберите диалог слева
                            </p>

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
