<?php

namespace Modules\ProjectManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'content' => 'required|string',
        'commentable_id' => 'required|integer',
        'commentable_type' => 'required|string',
        'is_internal' => 'boolean',
    ]);

    // 🔐 Get user from either 'web' or 'admin' guard
    $user = Auth::guard('web')->user() ?? Auth::guard('admin')->user();
    
    // 🛡️ Safety check
    if (!$user) {
        return response()->json(['error' => 'Unauthenticated'], 401);
        // or: return redirect()->route('login')->withErrors('Please log in');
    }

    $userType = get_class($user); // ✅ Now safe

    $typeMap = [
        'project' => \Modules\ProjectManagement\Models\Project::class,
        'task' => \Modules\ProjectManagement\Models\Task::class,
        // 👇 Add admin if commenting on admin records
        'admin' => \App\Models\Admin::class, // Adjust namespace as needed
    ];

    $commentableType = $typeMap[$request->commentable_type] ?? $request->commentable_type;

    $comment = Comment::create([
        'commentable_id' => $request->commentable_id,
        'commentable_type' => $commentableType,
        'user_id' => $user->id,
        'user_type' => $userType,
        'content' => $request->content,
        'is_internal' => $request->is_internal ?? false,
    ]);

    if ($request->hasFile('attachment')) {
        $comment->addMediaFromRequest('attachment')->toMediaCollection('comment_attachments');
    }

    return back()->with('success', 'Comment added successfully.');
}
    public function destroy(Comment $comment)
    {
        // Simple check: only author or admin can delete
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
