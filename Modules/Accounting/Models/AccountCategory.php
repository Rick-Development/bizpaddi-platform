<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Accounting\Database\Factories\AccountCategoryFactory;

class AccountCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
    ];

    // protected static function newFactory(): AccountCategoryFactory
    // {
    //     // return AccountCategoryFactory::new();
    // }
}
