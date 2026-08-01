<?php

namespace Modules\AI\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\AI\Models\AIModel;
use Illuminate\Http\Request;

class AIModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = AIModel::orderBy('provider')->orderBy('name')->get();
        return view('ai::admin.models.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ai::admin.models.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:openai,anthropic,gemini',
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        AIModel::create($request->all());

        return redirect()->route('admin.ai.models.index')->with('success', 'Model created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AIModel $model)
    {
        return view('ai::admin.models.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AIModel $model)
    {
        $request->validate([
            'provider' => 'required|in:openai,anthropic,gemini',
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $model->update($request->all());

        return redirect()->route('admin.ai.models.index')->with('success', 'Model updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AIModel $model)
    {
        $model->delete();
        return redirect()->route('admin.ai.models.index')->with('success', 'Model deleted successfully.');
    }
}
