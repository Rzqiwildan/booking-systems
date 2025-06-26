<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Rental Mobil</title>
    <link rel="shortcut icon" href="{{ asset('ArfaIndonesia.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .form-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .section-title {
            color: #dc2626;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: #1f2937;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 1rem;
            background-color: white;
            cursor: pointer;
        }

        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            font-size: 1rem;
            resize: vertical;
            min-height: 120px;
        }

        .whatsapp-btn {
            background-color: #dc2626;
            color: white;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .whatsapp-btn:hover {
            background-color: #b91c1c;
        }

        .rental-terms {
            background-color: #f9fafb;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #dc2626;
            height: auto;
            /* Membuat container menyesuaikan dengan konten */
            min-height: 150px;
            /* Menyediakan ruang minimal */
            display: flex;
            flex-direction: column;
            /* Mengatur elemen secara vertikal */
        }

        .rental-terms h3 {
            color: #dc2626;
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .rental-terms ol {
            list-style: decimal;
            margin-left: 1.5rem;
            color: #374151;
        }

        .rental-terms li {
            margin-bottom: 0.5rem;
        }

        .bg-yellow-50 {
            background-color: #fef3c7;
        }

        .border-l-4 {
            border-left-width: 4px;
        }

        .border-yellow-400 {
            border-color: #fbbf24;
        }

        .p-4 {
            padding: 1rem;
        }

        .rounded {
            border-radius: 8px;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Formulir Pemesanan -->
            <div class="form-section">
                <h2 class="section-title">Formulir Pemesanan</h2>

                <form action="{{ route('form.submit') }}" method="POST" id="rentalForm">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-input" id="namaLengkap" name="full_name"
                            placeholder="Masukkan nama lengkap Anda" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">No. Handphone</label>
                            <input type="tel" class="form-input" id="noHandphone" name="phone_number"
                                placeholder="08xxxxxxxxx" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alamat</label>
                            <input type="text" class="form-input" id="alamat" name="address"
                                placeholder="Alamat lengkap" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pilihan Mobil</label>
                        <select class="form-select" id="pilihanMobil" name="car_id" required>
                            <option value="">Pilih Mobil</option>
                            @foreach ($cars as $car)
                                <option value="{{ $car->id }}">{{ $car->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Pilih Type</label>
                            <select class="form-select" id="pilihType" name="car_type" required>
                                <option value="">Pilih Salah Satu</option>
                                <option value="manual">Manual</option>
                                <option value="matic">Matic</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Layanan Sewa</label>
                            <select class="form-select" id="layananSewa" name="rental_service" required>
                                <option value="Lepas Kunci">Lepas Kunci</option>
                                <option value="Dengan Driver">Dengan Driver</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Tanggal Penyewaan</label>
                            <input type="date" class="form-input" id="tanggalPenyewaan" name="rental_date" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Pengembalian</label>
                            <input type="date" class="form-input" id="tanggalPengembalian" name="return_date"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Lokasi Antar</label>
                            <input type="text" class="form-input" id="lokasiAntar" name="delivery_location"
                                placeholder="Lokasi pengantaran">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Lokasi Pengembalian</label>
                            <input type="text" class="form-input" id="lokasiPengembalian" name="return_location"
                                placeholder="Lokasi pengembalian">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Jam Pengantaran</label>
                            <input type="time" class="form-input" id="jamPengantaran" name="delivery_time">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jam Pengembalian</label>
                            <input type="time" class="form-input" id="jamPengembalian" name="return_time">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan Khusus</label>
                        <textarea class="form-textarea" id="catatanKhusus" name="special_notes"
                            placeholder="Tuliskan catatan khusus jika ada..."></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="whatsapp-btn">Kirim ke Whatsapp</button>
                    </div>
                </form>
            </div>

            <!-- Syarat Rental -->
            <div class="form-section">
                <h2 class="section-title">Syarat Rental</h2>

                <div class="rental-terms">
                    <p class="mb-4 text-gray-700">
                        Cari dan pesan pilihan terbaik untuk layanan rental mobil terpercaya hanya di Arfa
                        Indonesia, sehingga lebih mudah dan dapat dipesan secara online.
                    </p>

                    <h3>Syarat Rental Mobil Lepas Kunci :</h3>
                    <ol class="mb-6">
                        <li>KTP</li>
                        <li>STNK</li>
                        <li>Motor</li>
                    </ol>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                        <p class="text-sm text-yellow-800 font-medium">
                            Semua syarat di atas adalah wajib menunjukan dokumen asli.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set minimum date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggalPenyewaan').min = today;
            document.getElementById('tanggalPengembalian').min = today;
        });

        // Update minimum return date when rental date changes
        document.getElementById('tanggalPenyewaan').addEventListener('change', function() {
            document.getElementById('tanggalPengembalian').min = this.value;
        });
    </script>
</body>

</html>
