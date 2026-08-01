<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommunicationChannel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scripts()
    {
        return $this->hasMany(CommunicationScript::class, 'channel_id');
    }
}
