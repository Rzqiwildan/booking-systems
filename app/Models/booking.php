<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $fillable = [
        'full_name',
        'phone_number',
        'address',
        'car_id',
        'rental_service',
        'rental_date',
        'return_date',
        'delivery_location',
        'return_location',
        'delivery_time',
        'return_time',
        'special_notes',
        'status',
    ];

    protected $casts = [
        'rental_date' => 'date',
        'return_date' => 'date',
        'delivery_time' => 'datetime:H:i',
        'return_time' => 'datetime:H:i',
    ];

    // Relationship
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    // Accessor untuk durasi sewa
    public function getDurationAttribute(): int
    {
        return $this->rental_date->diffInDays($this->return_date) + 1;
    }

    // Accessor untuk mendapatkan car type
    public function getCarTypeAttribute(): string
    {
        return $this->car?->type ?? '';
    }

    // Scope untuk filter berdasarkan status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}