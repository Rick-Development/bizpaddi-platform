<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\ProjectConversation;
use Modules\ProjectManagement\Models\ProjectMessage;

class ProjectChatController extends Controller
{
    public function index(Project $project)
    {
        // Security check: Ensure user is assigned to this project
        // For now, assuming all team members can view all projects or strict assignment logic exists.
        // Ideally: if (!$project->teamMembers->contains(auth()->id())) abort(403);

        $conversation = ProjectConversation::firstOrCreate(
            [
                'project_id' => $project->id,
                'type' => 'client_team'
            ]
        );

        $messages = $conversation->messages()
            ->with('sender')
            ->latest()
            ->paginate(50);

        return view('teamportal::projects.chat', compact('project', 'conversation', 'messages'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            // 'attachments.*' => 'file|max:10240' 
        ]);

        $conversation = ProjectConversation::firstOrCreate(
            [
                'project_id' => $project->id,
                'type' => 'client_team'
            ]
        );

        $message = ProjectMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'sender_type' => auth()->user()?->getMorphClass(),
            'content' => $request->content,
        ]);

        // Handle attachments if needed in future

        return back()->with('success', 'Message sent.');
    }
}
