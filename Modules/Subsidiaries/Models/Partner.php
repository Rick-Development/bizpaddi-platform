<?php

namespace Modules\Subsidiaries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Subsidiaries\Database\Factories\PartnerFactory;

class Partner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): PartnerFactory
    // {
    //     // return PartnerFactory::new();
    // }
}
