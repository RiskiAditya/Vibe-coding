<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Equipment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category_id',
        'status',
        'stock',
        'available_stock',
        'image',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category that owns the equipment.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the borrowings for the equipment.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class, 'equipment_id');
    }

    /**
     * Get the current borrowing for the equipment.
     */
    public function currentBorrowing(): HasOne
    {
        return $this->hasOne(Borrowing::class, 'equipment_id')
            ->whereIn('status', ['pending', 'approved'])
            ->latest();
    }
}
