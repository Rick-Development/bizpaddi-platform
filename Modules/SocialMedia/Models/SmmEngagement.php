<?php

namespace Modules\SocialMedia\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\SocialMedia\Database\Factories\SmmEngagementFactory;

class SmmEngagement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): SmmEngagementFactory
    // {
    //     // return SmmEngagementFactory::new();
    // }
}
