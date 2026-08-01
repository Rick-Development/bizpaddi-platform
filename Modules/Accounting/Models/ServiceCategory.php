<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
        'sort_order',
        'image',
    ];

    /**
     * Get the image URL.
     */
    protected function image(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($value) => $value ? \Illuminate\Support\Facades\Storage::disk('public')->url($value) : null,
        );
    }

    /**
     * Get the services for this category.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'category_id');
    }
}
