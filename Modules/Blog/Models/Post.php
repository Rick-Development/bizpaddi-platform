<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\User;

class Post extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category_id',
        'author_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'ai_generated',
        'ai_model',
        'ai_generated_at',
        'ai_metadata',
        'featured_image_prompt',
        'featured_image',
        'status',
        'published_at',
        'scheduled_at',
        'views_count',
        'reading_time',
    ];

    protected $casts = [
        'ai_generated' => 'boolean',
        'ai_metadata' => 'array',
        'ai_generated_at' => 'datetime',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    protected $dates = [
        'published_at',
        'scheduled_at',
        'ai_generated_at',
        'deleted_at',
    ];

    /**
     * Get the category that owns the post.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the author that owns the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * The tags that belong to the post.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile()
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumb')
                    ->width(400)
                    ->height(300);
                
                $this->addMediaConversion('medium')
                    ->width(800)
                    ->height(600);
                
                $this->addMediaConversion('large')
                    ->width(1200)
                    ->height(900);
            });

        $this->addMediaCollection('content_images');
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include AI-generated posts.
     */
    public function scopeAiGenerated($query)
    {
        return $query->where('ai_generated', true);
    }

    /**
     * Calculate and update reading time.
     */
    public function calculateReadingTime(): void
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $this->reading_time = ceil($wordCount / 200); // Average reading speed: 200 words/minute
        $this->save();
    }

    /**
     * Increment views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Get the clean title without HTML tags.
     */
    public function getCleanTitleAttribute(): string
    {
        $title = $this->title;
        // Decode twice to handle double encoding
        $title = html_entity_decode(html_entity_decode($title));
        return strip_tags($title);
    }

    /**
     * Get the clean meta title without HTML tags.
     */
    public function getCleanMetaTitleAttribute(): string
    {
        $title = $this->meta_title ?? $this->title;
        $title = html_entity_decode(html_entity_decode($title));
        return strip_tags($title);
    }

    /**
     * Get the clean excerpt without HTML tags.
     */
    public function getCleanExcerptAttribute(): string
    {
        $excerpt = $this->excerpt;
        // Decode twice to handle double encoding
        $excerpt = html_entity_decode(html_entity_decode($excerpt));
        return strip_tags($excerpt);
    }
}
