<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'name',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Scope untuk filter berdasarkan tipe
    public function scopeManual($query)
    {
        return $query->where('type', 'Manual');
    }

    public function scopeMatic($query)
    {
        return $query->where('type', 'Matic');
    }

    // Accessor untuk mendapatkan nama lengkap dengan type
    public function getFullNameAttribute(): string
    {
        return $this->name . ' (' . $this->type . ')';
    }

    // Scope untuk mobil yang tersedia pada tanggal tertentu
    public function scopeAvailableOn($query, $startDate, $endDate)
    {
        return $query->whereDoesntHave('bookings', function ($query) use ($startDate, $endDate) {
            $query->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('rental_date', [$startDate, $endDate])
                            ->orWhereBetween('return_date', [$startDate, $endDate])
                            ->orWhere(function ($query) use ($startDate, $endDate) {
                                $query->where('rental_date', '<=', $startDate)
                                    ->where('return_date', '>=', $endDate);
                            });
                });
        });
    }
}