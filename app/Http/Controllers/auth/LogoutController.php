<?php

namespace App\Http\Controllers\auth;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout()
    {
        Auth::logout();
       return redirect()->route('home');
    }
}
