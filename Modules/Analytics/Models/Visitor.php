<?php

namespace Modules\Analytics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Analytics\Database\Factories\VisitorFactory;

class Visitor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ip_address',
        'user_agent',
        'url',
        'referer',
        'user_id',
        'visited_at',
        'country',
        'state',
        'city',
        'lga',
        'browser',
        'device',
        'platform',
        'time_spent',
        'session_id',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    // protected static function newFactory(): VisitorFactory
    // {
    //     // return VisitorFactory::new();
    // }
}
