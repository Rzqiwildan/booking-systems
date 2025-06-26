<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
{
    $cars = Car::all();  // Mendapatkan semua mobil dari database
    return view('booking', compact('cars'));  // Kirimkan variabel $cars ke view
}

    public function submitForm(Request $request)
{
    // Validasi input
    $request->validate([
        'full_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'car_id' => 'required|exists:cars,id', // Pastikan car_id valid
        'rental_service' => 'required|string',
        'rental_date' => 'required|date|after_or_equal:today',
        'return_date' => 'required|date|after:rental_date',
        'delivery_location' => 'nullable|string|max:255',
        'return_location' => 'nullable|string|max:255',
        'special_notes' => 'nullable|string',
    ]);

    // Simpan data booking ke tabel bookings
    $booking = Booking::create([
        'full_name' => $request->full_name,
        'phone_number' => $request->phone_number,
        'address' => $request->address,
        'car_id' => $request->car_id,  // Gunakan car_id dari form
        'rental_service' => $request->rental_service,
        'rental_date' => $request->rental_date,
        'return_date' => $request->return_date,
        'delivery_location' => $request->delivery_location,
        'return_location' => $request->return_location,
        'delivery_time' => $request->delivery_time,
        'return_time' => $request->return_time,
        'special_notes' => $request->special_notes,
        'status' => 'pending', // Status awal
    ]);

    // Setelah berhasil disimpan, redirect ke halaman WhatsApp
    $message = "*FORMULIR PEMESANAN RENTAL MOBIL*\n\n";
    $message .= "*Nama Lengkap:* {$request->full_name}\n";
    $message .= "*No. Handphone:* {$request->phone_number}\n";
    $message .= "*Alamat:* {$request->address}\n";
    $message .= "*Mobil:* {$booking->car->name}\n";
    $message .= "*Tipe Transmisi:* {$booking->car->type}\n";  // Anda bisa menambahkan informasi tipe mobil jika diperlukan
    $message .= "*Layanan Sewa:* {$request->rental_service}\n";
    $message .= "*Tanggal Penyewaan:* {$request->rental_date}\n";
    $message .= "*Tanggal Pengembalian:* {$request->return_date}\n";
    $message .= "*Lokasi Antar:* {$request->delivery_location}\n";
    $message .= "*Lokasi Pengembalian:* {$request->return_location}\n";
    $message .= "*Jam Pengantaran:* {$request->delivery_time}\n";
    $message .= "*Jam Pengembalian:* {$request->return_time}\n";
    
    if ($request->special_notes) {
        $message .= "*Catatan Khusus:* {$request->special_notes}\n";
    }
    
    $whatsappUrl = "https://wa.me/6281316413586?text=" . urlencode($message);

    return redirect()->away($whatsappUrl);  // Mengalihkan ke WhatsApp
}

}