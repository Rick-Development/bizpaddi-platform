<?php

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Service extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'description', 'features', 'icon', 'is_active', 'order'];
    protected $casts = ['is_active' => 'boolean'];

    public function caseStudies() 
    { 
        return $this->hasMany(CaseStudy::class); 
    }

    // 👇 Add this method
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function registerMediaCollections(): void {
        $this->addMediaCollection('image')->singleFile();
    }
}