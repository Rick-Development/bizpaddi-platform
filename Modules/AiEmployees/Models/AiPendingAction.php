<?php

namespace Modules\AiEmployees\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\AiEmployees\Database\Factories\AiPendingActionFactory;

class AiPendingAction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ai_employee_id',
        'tool_name',
        'tool_arguments',
        'status',
        'result',
    ];

    protected $casts = [
        'tool_arguments' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(AiEmployee::class, 'ai_employee_id');
    }

    // protected static function newFactory(): AiPendingActionFactory
    // {
    //     // return AiPendingActionFactory::new();
    // }
}
