<?php

namespace Modules\Home\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    protected $fillable = [
        'label',
        'url',
        'route_name',
        'location',
        'order',
        'parent_id',
        'is_active',
    ];

    public function parent()
    {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(NavigationItem::class, 'parent_id')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHeader($query)
    {
        return $query->whereIn('location', ['header', 'both']);
    }

    public function scopeFooter($query)
    {
        return $query->whereIn('location', ['footer', 'both']);
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('order');
    }
}
