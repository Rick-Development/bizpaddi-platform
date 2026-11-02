<?php

namespace Modules\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Contact\Database\Factories\ContactSubmissionFactory;

use App\Traits\BelongsToTenant;

class ContactSubmission extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'status',
        'notes'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
