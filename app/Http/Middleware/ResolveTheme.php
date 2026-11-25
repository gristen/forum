<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ResolveTheme
{
    /**
     * Определяет тему для текущего запроса и шарит её во вьюхи.
     *
     * Приоритет: предпросмотр админа → выбор пользователя → глобальная настройка.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $themes = config('themes', []);
        $fallback = array_key_first($themes) ?: 'default';

        $user = $request->user();
        $isAdmin = (bool) $user?->hasPermission('admin.access');
        $hasSession = $request->hasSession();

        if ($hasSession && $request->filled('theme') && $isAdmin) {
            $requested = (string) $request->query('theme');
            debugbar()->info($requested);
            if ($requested === 'off' || ! array_key_exists($requested, $themes)) {
                $request->session()->forget('theme.preview');
            } else {
                $request->session()->put('theme.preview', $requested);
            }
        }

        $preview = $hasSession ? $request->session()->get('theme.preview') : null;

        /* Предпросмотр живёт в сессии: после выхода/смены пользователя его надо снять. */
        if ($preview !== null && ! $isAdmin) {
            $request->session()->forget('theme.preview');
            $preview = null;
        }

        $theme = $preview
            ?? $user?->theme
            ?? Setting::get('forum.theme', $fallback);

        if (! array_key_exists($theme, $themes)) {
            $theme = $fallback;
        }

        View::share('theme', $theme);

        return $next($request);
    }
}
