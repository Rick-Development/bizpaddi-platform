<?php

namespace Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'label',
        'icon',
        'sort_order',
    ];

    protected static function newFactory()
    {
        return \Modules\Home\Database\factories\HomeStatFactory::new();
    }
}
