<?php

namespace Modules\Investor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FinancialReport extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'report_type',
        'fiscal_year',
        'fiscal_quarter',
        'report_date',
        'summary',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'report_date' => 'date',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'fiscal_quarter' => 'integer',
    ];

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('report_file')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    /**
     * Scope for published reports
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope for filtering by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    /**
     * Scope for filtering by year
     */
    public function scopeForYear($query, $year)
    {
        return $query->where('fiscal_year', $year);
    }

    /**
     * Get formatted report period
     */
    public function getReportPeriodAttribute()
    {
        if ($this->report_type === 'quarterly' && $this->fiscal_quarter) {
            return "Q{$this->fiscal_quarter} {$this->fiscal_year}";
        }
        return $this->fiscal_year;
    }
}
