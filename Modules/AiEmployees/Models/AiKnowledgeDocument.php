<?php

namespace Modules\AiEmployees\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\AiEmployees\Database\Factories\AiKnowledgeDocumentFactory;
use App\Traits\BelongsToTenant;

class AiKnowledgeDocument extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'title',
        'category',
        'content',
    ];

    /**
     * Scope a query to search documents.
     */
    public function scopeSearch($query, $term)
    {
        return $query->whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", [$term]);
    }

    protected static function newFactory()
    {
        // return \Modules\AiEmployees\Database\factories\AiKnowledgeDocumentFactory::new();
    }
}
