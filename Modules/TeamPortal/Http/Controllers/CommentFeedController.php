<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Comment;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\Project;
use Carbon\Carbon;

class CommentFeedController extends Controller
{
    /**
     * Display the comment feed.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Mark current time as read in session
        session(['last_viewed_comments_at' => now()->toDateTimeString()]);

        $comments = $this->getCommentsQuery($user)
            ->with(['user', 'commentable'])
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('teamportal::comments.index', compact('comments'));
    }

    /**
     * Get unread count for sidebar (comments posted since last view, or last 24h).
     */
    public function getUnreadCount(Request $request)
    {
        $user = auth()->user();
        $query = $this->getCommentsQuery($user);

        // Exclude user's own comments
        $query->where(function ($q) use ($user) {
            $q->where('user_id', '!=', $user->id)
              ->orWhere('user_type', '!=', get_class($user));
        });

        // Filter since last view or last 24 hours
        $lastViewedAt = session('last_viewed_comments_at')
            ? Carbon::parse(session('last_viewed_comments_at'))
            : Carbon::now()->subDay();

        $count = $query->where('created_at', '>', $lastViewedAt)->count();

        return response()->json([
            'unread_count' => $count
        ]);
    }

    /**
     * Build the query according to user role permissions.
     */
    protected function getCommentsQuery($user)
    {
        $query = Comment::query();

        // Super Admin / Admin role sees all comments across all tasks & projects
        if ($user->role === 'super_admin' || $user->role === 'admin' || $user->hasRole('admin') || $user->hasRole('super_admin')) {
            return $query;
        }

        if ($user->isProjectManager()) {
            // PMs: comments on tasks/projects they manage or comments posted by them
            $query->where(function ($q) use ($user) {
                // Task comments on projects managed by this PM
                $q->where(function ($subQ) use ($user) {
                    $subQ->where('commentable_type', Task::class)
                         ->whereIn('commentable_id', function ($taskQuery) use ($user) {
                             $taskQuery->select('id')
                                       ->from('pm_tasks')
                                       ->whereIn('project_id', function ($projQuery) use ($user) {
                                           $projQuery->select('id')
                                                     ->from('pm_projects')
                                                     ->where('manager_id', $user->id);
                                       });
                         });
                });
                // Project comments on projects managed by this PM
                $q->orWhere(function ($subQ) use ($user) {
                    $subQ->where('commentable_type', Project::class)
                         ->whereIn('commentable_id', function ($projQuery) use ($user) {
                             $projQuery->select('id')
                                       ->from('pm_projects')
                                       ->where('manager_id', $user->id);
                         });
                });
                // Comments posted directly by this user
                $q->orWhere(function ($subQ) use ($user) {
                    $subQ->where('user_id', $user->id)
                         ->where('user_type', get_class($user));
                });
            });
        } else {
            // Developers / BDMs: tasks directly assigned to them, projects they belong to, or comments posted by them
            $query->where(function ($q) use ($user) {
                // Task comments on tasks assigned directly to developer
                $q->where(function ($subQ) use ($user) {
                    $subQ->where('commentable_type', Task::class)
                         ->whereIn('commentable_id', function ($taskQuery) use ($user) {
                             $taskQuery->select('id')
                                       ->from('pm_tasks')
                                       ->where('assigned_to', $user->id);
                         });
                });

                // Task comments on projects where developer is a team member
                $q->orWhere(function ($subQ) use ($user) {
                    $subQ->where('commentable_type', Task::class)
                         ->whereIn('commentable_id', function ($taskQuery) use ($user) {
                             $taskQuery->select('id')
                                       ->from('pm_tasks')
                                       ->whereIn('project_id', function ($memberProj) use ($user) {
                                           $memberProj->select('project_id')
                                                      ->from('project_team_members')
                                                      ->where('user_id', $user->id);
                                       });
                         });
                });

                // Comments posted directly by this user
                $q->orWhere(function ($subQ) use ($user) {
                    $subQ->where('user_id', $user->id)
                         ->where('user_type', get_class($user));
                });
            });
        }

        return $query;
    }

}
