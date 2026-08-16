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
            'file' => 'nullable|file|max:10240', // 10MB limit
            'files.*' => 'nullable|file|max:10240', // 10MB limit for multiple
            'collection' => 'nullable|string|in:attachments,vault',
        ]);

        $modelClass = $request->model_type === 'project' ? Project::class : Task::class;
        $model = $modelClass::findOrFail($request->model_id);

        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            $uploadedFiles = $request->file('files');
        } elseif ($request->hasFile('file')) {
            $uploadedFiles = [$request->file('file')];
        }

        foreach ($uploadedFiles as $uploadedFile) {
            $media = $model->addMedia($uploadedFile);
            
            if ($request->has('is_internal')) {
                $media->withCustomProperties(['is_internal' => true]);
            }
            
            // For tasks, use task_attachments, for others use requested collection or attachments
            $collection = $request->collection ?? ($request->model_type === 'task' ? 'task_attachments' : 'attachments');
            $media->toMediaCollection($collection);
        }

        return back()->with('success', 'File(s) uploaded successfully.');
    }

    public function download(Request $request, $id)
    {
        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($id);
        return response()->file($media->getPath());
    }

    public function destroy($id)
    {
        // Spatie media deletion
        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::findOrFail($id);
        $media->delete();

        return back()->with('success', 'File removed.');
    }
}
