<?php

namespace Modules\TeamPortal\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TeamMemberBankAccount extends Model
{
    protected $fillable = [
        'user_id',
        'bank_code',
        'account_number',
        'account_name',
        'bank_name',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
