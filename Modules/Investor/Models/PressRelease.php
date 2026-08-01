<?php

namespace Modules\Investor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class PressRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'category',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pressRelease) {
            if (empty($pressRelease->slug)) {
                $pressRelease->slug = Str::slug($pressRelease->title);
            }
        });

        static::updating(function ($pressRelease) {
            if ($pressRelease->isDirty('title') && empty($pressRelease->slug)) {
                $pressRelease->slug = Str::slug($pressRelease->title);
            }
        });
    }

    /**
     * Scope for published releases
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope for featured releases
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for filtering by category
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get route key name
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
