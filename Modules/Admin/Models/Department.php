<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Department extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'name', 'description', 'is_active'];
}
