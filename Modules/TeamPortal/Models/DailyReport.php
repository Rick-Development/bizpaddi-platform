<?php

namespace Modules\TeamPortal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'hours_worked',
        'tasks_completed',
        'tasks_planned',
        'challenges',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
