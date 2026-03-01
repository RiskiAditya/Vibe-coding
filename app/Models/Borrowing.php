<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'equipment_id',
        'borrow_date',
        'requested_return_date',
        'actual_return_date',
        'status',
        'approved_by',
        'notes',
        'return_condition',
        'damage_notes',
        'repair_cost',
        'late_days',
        'late_fee',
        'total_penalty',
        'penalty_paid',
        'penalty_paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'borrow_date' => 'date',
        'requested_return_date' => 'date',
        'actual_return_date' => 'date',
        'repair_cost' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'total_penalty' => 'decimal:2',
        'penalty_paid' => 'boolean',
        'penalty_paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the borrowing.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the equipment that is borrowed.
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    /**
     * Get the user who approved the borrowing.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if borrowing is late.
     */
    public function isLate(): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }
        
        return now()->greaterThan($this->requested_return_date);
    }

    /**
     * Calculate late days.
     */
    public function calculateLateDays(): int
    {
        if ($this->status === 'returned' && $this->actual_return_date) {
            $days = $this->requested_return_date->diffInDays($this->actual_return_date, false);
            return $days > 0 ? $days : 0;
        }
        
        if ($this->status === 'approved') {
            $days = $this->requested_return_date->diffInDays(now(), false);
            return $days > 0 ? $days : 0;
        }
        
        return 0;
    }

    /**
     * Calculate late fee (Rp 5,000 per day).
     */
    public function calculateLateFee(): float
    {
        return $this->calculateLateDays() * 5000;
    }
}
