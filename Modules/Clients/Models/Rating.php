<?php

namespace Modules\Clients\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Clients\Database\Factories\RatingFactory;

class Rating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): RatingFactory
    // {
    //     // return RatingFactory::new();
    // }
}
