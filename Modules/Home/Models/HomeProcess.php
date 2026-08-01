<?php

namespace Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'step_number',
        'title',
        'description',
        'icon',
    ];

    protected static function newFactory()
    {
        return \Modules\Home\Database\factories\HomeProcessFactory::new();
    }
}
