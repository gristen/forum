<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ThemeController extends Controller
{
    /**
     * Личный выбор темы. Пустая строка — «как на форуме».
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'theme' => ['nullable', 'string', Rule::in(array_keys(config('themes', [])))],
        ]);

        $request->user()->update([
            'theme' => filled($data['theme'] ?? null) ? $data['theme'] : null,
        ]);

        return back();
    }
}
