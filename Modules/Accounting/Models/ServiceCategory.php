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
            get: function ($value) {
                if (!$value) {
                    return null;
                }

                $clean = $value;
                // Strip domain prefix
                $clean = preg_replace('/^https?:\/\/[^\/]+/', '', $clean);
                // Strip leading /storage/ or storage/ repeatedly
                while (preg_match('/^\/?storage\//i', $clean)) {
                    $clean = preg_replace('/^\/?storage\//i', '', $clean);
                }
                // Handle nested domain if present inside string
                if (preg_match('/https?:\/\/[^\/]+\/(.*)$/i', $clean, $matches)) {
                    $clean = $matches[1];
                    while (preg_match('/^\/?storage\//i', $clean)) {
                        $clean = preg_replace('/^\/?storage\//i', '', $clean);
                    }
                }

                $clean = ltrim($clean, '/');
                if (!$clean) {
                    return null;
                }

                return \Illuminate\Support\Facades\Storage::disk('public')->url($clean);
            }
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
