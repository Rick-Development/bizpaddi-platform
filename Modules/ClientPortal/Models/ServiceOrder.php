<?php

namespace Modules\ClientPortal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\Quote;
use Modules\Accounting\Models\Service;
use Modules\Clients\Models\Client;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'status',
        'details',
        'admin_notes',
        'admin_feedback',
        'feature_selections',
        'quote_id',
        'invoice_id',
        'contract_id',
    ];

    protected $casts = [
        'details' => 'array',
        'feature_selections' => 'array',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(\Modules\Contracts\Models\Contract::class);
    }
}
