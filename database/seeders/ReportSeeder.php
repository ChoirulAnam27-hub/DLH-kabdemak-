<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\ReportHistory;
use Carbon\Carbon;

/**
 * Seeder ReportSeeder — Data dummy laporan pengaduan.
 * 20 laporan tersebar di berbagai lokasi wilayah Kabupaten Demak
 * dengan variasi status, kategori, dan waktu pelaporan.
 */
class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $reports = [
            [
                'reporter_name' => 'Ahmad Fauzi',
                'reporter_phone' => '081234567001',
                'category' => 'sampah',
                'description' => 'Terdapat tumpukan sampah rumah tangga yang sudah menumpuk selama 1 minggu di pinggir jalan utama. Sampah sudah mulai berbau dan mengganggu warga sekitar.',
                'latitude' => -6.8936,
                'longitude' => 110.6381,
                'address' => 'Jl. Sultan Fatah No. 15, Bintoro',
                'kecamatan' => 'Demak',
                'kelurahan' => 'Bintoro',
                'status' => 'pending',
                'priority' => 'tinggi',
            ],
            [
                'reporter_name' => 'Siti Aminah',
                'reporter_phone' => '081234567002',
                'category' => 'sampah',
                'description' => 'Sampah menumpuk di TPS yang sudah lama tidak diangkut. Volume sampah sudah meluber keluar bak penampungan.',
                'latitude' => -6.9147,
                'longitude' => 110.4833,
                'address' => 'TPS Jl. Raya Sayung, Sayung',
                'kecamatan' => 'Sayung',
                'kelurahan' => 'Sayung',
                'status' => 'diproses',
                'priority' => 'tinggi',
                'assigned_to' => 2,
            ],
            [
                'reporter_name' => 'Budi Prasetyo',
                'reporter_phone' => '081234567003',
                'category' => 'pencemaran_air',
                'description' => 'Air sungai berubah warna menjadi hitam pekat dan mengeluarkan bau menyengat. Diduga ada limbah pabrik yang dibuang ke sungai.',
                'latitude' => -6.9015,
                'longitude' => 110.5797,
                'address' => 'Sungai Tuntang, Karangtengah',
                'kecamatan' => 'Karangtengah',
                'kelurahan' => 'Wonowoso',
                'status' => 'diproses',
                'priority' => 'darurat',
                'assigned_to' => 3,
            ],
            [
                'reporter_name' => 'Dewi Lestari',
                'reporter_phone' => '081234567004',
                'category' => 'sampah',
                'description' => 'Sampah plastik berserakan di area pasar dan saluran drainase tersumbat sampah.',
                'latitude' => -6.9463,
                'longitude' => 110.6595,
                'address' => 'Pasar Guntur, Jl. Pasar Baru',
                'kecamatan' => 'Guntur',
                'kelurahan' => 'Guntur',
                'status' => 'selesai',
                'priority' => 'sedang',
                'assigned_to' => 2,
            ],
            [
                'reporter_name' => 'Eko Widodo',
                'reporter_phone' => '081234567005',
                'category' => 'pencemaran_udara',
                'description' => 'Asap tebal dari pembakaran sampah di lahan kosong. Sudah berlangsung 3 hari berturut-turut.',
                'latitude' => -7.0228,
                'longitude' => 110.4853,
                'address' => 'Jl. Raya Mranggen Km 5',
                'kecamatan' => 'Mranggen',
                'kelurahan' => 'Bandungrejo',
                'status' => 'pending',
                'priority' => 'tinggi',
            ],
            [
                'reporter_name' => 'Fatimah Zahra',
                'reporter_phone' => '081234567006',
                'category' => 'sampah',
                'description' => 'Tumpukan sampah di tepi sungai menghambat aliran air. Warga khawatir akan banjir saat musim hujan.',
                'latitude' => -7.0456,
                'longitude' => 110.5384,
                'address' => 'Bantaran Sungai, Karangawen',
                'kecamatan' => 'Karangawen',
                'kelurahan' => 'Jragung',
                'status' => 'pending',
                'priority' => 'darurat',
            ],
            [
                'reporter_name' => 'Gunawan Hadi',
                'reporter_phone' => '081234567007',
                'category' => 'pencemaran_air',
                'description' => 'Tambak ikan warga tercemar limbah. Banyak ikan mati mendadak dalam semalam.',
                'latitude' => -6.8502,
                'longitude' => 110.6977,
                'address' => 'Area Tambak, Bonang',
                'kecamatan' => 'Bonang',
                'kelurahan' => 'Betahwalang',
                'status' => 'diproses',
                'priority' => 'darurat',
                'assigned_to' => 2,
            ],
            [
                'reporter_name' => 'Hesti Wulandari',
                'reporter_phone' => '081234567008',
                'category' => 'sampah',
                'description' => 'Sampah menumpuk di sepanjang jalan masuk desa. Sudah 2 minggu tidak ada pengangkutan.',
                'latitude' => -6.7758,
                'longitude' => 110.6672,
                'address' => 'Jl. Desa Wedung',
                'kecamatan' => 'Wedung',
                'kelurahan' => 'Wedung',
                'status' => 'selesai',
                'priority' => 'sedang',
                'assigned_to' => 3,
            ],
            [
                'reporter_name' => 'Irfan Hakim',
                'reporter_phone' => '081234567009',
                'category' => 'lainnya',
                'description' => 'Banyak bangkai hewan di pinggir jalan yang tidak segera dibersihkan. Menimbulkan bau yang sangat tidak sedap.',
                'latitude' => -6.9095,
                'longitude' => 110.7308,
                'address' => 'Jl. Raya Gajah-Karanganyar',
                'kecamatan' => 'Gajah',
                'kelurahan' => 'Gajah',
                'status' => 'ditolak',
                'priority' => 'rendah',
            ],
            [
                'reporter_name' => 'Joko Susanto',
                'reporter_phone' => '081234567010',
                'category' => 'sampah',
                'description' => 'TPA mini di RT 03 sudah penuh dan sampah berceceran ke jalan. Perlu penanganan segera.',
                'latitude' => -6.8630,
                'longitude' => 110.7520,
                'address' => 'RT 03 RW 02, Karanganyar',
                'kecamatan' => 'Karanganyar',
                'kelurahan' => 'Karanganyar',
                'status' => 'pending',
                'priority' => 'tinggi',
            ],
            [
                'reporter_name' => 'Kartini Putri',
                'reporter_phone' => '081234567011',
                'category' => 'pencemaran_air',
                'description' => 'Sumur warga berubah warna dan berbau. Diduga tercemar limbah pertanian.',
                'latitude' => -6.9544,
                'longitude' => 110.7402,
                'address' => 'Dusun Krajan, Mijen',
                'kecamatan' => 'Mijen',
                'kelurahan' => 'Mijen',
                'status' => 'diproses',
                'priority' => 'tinggi',
                'assigned_to' => 3,
            ],
            [
                'reporter_name' => 'Lukman Hakim',
                'reporter_phone' => '081234567012',
                'category' => 'sampah',
                'description' => 'Sampah medis ditemukan tercampur dengan sampah rumah tangga di TPS desa.',
                'latitude' => -6.9758,
                'longitude' => 110.6905,
                'address' => 'TPS Desa Kebonagung',
                'kecamatan' => 'Kebonagung',
                'kelurahan' => 'Kebonagung',
                'status' => 'pending',
                'priority' => 'darurat',
            ],
            [
                'reporter_name' => 'Maya Sari',
                'reporter_phone' => '081234567013',
                'category' => 'sampah',
                'description' => 'Sampah konstruksi dibuang sembarangan di lahan kosong milik desa. Sudah mulai ditumbuhi semak belukar.',
                'latitude' => -6.9194,
                'longitude' => 110.7022,
                'address' => 'Lahan kosong dekat Balai Desa Dempet',
                'kecamatan' => 'Dempet',
                'kelurahan' => 'Dempet',
                'status' => 'selesai',
                'priority' => 'rendah',
                'assigned_to' => 2,
            ],
            [
                'reporter_name' => 'Nurul Hidayah',
                'reporter_phone' => '081234567014',
                'category' => 'pencemaran_udara',
                'description' => 'Asap pabrik tahu menyebabkan polusi udara di pemukiman warga. Sudah dikeluhkan berkali-kali.',
                'latitude' => -6.9967,
                'longitude' => 110.6219,
                'address' => 'Jl. Industri, Wonosalam',
                'kecamatan' => 'Wonosalam',
                'kelurahan' => 'Wonosalam',
                'status' => 'diproses',
                'priority' => 'sedang',
                'assigned_to' => 3,
            ],
            [
                'reporter_name' => 'Oscar Pratama',
                'reporter_phone' => '081234567015',
                'category' => 'sampah',
                'description' => 'Sampah plastik mengapung di sungai dekat sekolah. Anak-anak sering bermain di area tersebut.',
                'latitude' => -6.8980,
                'longitude' => 110.6420,
                'address' => 'Sungai belakang SDN 1 Demak',
                'kecamatan' => 'Demak',
                'kelurahan' => 'Mangunjiwan',
                'status' => 'pending',
                'priority' => 'tinggi',
            ],
            [
                'reporter_name' => 'Putra Aditya',
                'reporter_phone' => '081234567016',
                'category' => 'pencemaran_air',
                'description' => 'Air irigasi sawah tercemar, sawah warga gagal panen karena air yang digunakan tidak layak.',
                'latitude' => -6.9300,
                'longitude' => 110.6700,
                'address' => 'Area persawahan Guntur Timur',
                'kecamatan' => 'Guntur',
                'kelurahan' => 'Trimulyo',
                'status' => 'pending',
                'priority' => 'darurat',
            ],
            [
                'reporter_name' => 'Rina Fitriani',
                'reporter_phone' => '081234567017',
                'category' => 'sampah',
                'description' => 'TPS di pasar Mranggen meluber. Truk sampah sudah 5 hari tidak datang untuk mengangkut.',
                'latitude' => -7.0180,
                'longitude' => 110.4900,
                'address' => 'Pasar Mranggen, Jl. Raya Semarang-Purwodadi',
                'kecamatan' => 'Mranggen',
                'kelurahan' => 'Mranggen',
                'status' => 'diproses',
                'priority' => 'tinggi',
                'assigned_to' => 2,
            ],
            [
                'reporter_name' => 'Surya Dharma',
                'reporter_phone' => '081234567018',
                'category' => 'lainnya',
                'description' => 'Drainase tersumbat sampah menyebabkan genangan air di jalan utama desa setiap hujan.',
                'latitude' => -6.8700,
                'longitude' => 110.7100,
                'address' => 'Jl. Utama Desa Bonang',
                'kecamatan' => 'Bonang',
                'kelurahan' => 'Purworejo',
                'status' => 'pending',
                'priority' => 'sedang',
            ],
            [
                'reporter_name' => 'Tri Wahyuni',
                'reporter_phone' => '081234567019',
                'category' => 'sampah',
                'description' => 'Sampah elektronik (e-waste) dibuang di tepi sawah. Berpotensi mencemari tanah pertanian.',
                'latitude' => -6.9600,
                'longitude' => 110.7500,
                'address' => 'Tepi Sawah Desa Bermi, Mijen',
                'kecamatan' => 'Mijen',
                'kelurahan' => 'Bermi',
                'status' => 'pending',
                'priority' => 'tinggi',
            ],
            [
                'reporter_name' => 'Umar Said',
                'reporter_phone' => '081234567020',
                'category' => 'pencemaran_air',
                'description' => 'Air sumur bor warga berasa asin dan berbau belerang. Diduga terpengaruh intrusi air laut.',
                'latitude' => -6.7800,
                'longitude' => 110.6800,
                'address' => 'Dusun Pesisir, Wedung',
                'kecamatan' => 'Wedung',
                'kelurahan' => 'Bungo',
                'status' => 'selesai',
                'priority' => 'sedang',
                'assigned_to' => 3,
            ],
        ];

        foreach ($reports as $index => $data) {
            $createdAt = Carbon::now()->subDays(rand(1, 90))->subHours(rand(1, 23));

            $today = Carbon::now()->format('Ymd');
            $ticketCode = "DLH-{$today}-" . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            $report = Report::create(array_merge($data, [
                'ticket_code' => $ticketCode,
                'photo_path' => 'uploads/reports/sample.jpg',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]));

            // Riwayat status awal
            ReportHistory::create([
                'report_id' => $report->id,
                'user_id' => null,
                'old_status' => null,
                'new_status' => 'pending',
                'note' => 'Laporan diterima dari warga.',
                'created_at' => $createdAt,
            ]);

            // Tambahkan riwayat lanjutan jika bukan pending
            if ($data['status'] !== 'pending') {
                ReportHistory::create([
                    'report_id' => $report->id,
                    'user_id' => $data['assigned_to'] ?? 1,
                    'old_status' => 'pending',
                    'new_status' => $data['status'] === 'selesai' ? 'diproses' : $data['status'],
                    'note' => 'Laporan sedang ditindaklanjuti oleh petugas.',
                    'created_at' => $createdAt->copy()->addDays(rand(1, 3)),
                ]);
            }

            if ($data['status'] === 'selesai') {
                $resolvedAt = $createdAt->copy()->addDays(rand(3, 7));
                $report->update([
                    'resolved_at' => $resolvedAt,
                    'resolved_note' => 'Masalah telah ditangani dan lokasi sudah dibersihkan.',
                    'resolved_photo' => 'uploads/resolved/sample.jpg',
                ]);

                ReportHistory::create([
                    'report_id' => $report->id,
                    'user_id' => $data['assigned_to'] ?? 1,
                    'old_status' => 'diproses',
                    'new_status' => 'selesai',
                    'note' => 'Penanganan selesai. Lokasi sudah bersih.',
                    'created_at' => $resolvedAt,
                ]);
            }
        }
    }
}
