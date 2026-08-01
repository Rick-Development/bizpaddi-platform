<?php

namespace Modules\ClientPortal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\ClientPortal\Database\Factories\InvoiceFactory;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): InvoiceFactory
    // {
    //     // return InvoiceFactory::new();
    // }
}
