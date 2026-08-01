<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, InteractsWithMedia, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'airtime_phone',
        'data_phone',
        'department',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bankAccounts()
    {
        return $this->hasMany(\Modules\TeamPortal\Models\TeamMemberBankAccount::class);
    }

    public function reports()
    {
        return $this->hasMany(\Modules\TeamPortal\Models\DailyReport::class);
    }

    public function workLogs()
    {
        return $this->hasMany(\Modules\TeamPortal\Models\WorkLog::class);
    }

    public function tasks()
    {
        return $this->hasMany(\Modules\ProjectManagement\Models\Task::class, 'assigned_to');
    }

    public function assignedTasks()
    {
        return $this->hasMany(\Modules\ProjectManagement\Models\Task::class, 'assigned_to');
    }

    public function attendance()
    {
        return $this->hasMany(\Modules\ProjectManagement\Models\Attendance::class, 'user_id');
    }

    public function assignedProjects()
    {
        return \Modules\ProjectManagement\Models\Project::whereHas('tasks', function($query) {
            $query->where('assigned_to', $this->id);
        })->get();
    }

    public function managedProjects()
    {
        return $this->hasMany(\Modules\ProjectManagement\Models\Project::class, 'manager_id');
    }

    public function isProjectManager()
    {
        return $this->role === 'pm' || 
               $this->role === 'project_manager' || 
               $this->role === 'Project Manager' ||
               $this->hasRole('project_manager') || 
               $this->hasPermissionTo('pm.manage-projects');
    }
}
