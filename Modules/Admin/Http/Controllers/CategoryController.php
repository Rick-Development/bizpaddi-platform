<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Blog\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->orderBy('name')->get();
        return view('admin::categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin::categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin::categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->posts()->count() > 0) {
            return redirect()->route('admin.categories.index')
                            ->with('error', 'Cannot delete category with existing posts.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category deleted successfully.');
    }
}
