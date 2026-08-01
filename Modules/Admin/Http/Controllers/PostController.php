<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Blog\Models\Post;
use Modules\Blog\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->latest()->get();
        return view('admin::posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin::posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'featured_image' => 'nullable|image|max:5120',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
        ]);

        $validated['author_id'] = auth()->guard('admin')->id();
        
        if ($validated['status'] === 'published' && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $post = Post::create($validated);

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('posts', 'public');
            $post->update(['featured_image' => $path]);
        }

        $post->calculateReadingTime();

        return redirect()->route('admin.posts.index')
                        ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin::posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'featured_image' => 'nullable|image|max:5120',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
        ]);

        if ($validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($post->featured_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->featured_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($post->featured_image);
            }
            
            $path = $request->file('featured_image')->store('posts', 'public');
            $post->update(['featured_image' => $path]);
        }

        $post->calculateReadingTime();

        return redirect()->route('admin.posts.index')
                        ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')
                        ->with('success', 'Post deleted successfully.');
    }
}
