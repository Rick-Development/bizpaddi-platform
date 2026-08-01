<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommunicationScriptStep extends Model
{
    use HasFactory;

    protected $fillable = ['script_id', 'order', 'name', 'content', 'key_points'];

    protected $casts = [
        'key_points' => 'array',
    ];

    public function script()
    {
        return $this->belongsTo(CommunicationScript::class, 'script_id');
    }

    public function exceptions()
    {
        return $this->hasMany(CommunicationScriptException::class, 'step_id');
    }
}
