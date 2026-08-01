<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Performance extends Model
{
    use HasFactory;

    protected $table = 'pm_team_performance';

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'score',
        'tasks_completed',
        'tasks_late',
        'tasks_early',
        'bonus_points',
        'penalty_points',
        'kpi_data',
    ];

    protected $casts = [
        'kpi_data' => 'array',
        'score' => 'decimal:2',
        'bonus_points' => 'decimal:2',
        'penalty_points' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
