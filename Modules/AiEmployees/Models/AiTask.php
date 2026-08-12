<?php

namespace Modules\AiEmployees\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\AiEmployees\Database\Factories\AiTaskFactory;

class AiTask extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id',
        'task',
        'status',
        'output',
    ];

    public function employee()
    {
        return $this->belongsTo(AiEmployee::class, 'employee_id');
    }
}
