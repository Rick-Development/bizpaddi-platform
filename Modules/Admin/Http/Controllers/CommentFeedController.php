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
     * Resolve the active tenant ID for the admin user.
     */
    protected function tenantId(): ?int
    {
        $admin = Auth::guard('admin')->user();
        if ($admin && method_exists($admin, 'isSuperAdmin') && $admin->isSuperAdmin()) {
            return null; // Platform Super Admin
        }
        return ($admin && !empty($admin->tenant_id)) ? (int) $admin->tenant_id : null;
    }

    /**
     * Display the comment feed for Admin.
     */
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $tenantId = $this->tenantId();
        
        // Mark current time as read in session
        session(['last_viewed_admin_comments_at' => now()->toDateTimeString()]);

        $query = Comment::with(['user', 'commentable']);

        if ($tenantId) {
            $query->where('pm_comments.tenant_id', $tenantId);
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(30);

        return view('admin::comments.index', compact('comments'));
    }

    /**
     * Get unread count for sidebar (comments posted since last view, or last 24h).
     */
    public function getUnreadCount(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $tenantId = $this->tenantId();
        
        $query = Comment::query();

        if ($tenantId) {
            $query->where('pm_comments.tenant_id', $tenantId);
        }

        // Exclude admin's own comments
        if ($admin) {
            $query->where(function ($q) use ($admin) {
                $q->where('user_id', '!=', $admin->id)
                  ->orWhere('user_type', '!=', get_class($admin));
            });
        }

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
