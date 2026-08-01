<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;
use Modules\ProjectManagement\Models\ProjectConversation;
use Modules\ProjectManagement\Models\ProjectMessage;

class ClientProjectChatController extends Controller
{
    public function index(Project $project)
    {
        // Security: Ensure project belongs to auth client
        if ($project->client_id !== auth('client')->id()) {
            abort(403, 'Unauthorized access to project chat.');
        }

        $conversation = ProjectConversation::firstOrCreate(
            [
                'project_id' => $project->id,
                'type' => 'client_team'
            ]
        );

        $messages = $conversation->messages()
            ->with(['sender']) // Polymorphic load
            ->latest()
            ->paginate(50);

        return view('clientportal::projects.chat', compact('project', 'conversation', 'messages'));
    }

    public function store(Request $request, Project $project)
    {
        if ($project->client_id !== auth('client')->id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $conversation = ProjectConversation::firstOrCreate(
            [
                'project_id' => $project->id,
                'type' => 'client_team'
            ]
        );

        ProjectMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth('client')->id(),
            'sender_type' => get_class(auth('client')->user()), // Correctly get the Client model class
            'content' => $request->content,
        ]);

        return back()->with('success', 'Message sent.');
    }
}
