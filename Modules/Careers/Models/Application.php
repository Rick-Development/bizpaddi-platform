<?php
namespace Modules\Careers\Models;
use Illuminate\Database\Eloquent\Model;

class Application extends Model {
    protected $table = 'career_applications';
    protected $fillable = ['career_job_id', 'candidate_id', 'cover_letter', 'status', 'ai_score', 'resume_path'];
    protected $appends = ['resume_url'];

    public function job() { return $this->belongsTo(Job::class, 'career_job_id'); }
    public function candidate() { return $this->belongsTo(Candidate::class); }

    public function getResumeUrlAttribute(): ?string
    {
        if (!$this->resume_path) {
            return null;
        }

        $rawPath = $this->resume_path;
        $cleanPath = preg_replace('/^https?:\/\/[^\/]+/', '', $rawPath);
        $cleanPath = preg_replace('/^\/?storage\//', '', $cleanPath);
        $cleanPath = ltrim($cleanPath, '/');

        return asset('storage/' . $cleanPath);
    }
}
