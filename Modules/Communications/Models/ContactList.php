<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToTenant;

class ContactList extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'total_contacts',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_contacts' => 'integer',
    ];

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'contact_list_contacts')
            ->using(ContactListContact::class)
            ->withPivot('id', 'created_at');
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function updateContactCount(): void
    {
        $this->update(['total_contacts' => $this->contacts()->count()]);
    }
}
