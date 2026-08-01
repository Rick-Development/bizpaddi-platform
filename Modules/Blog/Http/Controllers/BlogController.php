<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = \Modules\Blog\Models\Post::published()->with(['author', 'category', 'tags'])->latest()->paginate(9);
        return view('blog::index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('blog::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($identifier)
    {
        $post = \Modules\Blog\Models\Post::published()
            ->with(['author', 'category', 'tags'])
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->firstOrFail();

        $post->increment('views_count');
        return view('blog::show', compact('post'))->with('seoModel', $post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('blog::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
