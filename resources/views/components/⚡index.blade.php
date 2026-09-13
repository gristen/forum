<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Livewire\Component;

new class extends Component {

    public $categories;
    public $latestTopics;
    public $statistics;
    public $activeUsers;

    public function mount()
    {
        $this->categories = Category::query()
            ->withCount('topics')
            ->with([
                'topics' => fn($q) => $q
                    ->withCount('posts')
                    ->latest('updated_at')
                    ->with([
                        'user',
                        'posts' => fn($q) => $q
                            ->latest()
                            ->limit(1)
                            ->with('user'),
                    ]),
            ])
            ->get();


        $this->latestTopics = Topic::query()
            ->latest('updated_at')
            ->with([
                'user',
                'category',
            ])
            ->withCount('posts')
            ->limit(4)
            ->get();

        $this->statistics = [
            'topics' => Topic::count(),
            'posts' => Post::count(),
            'users' => User::count(),
            'online' => 0,
        ];

        $this->activeUsers = User::query()
            ->withCount('posts')
            ->orderByDesc('posts_count')
            ->limit(4)
            ->get();
    }
};

?>
<div>
    <div class="d-flex flex-column min-vh-100"> <!-- ============================================ --> <!-- НАВБАР -->
        <!-- ============================================ --> <!-- ============================================ -->
        <!-- ОСНОВНОЙ КОНТЕНТ --> <!-- ============================================ -->
        <main class="flex-grow-1 py-4">
            <div class="container"> <!-- ---- ПРИВЕТСТВЕННЫЙ БАННЕР ---- -->
                <div
                    class="forum-card p-4 mb-4 stripe-primary d-flex flex-wrap align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div
                            class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 54px; height: 54px; font-size: 1.6rem; color: #0d6efd;"><i
                                class="bi bi-chat-dots"></i></div>
                        <div><h5 class="fw-bold mb-0"> Добро пожаловать! </h5>
                            <p class="text-secondary-emphasis small mb-0"> Обсуждаем, делимся опытом, находим
                                друзей. </p></div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mt-2 mt-sm-0"><span class="badge-online"> <span
                                class="dot"></span> {{ number_format($statistics['online'], 0, ',', ' ') }} онлайн </span>
                        <span class="text-secondary small">|</span> <span class="text-secondary small"> Тем: <strong
                                class="text-dark"> {{ number_format($statistics['topics'], 0, ',', ' ') }} </strong> </span>
                    </div>
                </div> <!-- ============================================ --> <!-- КАТЕГОРИИ + САЙДБАР -->
                <!-- ============================================ -->
                <div class="row g-4"> <!-- ======================================== --> <!-- КАТЕГОРИИ -->
                    <!-- ======================================== -->
                    <div class="col-lg-8"> @forelse($categories as $category)
                            <div class="col-lg-12 mb-4">
                                <div class="forum-card overflow-hidden"> <!-- Заголовок категории -->
                                    <div
                                        class="category-header d-flex flex-wrap align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2"><i
                                                class="bi bi-folder2-open text-primary fs-5"></i> <h6
                                                class="fw-bold mb-0"> {{ $category->name }} </h6> <span
                                                class="badge bg-primary bg-opacity-10 text-primary fw-normal"> {{ $category->topics_count }} тем </span>
                                        </div>
                                        <a href="#"
                                           class="text-primary text-decoration-none small fw-medium"> Все темы <i
                                                class="bi bi-arrow-right ms-1"></i> </a></div> <!-- Темы категории -->

                                    <div class="category-body">
                                        @forelse($category->topics as $topic)
                                            @php $lastPost = $topic->posts->first(); @endphp
                                            <div
                                                class="sub-forum d-flex flex-wrap align-items-center justify-content-between">
                                                <!-- Информация о теме -->
                                                <div class="d-flex align-items-start gap-3"><i
                                                        class="bi bi-chat-square-text text-secondary-emphasis mt-1"> </i>
                                                    <div>
                                                        <a href="{{route('topic.show',['topic'=>$topic,'slug'=> $topic->slug])}}"
                                                            class="text-decoration-none fw-medium text-dark"> {{ $topic->title }} </a>
                                                        <div
                                                            class="d-flex flex-wrap align-items-center gap-2 small text-secondary-emphasis">
                                                            <span>
                                                                <i class="bi bi-chat me-1">

                                                                </i> {{ $topic->posts_count }} {{ trans_choice('сообщение|сообщения|сообщений', $topic->posts_count) }} </span>
                                                        </div>
                                                    </div>
                                                </div> <!-- Последний пост -->
                                                @if($lastPost)
                                                    <div
                                                        class="d-flex align-items-center gap-3 small text-secondary-emphasis">
                                                        <div class="d-flex align-items-center gap-1"><img
                                                                src="{{ $lastPost->user->avatar ? asset('storage/avatars/' . $lastPost->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($lastPost->user->name) . '&background=0d6efd&color=fff&size=28' }}"
                                                                alt="{{ $lastPost->user->name }}" class="avatar-sm">
                                                            <span> {{ $lastPost->user->name }} </span></div>
                                                        <span>
                                                            <i class="bi bi-clock me-1"></i> {{ $lastPost->created_at->diffForHumans() }} </span>
                                                    </div>
                                                @else
                                                    <span class="small text-secondary"> Пока нет сообщений </span>
                                                @endif </div>
                                        @empty
                                            <div class="sub-forum text-center text-secondary-emphasis small py-3"><i
                                                    class="bi bi-inbox me-1"></i> В этой категории пока нет тем
                                            </div>
                                        @endforelse </div>
                                </div>
                            </div>
                        @empty
                            <div class="forum-card p-4 text-center text-secondary"><i
                                    class="bi bi-inbox fs-3 d-block mb-2"></i> Категорий пока нет
                            </div>
                        @endforelse </div> <!-- ======================================== --> <!-- САЙДБАР -->
                    <!-- ======================================== -->
                    <div class="col-lg-4">
                        <div class="d-flex flex-column gap-4"> <!-- ================================= -->
                            @if(Auth::check())
                                <a class="btn btn-success" href="{{route('topics.create')}}">Создать топик</a>
                            @endif

                            <!-- ПОСЛЕДНИЕ ОБСУЖДЕНИЯ --> <!-- ================================= -->
                            <div class="forum-card p-4">
                                <div class="d-flex align-items-center justify-content-between mb-4"><h6
                                        class="fw-bold mb-0 d-flex align-items-center gap-2"><i
                                            class="bi bi-fire text-danger"></i> Последние обсуждения </h6> <a href="#"
                                                                                                              class="text-primary text-decoration-none small fw-medium">
                                        Все темы <i class="bi bi-arrow-right ms-1"></i> </a>
                                </div> @forelse($latestTopics as $topic)
                                    <div class="topic-item d-flex gap-3"> <!-- Аватар автора --> <img
                                            src="{{ $topic->user->avatar ? asset('storage/avatars/' . $topic->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($topic->user->name) . '&background=0d6efd&color=fff&size=40' }}"
                                            alt="{{ $topic->user->name }}" class="avatar-md flex-shrink-0">
                                        <div class="flex-grow-1 min-w-0"> <!-- Название --> <a
                                                href="#"
                                                class="text-decoration-none fw-medium text-dark text-break"> {{ $topic->title }} </a>
                                            <!-- Информация -->
                                            <div
                                                class="d-flex flex-wrap align-items-center gap-2 small text-secondary-emphasis mt-1">
                                                <span> <i
                                                        class="bi bi-person me-1"></i> {{ $topic->user->name }} </span>
                                                <span>•</span> <span> <i class="bi bi-folder me-1"></i> {{ $topic->category->name }} </span>
                                                <span>•</span> <span> <i class="bi bi-clock me-1"></i> {{ $topic->updated_at->diffForHumans() }} </span> @if($topic->created_at->gt(now()->subDay()))
                                                    <span class="badge bg-success bg-opacity-10 text-success fw-normal"> Новое </span>
                                                @endif </div>
                                        </div> <!-- Количество ответов -->
                                        <div class="text-end flex-shrink-0 d-none d-sm-block">
                                            <div class="small text-secondary-emphasis"> ответов</div>
                                            <strong class="fs-6"> {{ max(0, $topic->posts_count - 1) }} </strong></div>
                                    </div>
                                @empty
                                    <div class="text-center text-secondary-emphasis small py-3"><i
                                            class="bi bi-inbox me-1"></i> Пока нет обсуждений
                                    </div>
                                @endforelse </div> <!-- ================================= --> <!-- СТАТИСТИКА -->
                            <!-- ================================= -->
                            <div class="forum-card p-4"><h6 class="fw-bold mb-3 d-flex align-items-center gap-2"><i
                                        class="bi bi-graph-up-arrow text-primary"></i> Статистика </h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div
                                                class="number"> {{ number_format($statistics['topics'], 0, ',', ' ') }} </div>
                                            <div class="label"> Тем</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div
                                                class="number"> {{ number_format($statistics['posts'], 0, ',', ' ') }} </div>
                                            <div class="label"> Сообщений</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div
                                                class="number"> {{ number_format($statistics['users'], 0, ',', ' ') }} </div>
                                            <div class="label"> Пользователей</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box">
                                            <div
                                                class="number"> {{ number_format($statistics['online'], 0, ',', ' ') }} </div>
                                            <div class="label"> Онлайн</div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- ================================= --> <!-- АКТИВНЫЕ ПОЛЬЗОВАТЕЛИ -->
                            <!-- ================================= -->
                            <div class="forum-card p-4"><h6 class="fw-bold mb-3 d-flex align-items-center gap-2"><i
                                        class="bi bi-trophy text-warning"></i> Активные участники </h6>
                                <div class="d-flex flex-column gap-2"> @forelse($activeUsers as $user)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-2"><img
                                                    src="{{ $user->avatar ? asset('storage/avatars/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d6efd&color=fff&size=30' }}"
                                                    alt="{{ $user->name }}" class="avatar-sm"> <span
                                                    class="fw-medium small"> {{ $user->name }} </span></div>
                                            <span
                                                class="small text-secondary-emphasis"> {{ number_format($user->posts_count, 0, ',', ' ') }} {{ trans_choice('сообщение|сообщения|сообщений', $user->posts_count) }} </span>
                                        </div>
                                    @empty
                                        <div class="text-center text-secondary small py-2"> Пока нет пользователей</div>
                                    @endforelse </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main> <!-- ============================================ --> <!-- ФУТЕР -->
        <!-- ============================================ -->
        <footer class="bg-white border-top mt-5 py-4">
            <div class="container">
                <div class="row align-items-center g-2">
                    <div class="col-md-6 text-center text-md-start"><span class="text-secondary-emphasis small"> © 2026 Форум.hub — сделано на <strong
                                class="text-primary"> Laravel </strong> </span></div>
                    <div class="col-md-6 text-center text-md-end">
                        <div
                            class="d-flex flex-wrap justify-content-center justify-content-md-end gap-3 align-items-center">
                            <a href="#" class="footer-link small"> Правила </a> <a href="#" class="footer-link small">
                                Контакты </a> <span class="text-secondary-emphasis small"> | </span> <a href="#"
                                                                                                        class="footer-link">
                                <i class="bi bi-github"></i> </a> <a href="#" class="footer-link"> <i
                                    class="bi bi-telegram"></i> </a> <a href="#" class="footer-link"> <i
                                    class="bi bi-vk"></i> </a></div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
