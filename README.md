# Sistem Pengaduan Penumpukan Sampah & Pencemaran Lingkungan - DLH Demak

Aplikasi web terintegrasi untuk melayani pengaduan masyarakat terkait masalah lingkungan di wilayah Kabupaten Demak, dilengkapi dengan sistem pemetaan koordinat (GPS), **klasifikasi jenis sampah berbasis AI (CNN)**, dan manajemen disposisi penanganan oleh petugas Dinas Lingkungan Hidup.

## 🚀 Fitur Utama

### Portal Warga (Publik)

- **Pelaporan Tanpa Login**: Warga bisa langsung melapor tanpa proses registrasi, dengan opsi laporan anonim.
- **Deteksi GPS Otomatis**: Integrasi Leaflet.js untuk mendeteksi lokasi otomatis atau menandai manual di peta.
- **Upload Foto Bukti**: Mendukung upload foto bukti (JPG, JPEG, PNG, WEBP, HEIC) dengan pratinjau instan.
- **Klasifikasi Sampah AI (CNN)**: Model MobileNetV2 berjalan langsung di browser via TensorFlow.js untuk mendeteksi jenis sampah **Organik / Anorganik** secara otomatis dari foto yang diunggah.
- **Tracking Laporan**: Warga mendapatkan _Kode Tiket_ unik untuk memantau sejauh mana laporannya ditangani.
- **Deteksi Duplikat**: Sistem memperingatkan jika ada laporan serupa di sekitar lokasi yang sama.

### Backoffice (Admin & Petugas)

- **Dashboard Analitik**: Menampilkan ringkasan statistik, grafik kategori, dan status penanganan hari ini.
- **Peta Sebaran Laporan**: Peta interaktif (Leaflet MarkerCluster) untuk melihat titik-titik rawan masalah lingkungan.
- **Manajemen Disposisi**: Admin dapat meneruskan (assign) laporan ke petugas lapangan spesifik.
- **Halaman Tugas Petugas**: Petugas hanya melihat laporan yang ditugaskan kepadanya melalui halaman _Tugas Saya_.
- **Pelaporan Hasil (Resolusi)**: Petugas wajib mengunggah foto bukti penyelesaian sebelum menutup laporan.
- **Export PDF & Excel**: Fitur cetak/unduh rekapitulasi laporan untuk arsip DLH.
- **Manajemen Petugas**: Admin dapat mengelola (CRUD) akun petugas lapangan.

## 🤖 Integrasi AI — Klasifikasi Sampah CNN

Saat warga mengunggah foto pada kategori **Sampah Menumpuk**, sistem secara otomatis:

1. Memuat model **MobileNetV2** (dikonversi ke format TensorFlow.js) langsung di browser.
2. Melakukan preprocessing gambar (resize 224×224, normalisasi [-1, 1]).
3. Menjalankan prediksi dan menampilkan hasil klasifikasi (**Organik** atau **Anorganik**) beserta tingkat kepercayaan (%).
4. Mengisi otomatis field `waste_type` pada laporan.

> Model dan label berada di folder `public/model/`. Untuk mengganti model, cukup timpa file `model.json`, `labels.json`, dan shard `.bin` di folder tersebut.

## 🛠️ Tech Stack

- **Framework**: Laravel 13 (PHP 8.3)
- **Database**: SQLite (default, bisa diganti MySQL)
- **Frontend CSS**: Bootstrap 5
- **Peta/Maps**: Leaflet.js (OpenStreetMap)
- **AI/ML**: TensorFlow.js 4.20 + MobileNetV2 (browser-side inference)
- **Export**: Barryvdh/DomPDF (PDF), Maatwebsite/Excel (Excel)
- **Image Processing**: Intervention Image

---

## 💻 Panduan Instalasi & Menjalankan Aplikasi Secara Lokal

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di komputer lokal Anda:

### 1. Persiapan Environment

Pastikan PHP 8.3+, Composer, dan Node.js sudah terinstal.

### 2. Konfigurasi Dependensi

```bash
composer install
npm install
```

### 3. Konfigurasi Environment & Storage

```bash
copy .env.example .env
php artisan key:generate
php artisan storage:link
```

### 4. Setup Database

Secara default proyek ini menggunakan **SQLite**. File database akan otomatis dibuat saat migrasi pertama kali.

```bash
php artisan migrate:fresh --seed
```

> Jika ingin menggunakan MySQL, ubah konfigurasi `DB_CONNECTION` di file `.env`.

### 5. Menjalankan Server

```bash
php artisan serve
```

Buka browser dan akses: `http://localhost:8000`

---

## 🔑 Data Login Dummy (Seeder)

Anda dapat menggunakan akun berikut untuk masuk ke Dashboard (`/login`):

**1. Administrator** _(Bisa melihat semua data, export, disposisi, kelola petugas)_

- **Email:** admin@dlh-demak.go.id
- **Password:** password

**2. Petugas Lapangan** _(Hanya melihat laporan yang ditugaskan kepadanya)_

- **Email:** budi@dlh-demak.go.id
- **Password:** password

---

## 📄 Struktur Direktori Penting

- `app/Http/Controllers/Public` → Logika portal warga (lapor, lacak)
- `app/Http/Controllers/Admin` → Logika backoffice (dashboard, manajemen, export)
- `app/Services` → _Business logic_ (upload file, generate tiket, WhatsApp notif)
- `resources/views` → Tampilan UI (Blade templates Bootstrap 5)
- `public/js` → Script JavaScript kustom (Leaflet map, photo upload, AI classifier)
- `public/model` → File model TensorFlow.js (model.json, labels.json, shard .bin)

> **Catatan Developer:**
> Basis kode ini dirancang menggunakan prinsip MVC yang bersih dan sudah dilengkapi validasi pada sisi server maupun klien. Klasifikasi AI berjalan sepenuhnya di sisi klien (browser) tanpa memerlukan server GPU. Siap untuk dikembangkan lebih lanjut.
