<?php
namespace Modules\Legal\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LegalPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'version',
        'effective_date'
    ];

    protected $casts = [
        'effective_date' => 'datetime',
    ];

    protected $appends = ['web_url'];

    public function getWebUrlAttribute()
    {
        // Construct the frontend URL. 
        // Limitations and Privacy Policy move to their respective URLs as defined in master.blade.php
        $slug = $this->slug;
        $baseUrl = config('app.url');
        
        if ($slug === 'privacy-policy' || $slug === 'terms-conditions') {
            return $baseUrl . '/' . $slug;
        }
        
        return $baseUrl . '/legal/' . $slug;
    }
}

