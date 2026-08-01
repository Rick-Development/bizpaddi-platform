<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommunicationScriptException extends Model
{
    use HasFactory;

    protected $fillable = ['step_id', 'trigger_text', 'response_content', 'action'];

    public function step()
    {
        return $this->belongsTo(CommunicationScriptStep::class, 'step_id');
    }
}
