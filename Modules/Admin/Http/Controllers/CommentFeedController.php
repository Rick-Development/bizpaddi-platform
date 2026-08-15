<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Comment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CommentFeedController extends Controller
{
    /**
     * Display the comment feed for Admin.
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        // Mark current time as read in session
        session(['last_viewed_admin_comments_at' => now()->toDateTimeString()]);

        $comments = Comment::with(['user', 'commentable'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin::comments.index', compact('comments'));
    }

    /**
     * Get unread count for sidebar (comments posted since last view, or last 24h).
     */
    public function getUnreadCount(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $query = Comment::query();

        // Exclude admin's own comments
        $query->where(function ($q) use ($admin) {
            $q->where('user_id', '!=', $admin->id)
              ->orWhere('user_type', '!=', get_class($admin));
        });

        // Filter since last view or last 24 hours
        $lastViewedAt = session('last_viewed_admin_comments_at') 
            ? Carbon::parse(session('last_viewed_admin_comments_at')) 
            : Carbon::now()->subDay();

        $count = $query->where('created_at', '>', $lastViewedAt)->count();

        return response()->json([
            'unread_count' => $count
        ]);
    }
}
