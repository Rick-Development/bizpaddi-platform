<?php

namespace Modules\ProjectManagement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Clients\Models\Client;
use App\Traits\BelongsToTenant;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, BelongsToTenant;

    protected $table = 'pm_projects';

    protected $appends = ['title'];

    public function getTitleAttribute()
    {
        return $this->name;
    }

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'client_id',
        'manager_id',
        'status',
        'start_date',
        'due_date',
        'completed_at',
        'budget',
        'currency_id',
        'hourly_rate',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
        'budget' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(\Modules\Accounting\Models\Currency::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class)->orderBy('order');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }

    public function teamMembers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'project_team_members', 'project_id', 'user_id')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function scopeManagedBy($query, $userId)
    {
        return $query->where('manager_id', $userId);
    }

    public function scopeManageableBy(Builder $query, User $user): Builder
    {
        if (!$user->isProjectManager()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function (Builder $query) use ($user) {
            $query->where('manager_id', $user->id)
                ->orWhereNull('manager_id')
                ->orWhereHas('teamMembers', function (Builder $query) use ($user) {
                    $query->where('users.id', $user->id)
                        ->where('project_team_members.role', 'lead');
                });
        });
    }

    public function canBeManagedBy(?User $user): bool
    {
        if (!$user || !$user->isProjectManager()) {
            return false;
        }

        if ((int) $this->manager_id === (int) $user->id) {
            return true;
        }

        if (is_null($this->manager_id)) {
            return true;
        }

        return $this->teamMembers()
            ->where('users.id', $user->id)
            ->wherePivot('role', 'lead')
            ->exists();
    }
}
