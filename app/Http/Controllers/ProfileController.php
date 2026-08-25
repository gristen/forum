<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index($value)
    {
        $user = User::query()
            ->when(is_numeric($value), function ($query) use ($value) {
                $query->where('id', $value);
            })
        ->when(is_string($value), function ($query) use ($value) {
            $query->where('name', $value);
        })->firstOrFail();

        return view('profile', compact('user'));
    }
}
