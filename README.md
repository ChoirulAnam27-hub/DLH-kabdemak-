# Sistem Pengaduan Penumpukan Sampah & Pencemaran Lingkungan — Dinas Lingkungan Hidup (DLH) Kabupaten Demak

Sistem Informasi Geografis berbasis web yang dirancang untuk memudahkan warga Kabupaten Demak melaporkan permasalahan sampah liar maupun pencemaran lingkungan (air/udara). Dilengkapi dengan panel Backoffice DLH untuk pengelolaan disposisi petugas lapangan, status penanganan, peta spasial (marker cluster & heatmap), serta ekspor data rekapitulasi.

---

## 🛠️ Tech Stack & Spesifikasi

- **Backend Framework**: PHP (Laravel 13 - Laravel 11 skeleton modern)
- **Database**: MySQL 8.4 (dilengkapi data koordinat spasial latitude & longitude)
- **Frontend Framework**: Bootstrap 5 (Responsive Web Design) & Vanilla JavaScript
- **Peta Spasial**: Leaflet.js (OpenStreetMap) — *Gratis, tanpa API Key Google Maps*
- **Visualisasi Grafik**: Chart.js
- **Paket Integrasi**:
  - `barryvdh/laravel-dompdf` (Ekspor PDF rekapitulasi)
  - `maatwebsite/excel` (Ekspor Excel rekapitulasi)

---

## ⚙️ Persyaratan Sistem (Prerequisites)

Sebelum menjalankan aplikasi, pastikan komputer Anda telah terpasang:
1. **Laragon** (atau XAMPP) dengan:
   - **PHP >= 8.2** (Rekomendasi PHP 8.3)
   - **MySQL >= 8.0**
2. **Composer** (untuk dependensi PHP)
3. **Node.js** & **NPM** (untuk frontend compilation jika diperlukan, namun semua CSS/JS di aplikasi ini menggunakan CDN + Custom CSS/JS terintegrasi agar ringan)

---

## 🚀 Panduan Instalasi di Localhost (Laragon)

Ikuti langkah-langkah berikut untuk menjalankan aplikasi:

### 1. Persiapan Database MySQL
1. Buka **Laragon** lalu klik **Start All**.
2. Klik tombol **Database** (atau gunakan phpMyAdmin / HeidiSQL).
3. Buat database baru dengan nama: **`dlh_demak`**.

### 2. Konfigurasi Environment (`.env`)
Buka file `.env` di root folder project, pastikan konfigurasi database sudah sesuai:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dlh_demak
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Jalankan Migrasi Database dan Seeders
Gunakan terminal untuk membuat tabel dan mengisi data wilayah Demak (14 kecamatan + desa) beserta 20 laporan pengaduan dummy:
```bash
php artisan migrate:fresh --seed
```

### 4. Jalankan Server Laravel
Mulai server development lokal Anda:
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses melalui browser di alamat: **[http://localhost:8000](http://localhost:8000)**.

---

## 🔑 Akun Uji Coba (Login Backoffice)

Untuk masuk ke dashboard admin/petugas, kunjungi halaman **`http://localhost:8000/login`** dan gunakan akun berikut:

### 1. Akun Administrator (Akses Penuh)
- **Email**: `admin@dlh-demak.go.id`
- **Password**: `password`
- **Fitur**: Statistik lengkap, pengelolaan status laporan, disposisi petugas lapangan, ekspor data PDF/Excel, dan peta sebaran interaktif.

### 2. Akun Petugas Lapangan (Akses Terbatas)
- **Email**: `budi.petugas@dlh-demak.go.id` atau `siti.petugas@dlh-demak.go.id`
- **Password**: `password`
- **Fitur**: Melihat tugas pengerjaan yang didisposisikan, mengunggah foto bukti penyelesaian (foto lokasi bersih), dan menulis catatan penyelesaian.

---

## 📋 Fitur Utama Aplikasi

### 1. Portal Warga (Responsive / PWA-Ready)
- **Form Laporan Cepat**: Mengisi data pelapor (Nama & No. WhatsApp) tanpa login.
- **Deteksi GPS Otomatis**: Secara otomatis mendeteksi koordinat latitude/longitude menggunakan sensor GPS smartphone/laptop.
- **Marker Peta Draggable**: Warga dapat mengeklik dan menggeser marker pada peta Leaflet untuk menyesuaikan titik lokasi kejadian secara presisi.
- **Reverse Geocoding**: Mengonversi titik koordinat GPS menjadi alamat nama jalan secara otomatis (via OpenStreetMap Nominatim API).
- **Foto Bukti**: Warga mengunggah foto bukti tumpukan sampah atau pencemaran.
- **Kode Tiket Unik**: Menghasilkan kode tiket dengan format `DLH-YYYYMMDD-XXX` untuk pelacakan.
- **Lacak Laporan**: Lacak timeline penanganan real-time berdasarkan kode tiket atau nomor WhatsApp pelapor.

### 2. Dashboard Admin (Backoffice Staff)
- **Statistik & Tren**: Panel ringkasan statistik status pengaduan dan grafik tren bulanan menggunakan Chart.js.
- **Tabel Pengaduan & Filter**: Daftar aduan masuk yang dapat difilter berdasarkan Status, Kategori, Kecamatan, dan Tanggal.
- **Disposisi Petugas**: Admin menunjuk petugas lapangan tertentu untuk menangani aduan.
- **Bukti Penyelesaian**: Petugas mengunggah foto lokasi setelah dibersihkan dan menulis laporan penutupan tiket.
- **Peta Sebaran Laporan**: Peta Leaflet dengan fitur cluster marker untuk menandai penumpukan sampah liar dan area rawan.
- **Heatmap Layer**: Tombol filter untuk mengubah visualisasi peta menjadi peta panas (heatmap) guna mendeteksi area dengan tingkat aduan tertinggi di Demak.
- **Ekspor Laporan**: Tombol instan untuk mengunduh rekapitulasi data dalam format PDF landscape terformat dan Excel `.xlsx`.

---

## 📂 Struktur Folder Utama
- `app/Http/Controllers/` — Logika Backend API, autentikasi, publik, dan admin.
- `app/Models/` — Model database (User, Report, Kecamatan, Kelurahan).
- `database/migrations/` — Skema tabel MySQL.
- `database/seeders/` — Pengisi data wilayah Demak dan laporan simulasi.
- `public/css/custom.css` — Kustomisasi style warna hijau-oranye DLH.
- `public/js/` — Logika peta Leaflet untuk form warga, heatmap admin, dan Chart.js.
- `resources/views/` — Templating Blade HTML.
- `routes/web.php` — Seluruh alur routing aplikasi.

---
*Dibuat untuk memenuhi tugas magang di Dinas Lingkungan Hidup (DLH) Kabupaten Demak.*
