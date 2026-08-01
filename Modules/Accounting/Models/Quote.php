<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\User;

class Quote extends Model
{
    protected $fillable = [
        'quote_number',
        'client_id',
        'currency_id',
        'service_category_id',
        'service_id',
        'client_name',
        'client_email',
        'client_address',
        'client_phone',
        'issue_date',
        'valid_until',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'notes',
        'terms_conditions',
        'created_by',
        'approved_at',
        'converted_to_invoice_id',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quote) {
            if (empty($quote->quote_number)) {
                $quote->quote_number = static::generateQuoteNumber();
            }
        });
    }

    /**
     * Generate a unique quote number.
     */
    public static function generateQuoteNumber(): string
    {
        $year = date('Y');
        $lastQuote = static::whereYear('created_at', $year)->latest('id')->first();
        $number = $lastQuote ? ((int) substr($lastQuote->quote_number, -4)) + 1 : 1;
        
        return 'QUO-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate and update totals.
     */
    public function calculateTotals(): void
    {
        $this->subtotal = $this->items()->sum('total');
        $this->tax_amount = ($this->subtotal * $this->tax_rate) / 100;
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->save();
    }

    /**
     * Mark quote as approved.
     */
    public function markAsApproved(): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    /**
     * Convert quote to invoice.
     */
    public function convertToInvoice(): Invoice
    {
        $invoice = Invoice::create([
            'client_id' => $this->client_id,
            'client_name' => $this->client_name,
            'client_email' => $this->client_email,
            'client_address' => $this->client_address,
            'client_phone' => $this->client_phone,
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => $this->subtotal,
            'tax_rate' => $this->tax_rate,
            'tax_amount' => $this->tax_amount,
            'discount_amount' => $this->discount_amount,
            'total_amount' => $this->total_amount,
            'notes' => $this->notes,
            'terms_conditions' => $this->terms_conditions,
            'created_by' => $this->created_by,
            'quote_id' => $this->id,
        ]);

        // Copy items
        foreach ($this->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'service_id' => $item->service_id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total' => $item->total,
            ]);
        }

        // Update quote status
        $this->update([
            'status' => 'converted',
            'converted_to_invoice_id' => $invoice->id,
        ]);

        return $invoice;
    }

    /**
     * Check if quote is expired.
     */
    public function checkExpiration(): void
    {
        if ($this->valid_until < now() && $this->status === 'sent') {
            $this->update(['status' => 'expired']);
        }
    }

    /**
     * Get the items for this quote.
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function client()
    {
        return $this->belongsTo(\Modules\Clients\Models\Client::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the service category for this quote.
     */
    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Get the service for this quote.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Get the user who created this quote.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the invoice this quote was converted to.
     */
    public function convertedInvoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'converted_to_invoice_id');
    }

    /**
     * Scope for expired quotes.
     */
    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', now())
                    ->where('status', 'sent');
    }
}
