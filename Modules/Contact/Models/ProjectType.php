<?php

namespace Modules\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Contact\Database\Factories\ProjectTypeFactory;

class ProjectType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'sort_order', 'is_active'];

    // protected static function newFactory(): ProjectTypeFactory
    // {
    //     // return ProjectTypeFactory::new();
    // }
}
