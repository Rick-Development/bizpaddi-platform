<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    protected $fillable = ['user_id', 'user_type', 'token'];

    public function user()
    {
        return $this->morphTo();
    }
}
