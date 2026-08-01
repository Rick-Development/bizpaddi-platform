<?php

namespace Modules\Clients\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'content',
        'author_name',
        'author_position',
        'rating',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'rating' => 'integer',
    ];

    /**
     * Scope a query to only include featured testimonials.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include active testimonials.
     * Using is_featured as the active indicator since is_active doesn't exist
     */
    public function scopeActive($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Accessor for message (maps to content)
     */
    public function getMessageAttribute()
    {
        return $this->content;
    }

    /**
     * Accessor for client_name (maps to author_name)
     */
    public function getClientNameAttribute()
    {
        return $this->author_name;
    }

    /**
     * Accessor for client_position (maps to author_position)
     */
    public function getClientPositionAttribute()
    {
        return $this->author_position;
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }
}
