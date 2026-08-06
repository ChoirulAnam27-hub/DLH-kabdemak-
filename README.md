# Sistem Pengaduan Penumpukan Sampah & Pencemaran Lingkungan - DLH Demak

Aplikasi web terintegrasi untuk melayani pengaduan masyarakat terkait masalah lingkungan di wilayah Kabupaten Demak, dilengkapi dengan sistem pemetaan koordinat (GPS) dan manajemen disposisi penanganan oleh petugas Dinas Lingkungan Hidup.

## 🚀 Fitur Utama

### Portal Warga (Publik)
* **Pelaporan Tanpa Login**: Warga bisa langsung melapor tanpa proses registrasi yang rumit.
* **Deteksi GPS Otomatis**: Integrasi Leaflet.js untuk mendeteksi lokasi otomatis atau menandai manual di peta.
* **Upload Foto Bukti**: Mendukung upload hingga 3 foto sekaligus dengan pratinjau instan.
* **Tracking Laporan**: Warga mendapatkan *Kode Tiket* unik untuk memantau sejauh mana laporannya ditangani.

### Backoffice (Admin & Petugas)
* **Dashboard Analitik**: Menampilkan ringkasan statistik, grafik kategori, dan status penanganan hari ini.
* **Peta Sebaran Laporan**: Peta interaktif (Leaflet MarkerCluster) untuk melihat titik-titik rawan masalah lingkungan.
* **Manajemen Disposisi**: Admin dapat meneruskan (assign) laporan ke petugas lapangan spesifik.
* **Pelaporan Hasil (Resolusi)**: Petugas wajib mengunggah foto bukti penyelesaian sebelum menutup laporan.
* **Export PDF**: Fitur cetak rekapitulasi laporan bulanan untuk arsip DLH.

## 🛠️ Tech Stack
* **Framework**: Laravel 11 (PHP 8.3)
* **Database**: MySQL 8.x
* **Frontend CSS**: Bootstrap 5
* **Peta/Maps**: Leaflet.js (OpenStreetMap)
* **Export PDF**: Barryvdh/DomPDF

---

## 💻 Panduan Instalasi & Menjalankan Aplikasi Secara Lokal (Laragon/XAMPP)

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di komputer lokal Anda:

### 1. Persiapan Environment
1. Pastikan Laragon/XAMPP sudah berjalan (Apache & MySQL).
2. Buka Terminal/Command Prompt, masuk ke folder project ini.
   ```bash
   cd c:\laragon\www\"(DLH) Kabupaten Demak"
   ```

### 2. Konfigurasi Dependensi
Jalankan perintah composer untuk menginstal semua library yang dibutuhkan:
```bash
composer install
```

### 3. Konfigurasi Environment & Storage
Buat file environment dan tautkan folder storage untuk menyimpan foto:
```bash
copy .env.example .env
php artisan key:generate
php artisan storage:link
```

### 4. Setup Database
1. Buka aplikasi database client (misal: HeidiSQL / phpMyAdmin).
2. Buat database baru dengan nama `dlh_demak`.
3. Jalankan migrasi dan seeder untuk membuat struktur tabel dan mengisi data dummy (20 laporan tersebar se-Demak):
   ```bash
   php artisan migrate:fresh --seed
   ```

### 5. Menjalankan Server
Jika Anda menggunakan Laragon, aplikasi sudah bisa diakses melalui URL: 
`http://dlh-kabupaten-demak.test` (atau sesuai nama virtual host Laragon Anda).

Jika ingin menjalankan server artisan bawaan Laravel:
```bash
php artisan serve
```
Buka browser dan akses: `http://localhost:8000`

---

## 🔑 Data Login Dummy (Seeder)

Anda dapat menggunakan akun berikut untuk masuk ke Dashboard Admin/Petugas (`/login`):

**1. Administrator (Bisa melihat semua data, export PDF, disposisi)**
* **Email:** admin@dlh-demak.go.id
* **Password:** password

**2. Petugas Lapangan (Hanya melihat laporan yang ditugaskan kepadanya)**
* **Email:** budi@dlh-demak.go.id (Atau petugas1/2/3)
* **Password:** password

---

## 📄 Struktur Direktori Penting
* `app/Http/Controllers/Public` -> Logika sistem portal warga
* `app/Http/Controllers/Admin` -> Logika sistem backoffice
* `app/Services` -> *Business logic* yang kompleks (Upload file, generate tiket)
* `resources/views` -> Tampilan UI (Blade templates Bootstrap 5)
* `public/js` -> Script JavaScript kustom (Leaflet map & drag-drop foto)

> **Catatan Developer:**
> Basis kode ini dirancang menggunakan prinsip MVC yang bersih dan sudah dilengkapi validasi pada sisi server maupun klien. Siap untuk dikembangkan lebih lanjut.
