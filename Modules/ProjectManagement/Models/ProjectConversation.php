<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectConversation extends Model
{
    use HasFactory;

    protected $table = 'project_conversations';

    protected $fillable = [
        'project_id',
        'type', // client_team, internal_team
        'status', // active, suspended, terminated
        'name',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ProjectMessage::class, 'conversation_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class, 'conversation_id');
    }
}
