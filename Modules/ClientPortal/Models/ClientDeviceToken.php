<?php

namespace Modules\ClientPortal\Models;

use Illuminate\Database\Eloquent\Model;

class ClientDeviceToken extends Model
{
    protected $fillable = ['client_id', 'fcm_token', 'device_type'];

    public function client()
    {
        return $this->belongsTo(\Modules\Clients\Models\Client::class);
    }
}
