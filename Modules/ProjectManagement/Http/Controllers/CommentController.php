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

        // 🔐 Resolve user based on active context / guard / referer
        $guard = $request->input('guard');
        $referer = (string) $request->headers->get('referer', '');

        $user = null;

        if ($guard && Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();
        } elseif (str_contains($referer, '/admin') && Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
        } elseif (str_contains($referer, '/client') && Auth::guard('client')->check()) {
            $user = Auth::guard('client')->user();
        } elseif (str_contains($referer, '/team') && Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        } else {
            // Fallback: check admin first, then web, then client
            $user = Auth::guard('admin')->user()
                ?? Auth::guard('web')->user()
                ?? Auth::guard('client')->user();
        }
        
        // 🛡️ Safety check
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $userType = get_class($user);

        $typeMap = [
            'project' => \Modules\ProjectManagement\Models\Project::class,
            'task' => \Modules\ProjectManagement\Models\Task::class,
            'admin' => \App\Models\Admin::class,
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

        // Dispatch CommentPosted event for real-time WebSocket updates
        broadcast(new \Modules\ProjectManagement\Events\CommentPosted($comment))->toOthers();

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $comment->addMedia($file)->toMediaCollection('comment_attachments');
            }
        } elseif ($request->hasFile('attachment')) {
            $comment->addMediaFromRequest('attachment')->toMediaCollection('comment_attachments');
        }

        return back()->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        $isAdmin = Auth::guard('admin')->check();
        $isAuthor = false;

        $currentUser = Auth::guard('admin')->user() 
            ?? Auth::guard('web')->user() 
            ?? Auth::guard('client')->user();

        if ($currentUser && $comment->user_id == $currentUser->id && $comment->user_type === get_class($currentUser)) {
            $isAuthor = true;
        }

        if (!$isAdmin && !$isAuthor) {
            abort(403, 'Unauthorized to delete this comment.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
