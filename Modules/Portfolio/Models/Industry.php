<?php
namespace Modules\Portfolio\Models;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model {
    protected $fillable = ['name', 'slug'];
    public function projects() { return $this->hasMany(Project::class); }
}
