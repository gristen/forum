<?php

namespace App\Http\Controllers\forum;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index');
    }

    public function show(Category $category, ?string $slug = null): View|RedirectResponse
    {
        if ($slug !== null && $slug !== $category->slug) {
            return redirect()->route('category.show', [
                'category' => $category,
                'slug' => $category->slug,
            ], 301);
        }

        $category->loadCount('topics');

        $topics = $category->topics()
            ->with('user')
            ->withCount('posts')
            ->with([
                'posts' => fn ($query) => $query
                    ->latest()
                    ->limit(1)
                    ->with('user'),
            ])
            ->orderByDesc('pinned')
            ->latest('updated_at')
            ->paginate(20);

        return view('category.show', compact('category', 'topics'));
    }
}
