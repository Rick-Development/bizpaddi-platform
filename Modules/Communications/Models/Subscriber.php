<?php
namespace Modules\Communications\Models;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model { protected $fillable = ['email', 'name', 'is_active']; protected $casts = ['is_active' => 'boolean']; }
class Campaign extends Model { protected $fillable = ['name', 'subject', 'content', 'type', 'status', 'scheduled_at', 'sent_at']; protected $casts = ['scheduled_at' => 'datetime', 'sent_at' => 'datetime']; }
