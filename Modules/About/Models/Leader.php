<?php
namespace Modules\About\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Leader extends Model implements HasMedia {
    use InteractsWithMedia;
    protected $fillable = ['name', 'position', 'bio', 'email', 'linkedin', 'order'];
    public function registerMediaCollections(): void {
        $this->addMediaCollection('photo')->singleFile();
    }
}
