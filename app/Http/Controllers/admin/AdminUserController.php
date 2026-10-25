<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    private const PER_PAGE = 20;

    public function index(Request $request, DashboardService $dashboardService)
    {
        $search = is_string($request->query('search')) ? trim($request->query('search')) : '';
        $role = is_string($request->query('role')) ? $request->query('role') : '';
        $sort = is_string($request->query('sort')) ? $request->query('sort') : 'latest';
        debugbar()->info($request);
        $users = User::query()
            ->with('role')
            ->withCount(['topics', 'posts'])
            ->with(['bans' => fn ($query) => $query->active()])
            ->search($search)
            ->filterByRole($role)
            ->sorted($sort)
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $monthly = $dashboardService->getMonthlyRegistrations();

        $stats = [
            'total' => User::count(),
            'month' => $dashboardService->getGrowth(User::class, 'month'),
            'week' => $dashboardService->countForPeriod(User::class, 'week'),
            'today' => $dashboardService->countForPeriod(User::class, 'day'),
            'online' => Schema::hasTable('sessions')
                ? DB::table('sessions')
                    ->whereNotNull('user_id')
                    ->where('last_activity', '>=', now()->subMinutes(15)->getTimestamp())
                    ->distinct()
                    ->count('user_id')
                : 0,
        ];

        return view('admin.users.index', [
            'users' => $users,
            'roles' => Role::query()->withCount('users')->orderBy('name')->get(),
            'stats' => $stats,
            'monthly' => $monthly,
            'monthlyMax' => max(1, ...array_values($monthly)),
            'monthlyTotal' => array_sum($monthly),
            'search' => $search,
            'role' => $role,
            'sort' => $sort,
        ]);
    }

    public function show(User $user)
    {
        $topics = $user->topics()
            ->withCount('posts')
            ->latest()
            ->limit(5)
            ->get();

        $posts = $user->posts()
            ->with('topic.user')
            ->latest()
            ->limit(5)
            ->get();

        $messages = $user->sentMessages()
            ->with('conversation.userTwo')
            ->latest()
            ->limit(5)
            ->get();

        $lastActivityAt = $user->topics()->max('created_at')
            ?? $user->posts()->max('created_at')
            ?? $user->sentMessages()->max('created_at');

        $lastSession = Schema::hasTable('sessions')
            ? DB::table('sessions')
                ->where('user_id', $user->id)
                ->orderBy('last_activity', 'desc')
                ->first()
            : null;

        $stats = [
            'topics' => $user->topics()->count(),
            'posts' => $user->posts()->count(),
            'messages' => $user->sentMessages()->count(),
            'conversations' => $user->conversations()->count(),
        ];

        $activeBan = $user->bans()
            ->active()
            ->with('admin')
            ->latest()
            ->first();

        $bans = $user->bans()
            ->with('admin')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.users.show', [
            'user' => $user->load('role'),
            'topics' => $topics,
            'posts' => $posts,
            'messages' => $messages,
            'stats' => $stats,
            'lastActivityAt' => $lastActivityAt,
            'lastSession' => $lastSession,
            'activeBan' => $activeBan,
            'bans' => $bans,
        ]);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user->load('role'),
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'about' => ['nullable', 'string', 'max:255'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Укажите имя пользователя.',
            'email.required' => 'Укажите email.',
            'email.email' => 'Введите корректный email.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'about.max' => 'Поле «О себе» не должно быть длиннее 255 символов.',
            'role_id.required' => 'Выберите роль.',
            'role_id.exists' => 'Выбранная роль не найдена.',
            'password.min' => 'Пароль должен быть не короче 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Профиль пользователя обновлён.');
    }

    public function destroy(User $user)
    {
        if ($user->topics()->exists()) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', "Нельзя удалить пользователя «{$user->name}»: сначала удалите его темы.");
        }

        $name = $user->name;

        DB::transaction(function () use ($user) {
            // Снимаем ссылки в темах, где пользователь был последним ответившим.
            DB::table('topics')
                ->where('last_post_user_id', $user->id)
                ->update(['last_post_user_id' => null]);

            $user->delete();
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Пользователь «{$name}» удалён.");
    }

    public function ban(Request $request, User $user)
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'in:1,7,30,permanent'],
        ], [
            'reason.required' => 'Укажите причину бана.',
            'reason.max' => 'Причина не должна быть длиннее 255 символов.',
            'duration.required' => 'Выберите срок бана.',
            'duration.in' => 'Выбран некорректный срок бана.',
        ]);

        if ($user->isBanned()) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Пользователь уже забанен.');
        }

        $user->bans()->create([
            'banned_by' => auth()->id(),
            'reason' => $data['reason'],
            'expires_at' => $data['duration'] === 'permanent'
                ? null
                : now()->addDays((int) $data['duration']),
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Пользователь забанен.');
    }

    public function unban(User $user)
    {
        if (! $user->isBanned()) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Пользователь не забанен.');
        }

        $user->bans()
            ->active()
            ->update(['lifted_at' => now()]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Пользователь разбанен.');
    }
}
