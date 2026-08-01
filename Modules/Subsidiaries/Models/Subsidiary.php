<?php
namespace Modules\Subsidiaries\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Subsidiary extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'website',
        'location'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }
}

class Partner extends Model { protected $fillable = ['name', 'description', 'website', 'partnership_type']; }
