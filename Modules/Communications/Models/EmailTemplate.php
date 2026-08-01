<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'content',
        'thumbnail',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'template_id');
    }

    public function replaceVariables(array $data): string
    {
        $content = $this->content;
        
        foreach ($data as $key => $value) {
            $content = str_replace([
                '{{' . $key . '}}',
                '{{ ' . $key . ' }}',
            ], $value, $content);
        }
        
        return $content;
    }
}
