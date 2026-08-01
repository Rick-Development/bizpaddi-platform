<?php
namespace Modules\Portfolio\Models;
use Illuminate\Database\Eloquent\Model;
class Project extends Model {
    protected $fillable = ['industry_id', 'title', 'client', 'description', 'impact_metrics', 'year', 'is_featured', 'featured_image', 'gallery'];
    protected $casts = [
        'is_featured' => 'boolean',
        'gallery' => 'array',
    ];
    
    public function industry() { 
        return $this->belongsTo(Industry::class); 
    }

    /**
     * Get the clean description without HTML tags.
     */
    public function getCleanDescriptionAttribute(): string
    {
        $description = $this->description;
        // Decode twice to handle double encoding
        $description = html_entity_decode(html_entity_decode($description));
        return strip_tags($description);
    }
}
