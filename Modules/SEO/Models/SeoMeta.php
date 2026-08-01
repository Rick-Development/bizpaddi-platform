<?php

namespace Modules\SEO\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    use HasFactory;

    protected $table = 'seo_meta';

    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'route_name',
        'title',
        'description',
        'keywords',
        'og_image',
        'twitter_card',
        'verification_codes',
        'schema_markup',
        'noindex',
        'nofollow',
    ];

    protected $casts = [
        'verification_codes' => 'array',
        'schema_markup' => 'array',
        'noindex' => 'boolean',
        'nofollow' => 'boolean',
    ];

    public function seoable()
    {
        return $this->morphTo();
    }
}
