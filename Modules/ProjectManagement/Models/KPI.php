<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KPI extends Model
{
    use HasFactory;

    protected $table = 'pm_kpis';

    protected $fillable = [
        'name',
        'key',
        'target',
        'unit',
        'description',
    ];
}
