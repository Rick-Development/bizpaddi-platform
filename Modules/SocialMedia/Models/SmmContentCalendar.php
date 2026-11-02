<?php

namespace Modules\SocialMedia\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\SocialMedia\Database\Factories\SmmContentCalendarFactory;
use App\Traits\BelongsToTenant;

class SmmContentCalendar extends Model
{
    use BelongsToTenant;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',];

    // protected static function newFactory(): SmmContentCalendarFactory
    // {
    //     // return SmmContentCalendarFactory::new();
    // }
}
