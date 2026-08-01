<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommunicationScript extends Model
{
    use HasFactory;

    protected $fillable = ['channel_id', 'title', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function channel()
    {
        return $this->belongsTo(CommunicationChannel::class, 'channel_id');
    }

    public function steps()
    {
        return $this->hasMany(CommunicationScriptStep::class, 'script_id')->orderBy('order');
    }
}
