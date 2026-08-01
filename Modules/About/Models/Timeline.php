<?php
namespace Modules\About\Models;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model {
    protected $fillable = ['year', 'title', 'description', 'order'];
}
