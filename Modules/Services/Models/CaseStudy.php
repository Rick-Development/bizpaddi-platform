<?php
namespace Modules\Services\Models;
use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model {
    protected $fillable = ['service_id', 'title', 'client_name', 'challenge', 'solution', 'results'];
    public function service() { return $this->belongsTo(Service::class); }
}
