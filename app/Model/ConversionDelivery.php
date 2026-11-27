<?php

namespace App\Model;

use App\Model\Traits\HasSafeGuardedColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string|float|int|null $amount
 * @property string|null $currency
 * @property ConversionAttribution|null $attribution
 * @property Transaction|null $transaction
 */
class ConversionDelivery extends Model
{
    use HasSafeGuardedColumns;

    protected $fillable = [
        'transaction_id',
        'attribution_id',
        'event_id',
        'provider_event_key',
        'method',
        'config_hash',
        'counting_mode',
        'purchase_type',
        'is_recurring',
        'value',
        'currency',
        'test_mode',
        'status',
        'last_error',
        'available_at',
        'leased_until',
        'sent_at',
        'browser_claim_token',
        'attempts',
    ];

    protected $guarded = ['id'];

    protected $hidden = ['browser_claim_token', 'provider_event_key'];

    protected $casts = [
        'test_mode' => 'boolean',
        'is_recurring' => 'boolean',
        'attempts' => 'integer',
        'value' => 'decimal:2',
        'available_at' => 'datetime',
        'leased_until' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /** @return BelongsTo<Transaction, $this> */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /** @return BelongsTo<ConversionAttribution, $this> */
    public function attribution(): BelongsTo
    {
        return $this->belongsTo(ConversionAttribution::class, 'attribution_id');
    }

    public function payload(): array
    {
        return [
            'event_id' => $this->event_id,
            'event_name' => 'purchase',
            'purchase_type' => $this->purchase_type,
            'is_recurring' => (bool) $this->is_recurring,
            'transaction_id' => (string) $this->transaction_id,
            'value' => number_format((float) ($this->value ?? $this->transaction->amount), 2, '.', ''),
            'currency' => strtoupper((string) ($this->currency ?? $this->transaction->currency)),
        ];
    }
}
