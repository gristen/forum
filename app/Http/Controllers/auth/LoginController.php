<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials, true)) {
            if (auth()->user()->isBanned()) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['message' => 'Ваш аккаунт заблокирован.']);
            }

            app(ActivityService::class)->log('login', auth()->user());
            return redirect()->intended(route('home'));
        }
        return back()->withErrors(['message' => 'login failed']);
    }
}
