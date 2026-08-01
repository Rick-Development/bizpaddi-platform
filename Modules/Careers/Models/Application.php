<?php
namespace Modules\Careers\Models;
use Illuminate\Database\Eloquent\Model;

class Application extends Model {
    protected $table = 'career_applications';
    protected $fillable = ['career_job_id', 'candidate_id', 'cover_letter', 'status', 'ai_score', 'resume_path'];
    public function job() { return $this->belongsTo(Job::class, 'career_job_id'); }
    public function candidate() { return $this->belongsTo(Candidate::class); }
}
