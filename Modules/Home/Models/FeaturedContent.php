<?php

namespace Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FeaturedContent extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'excerpt',
        'type',
        'link',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(400)
                    ->height(300);
            });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
