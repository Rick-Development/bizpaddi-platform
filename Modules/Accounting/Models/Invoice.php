<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'client_id',
        'currency_id',
        'service_category_id',
        'service_id',
        'client_name',
        'client_email',
        'client_address',
        'client_phone',
        'issue_date',
        'due_date',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'notes',
        'terms_conditions',
        'created_by',
        'quote_id',
        'paid_at',
    ];

    /**
     * Get the service category for this invoice.
     */
    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Get the service for this invoice.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = static::generateInvoiceNumber();
            }
        });
    }

    /**
     * Generate a unique invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $lastInvoice = static::whereYear('created_at', $year)->latest('id')->first();
        $number = $lastInvoice ? ((int) substr($lastInvoice->invoice_number, -4)) + 1 : 1;
        
        return 'INV-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
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
     * Mark invoice as paid.
     */
    public function markAsPaid(): void
    {
        if ($this->status === 'paid') {
            return;
        }

        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Create a transaction record for this payment
        $this->createTransaction();
    }

    /**
     * Create a transaction for this invoice.
     */
    protected function createTransaction(): void
    {
        // Ensure Revenue category exists
        $revenueCategory = AccountCategory::firstOrCreate(
            ['code' => 'R'],
            [
                'name' => 'Revenue',
                'type' => 'credit',
                'description' => 'Income generated from business activities',
            ]
        );

        // Find or create a default revenue account
        $revenueAccount = Account::firstOrCreate(
            ['code' => 'REV-001'],
            [
                'category_id' => $revenueCategory->id,
                'name' => 'Revenue - Invoices',
                'description' => 'Revenue from paid invoices',
                'is_active' => true,
            ]
        );

        // Create the transaction
        Transaction::create([
            'account_id' => $revenueAccount->id,
            'type' => 'credit', // Revenue is a credit
            'amount' => $this->total_amount,
            'description' => "Payment received for Invoice #{$this->invoice_number} - {$this->client_name}",
            'reference' => $this->invoice_number,
            'transaction_date' => $this->paid_at ?? now(),
            'status' => 'completed',
            'created_by' => $this->created_by ?? auth('admin')->id(),
        ]);

        // Update account balance
        $revenueAccount->updateBalance();
    }

    /**
     * Get the items for this invoice.
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the client for this invoice.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(\Modules\Clients\Models\Client::class, 'client_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the quote this invoice was converted from.
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }

    /**
     * Scope for overdue invoices.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'paid')
                    ->where('due_date', '<', now());
    }
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'payable');
    }
}
