<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Milestone extends Model
{
    use HasFactory;

    protected $table = 'pm_milestones';

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'due_date',
        'status',
        'order',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getProgressAttribute()
    {
        $total = $this->tasks()->count();
        if ($total === 0) return 0;
        
        $completed = $this->tasks()->where('status', 'done')->count();
        return round(($completed / $total) * 100);
    }
}
