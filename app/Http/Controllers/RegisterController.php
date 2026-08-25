<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:2|max:32',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role_id'] = '3';
        $data['about'] = "test about text";

        $user = User::create($data);
        Auth::login($user);

        return redirect('/', 301, ['message' => 'reg success']);

    }
}
