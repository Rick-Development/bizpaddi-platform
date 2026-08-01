<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];
}
