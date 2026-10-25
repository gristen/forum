<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminCategoryController extends Controller
{
    /**
     * Иконка по умолчанию для категории.
     */
    private const DEFAULT_ICON = 'bi-folder-fill';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query()
            ->with('parent')
            ->withCount('topics')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create', [
            'parents' => $this->parentsTree(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validateCategory($request);

        $data['slug'] = $this->makeSlug($data['name']);
        $data['icon'] = $data['icon'] ?: self::DEFAULT_ICON;
        $data['order'] = $data['order'] ?? 0;

        Category::query()->create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Категория «{$data['name']}» создана.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', [
            'category' => $category,
            'parents' => $this->parentsTree($category),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $this->validateCategory($request, $category);

        if (! empty($data['parent_id'])) {
            $this->guardAgainstCycles($category, (int) $data['parent_id']);
        }

        if ($data['name'] !== $category->name) {
            $data['slug'] = $this->makeSlug($data['name'], $category->id);
        }

        $data['icon'] = $data['icon'] ?: self::DEFAULT_ICON;
        $data['order'] = $data['order'] ?? 0;

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Категория «{$category->name}» обновлена.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->topics()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', "Нельзя удалить «{$category->name}»: в категории есть темы.");
        }

        if ($category->children()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', "Нельзя удалить «{$category->name}»: у категории есть подкатегории.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Категория «{$name}» удалена.");
    }

    /**
     * Правила валидации формы категории.
     */
    private function validateCategory(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')->ignore($category?->id),
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'string', 'max:60', 'regex:/^bi-[a-z0-9-]+$/'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [
            'name.required' => 'Укажите название категории.',
            'name.max' => 'Название не должно быть длиннее 100 символов.',
            'name.unique' => 'Категория с таким названием уже существует.',
            'description.max' => 'Описание не должно быть длиннее 500 символов.',
            'icon.regex' => 'Иконка должна быть классом Bootstrap Icons, например bi-folder-fill.',
            'parent_id.exists' => 'Выбранная родительская категория не найдена.',
            'order.integer' => 'Порядок должен быть числом.',
            'order.min' => 'Порядок не может быть отрицательным.',
        ]);
    }

    /**
     * Список категорий, которые можно выбрать родителем.
     */
    private function parentsTree(?Category $category = null)
    {
        $categories = Category::query()
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        if (! $category) {
            return $categories;
        }

        return $categories
            ->reject(fn (Category $candidate) => $this->isSameOrAncestorOfSelf($candidate, $category))
            ->values();
    }

    /**
     * Проверяет, является ли категория самой собой или своим потомком.
     */
    private function isSameOrAncestorOfSelf(Category $candidate, Category $category): bool
    {
        $current = $candidate;
        $guard = 0;

        while ($current && $guard++ < 50) {
            if ($current->id === $category->id) {
                return true;
            }

            $current = $current->parent;
        }

        return false;
    }

    /**
     * Запрещает делать категорию родителем самой себе / своего потомка.
     */
    private function guardAgainstCycles(Category $category, int $parentId): void
    {
        if ($parentId === $category->id) {
            throw ValidationException::withMessages([
                'parent_id' => 'Категория не может быть родителем сама себе.',
            ]);
        }

        $parent = Category::query()->find($parentId);
        $guard = 0;

        while ($parent && $guard++ < 50) {
            if ($parent->id === $category->id) {
                throw ValidationException::withMessages([
                    'parent_id' => 'Нельзя выбрать подкатегорию в качестве родителя.',
                ]);
            }

            $parent = $parent->parent;
        }
    }

    /**
     * Генерирует уникальный slug на основе названия.
     */
    private function makeSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name, '-', 'ru') ?: 'category';
        $slug = $base;
        $counter = 2;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return Category::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists();
    }

    public function test()
    {
       
    }
}
