<?php

namespace Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HomeFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon',
        'sort_order',
    ];

    protected static function newFactory()
    {
        return \Modules\Home\Database\factories\HomeFeatureFactory::new();
    }
}
