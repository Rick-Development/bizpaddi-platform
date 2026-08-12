<?php

namespace Modules\SocialMedia\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\SocialMedia\Database\Factories\SmmTrendFactory;

class SmmTrend extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): SmmTrendFactory
    // {
    //     // return SmmTrendFactory::new();
    // }
}
