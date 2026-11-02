<?php

namespace Modules\AiEmployees\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\AiEmployees\Database\Factories\AiTaskFactory;
use App\Traits\BelongsToTenant;

class AiTask extends Model
{
    use BelongsToTenant;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
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
