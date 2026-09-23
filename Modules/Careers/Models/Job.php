<?php

namespace Modules\Careers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Job extends Model
{
    protected $table = 'career_jobs';

    protected $fillable = [
        'title',
        'department',
        'location',
        'description',
        'responsibilities',
        'requirements',
        'benefits',
        'type',
        'workplace_type',
        'experience_level',
        'is_active',
        'is_featured',
        'status',
        'visibility',
        'category_id',
        'slug',
        'published_at',
        'closed_at',
        'application_deadline',
        'salary_min',
        'salary_max',
        'currency',
        'salary_period',
        'skills',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'application_deadline' => 'date',
        'published_at' => 'datetime',
        'closed_at' => 'datetime',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saving(function ($job) {
            if (empty($job->slug) && !empty($job->title)) {
                $baseSlug = Str::slug($job->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->where('id', '!=', $job->id ?? 0)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }
                $job->slug = $slug;
            }

            if (empty($job->status)) {
                $job->status = $job->is_active ? 'published' : 'draft';
            }
        });
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'career_job_id');
    }

    /**
     * Get currency symbol
     */
    public function getCurrencySymbolAttribute(): string
    {
        $curr = strtoupper($this->currency ?? 'NGN');
        return match ($curr) {
            'NGN' => '₦',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'CAD' => 'CA$',
            'AUD' => 'A$',
            'ZAR' => 'R',
            default => $this->currency ?: '₦',
        };
    }

    /**
     * Format salary display
     */
    public function getFormattedSalaryAttribute(): ?string
    {
        $symbol = $this->currency_symbol;
        $period = $this->salary_period ? ' / ' . strtolower($this->salary_period) : '';

        if (!empty($this->salary_min) && !empty($this->salary_max)) {
            return $symbol . number_format((float)$this->salary_min) . ' - ' . $symbol . number_format((float)$this->salary_max) . $period;
        }

        if (!empty($this->salary_min)) {
            return $symbol . number_format((float)$this->salary_min) . $period;
        }

        if (!empty($this->salary_max)) {
            return 'Up to ' . $symbol . number_format((float)$this->salary_max) . $period;
        }

        return null;
    }

    /**
     * Get skills as an array of tags
     */
    public function getSkillsListAttribute(): array
    {
        if (empty($this->skills)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->skills))));
    }

    /**
     * Format workplace type badge label
     */
    public function getWorkplaceLabelAttribute(): string
    {
        return match (strtolower($this->workplace_type ?? 'remote')) {
            'remote' => 'Remote',
            'hybrid' => 'Hybrid',
            'on-site', 'onsite' => 'On-site',
            default => ucfirst($this->workplace_type ?? 'Remote'),
        };
    }
}
