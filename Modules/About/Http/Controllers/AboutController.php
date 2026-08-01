<?php

namespace Modules\About\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaders = \Modules\About\Models\Leader::orderBy('order')->get();
        $timelines = \Modules\About\Models\Timeline::orderBy('year', 'desc')->get();
        $awards = \Modules\About\Models\Award::orderBy('year', 'desc')->get();

        return view('about::index', compact('leaders', 'timelines', 'awards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('about::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('about::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('about::edit');
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
