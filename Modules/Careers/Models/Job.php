<?php
namespace Modules\Careers\Models;
use Illuminate\Database\Eloquent\Model;

class Job extends Model {
    protected $table = 'career_jobs';
    protected $fillable = ['title', 'department', 'location', 'description', 'requirements', 'type', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function applications() { return $this->hasMany(Application::class, 'career_job_id'); }
}
