<?php

namespace Modules\AiEmployees\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\AiEmployees\Database\Factories\AiEmployeeFactory;
use App\Traits\BelongsToTenant;

class AiEmployee extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'role',
        'description',
        'system_prompt',
        'ai_model_id',
        'is_active',
        'parent_id',
        'is_sub_agent',
    ];

    // protected static function newFactory(): AiEmployeeFactory
    // {
    //     // return AiEmployeeFactory::new();
    // }

    public function parent()
    {
        return $this->belongsTo(AiEmployee::class, 'parent_id');
    }

    public function subAgents()
    {
        return $this->hasMany(AiEmployee::class, 'parent_id');
    }
}
