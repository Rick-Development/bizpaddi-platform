<?php

namespace Modules\ProjectManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\Task;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'model_id' => 'required|integer',
            'model_type' => 'required|in:project,task',
            'file' => 'required|file|max:10240', // 10MB limit
            'collection' => 'nullable|string|in:attachments,vault',
        ]);

        $modelClass = $request->model_type === 'project' ? Project::class : Task::class;
        $model = $modelClass::findOrFail($request->model_id);

        $media = $model->addMediaFromRequest('file');
        
        if ($request->has('is_internal')) {
            $media->withCustomProperties(['is_internal' => true]);
        }
        
        $media->toMediaCollection($request->collection ?? 'attachments');

        return back()->with('success', 'File uploaded successfully.');
    }

    public function destroy($id)
    {
        // Spatie media deletion
        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($id);
        $media->delete();

        return back()->with('success', 'File removed.');
    }
}
