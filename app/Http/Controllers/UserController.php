<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = is_string($request->query('search')) ? trim($request->query('search')) : '';

        $users = User::query()
            ->search($search)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('user.index', compact('users'));
    }
}
