<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');  // Nama lengkap
            $table->string('phone_number');  // No HP
            $table->text('address');  // Alamat
            $table->foreignId('car_id')->constrained('cars')->onDelete('cascade');  // Foreign key ke cars
            $table->enum('rental_service', ['Lepas Kunci', 'Dengan Driver']);  // Layanan sewa
            $table->date('rental_date');  // Tanggal penyewaan
            $table->date('return_date');  // Tanggal pengembalian
            $table->string('delivery_location')->nullable();  // Lokasi antar
            $table->string('return_location')->nullable();  // Lokasi pengembalian
            $table->time('delivery_time')->nullable();  // Jam pengantaran
            $table->time('return_time')->nullable();  // Jam pengembalian
            $table->text('special_notes')->nullable();  // Catatan khusus
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            // Indexes
            $table->index(['rental_date', 'return_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};