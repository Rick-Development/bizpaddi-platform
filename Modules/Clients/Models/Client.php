<?php

namespace Modules\Clients\Models;

use App\Models\FcmToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'description',
        'industry',
        'website',
        'company_name',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'tax_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function projects()
    {
        return $this->hasMany(\Modules\ProjectManagement\Models\Project::class);
    }

    public function invoices()
    {
        return $this->hasMany(\Modules\Accounting\Models\Invoice::class);
    }

    public function quotes()
    {
        return $this->hasMany(\Modules\Accounting\Models\Quote::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(\Modules\ClientPortal\Models\SupportTicket::class);
    }

    public function serviceOrders()
    {
        return $this->hasMany(\Modules\ClientPortal\Models\ServiceOrder::class);
    }

    public function fcmTokens()
    {
        return $this->morphMany(FcmToken::class, 'user');
    }
}
