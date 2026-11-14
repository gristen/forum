<?php

namespace App\Http\Controllers\auth;


use App\Http\Controllers\Controller;
use App\Services\ActivityService;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout()
    {
        /* логируем ДО выхода — иначе auth() уже пуст */
        if ($user = auth()->user()) {
            app(ActivityService::class)->log('logout', $user);
        }

        Auth::logout();

        return redirect()->route('home');
    }
}
