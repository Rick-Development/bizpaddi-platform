<?php
namespace Modules\About\Models;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Award extends Model implements HasMedia {
    use InteractsWithMedia;
    protected $fillable = ['title', 'organization', 'year', 'description'];
    
    public function registerMediaCollections(): void {
        $this->addMediaCollection('image')->singleFile();
    }
}
