<?php

namespace Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeCta extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'button1_text',
        'button1_link',
        'button2_text',
        'button2_link',
        'is_active',
    ];

    protected static function newFactory()
    {
        return \Modules\Home\Database\factories\HomeCtaFactory::new();
    }
}
