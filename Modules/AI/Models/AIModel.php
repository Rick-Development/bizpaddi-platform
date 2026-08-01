<?php

namespace Modules\AI\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AIModel extends Model
{
    use HasFactory;

    protected $table = 'ai_models';

    protected $fillable = [
        'provider',
        'name',
        'label',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }
}
