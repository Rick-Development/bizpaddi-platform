<?php
namespace Modules\Careers\Models;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model {
    protected $fillable = ['name', 'email', 'phone', 'linkedin'];
    public function applications() { return $this->hasMany(Application::class); }
}
