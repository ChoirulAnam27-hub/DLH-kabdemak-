<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\ReportLog;
use App\Models\ReportPhoto;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Seeder: Dummy Laporan Pengaduan
 * 
 * 20 laporan tersebar di 13 kecamatan Kabupaten Demak
 * dengan koordinat GPS nyata dan variasi status.
 */
class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $reports = [
            // === KECAMATAN DEMAK (Kota) ===
            [
                'reporter_name' => 'Hasan Mubarok',
                'reporter_phone' => '08131000001',
                'category_id' => 1,
                'description' => 'Sampah menumpuk di depan Pasar Bintoro sudah 3 hari tidak diangkut. Bau sangat menyengat dan mengganggu pedagang.',
                'latitude' => -6.8936,
                'longitude' => 110.6382,
                'address' => 'Depan Pasar Bintoro, Jl. Sultan Fatah',
                'kelurahan' => 'Bintoro',
                'kecamatan' => 'Demak',
                'status' => 'selesai',
                'priority' => 'tinggi',
                'assigned_to' => 2,
                'days_ago' => 15,
            ],
            [
                'reporter_name' => 'Sri Wahyuni',
                'reporter_phone' => '08131000002',
                'category_id' => 1,
                'description' => 'TPS di Jl. Bhayangkara meluap, sampah berserakan sampai ke badan jalan.',
                'latitude' => -6.8912,
                'longitude' => 110.6401,
                'address' => 'TPS Jl. Bhayangkara, dekat Masjid Agung Demak',
                'kelurahan' => 'Kadilangu',
                'kecamatan' => 'Demak',
                'status' => 'diproses',
                'priority' => 'tinggi',
                'assigned_to' => 3,
                'days_ago' => 3,
            ],
            // === KECAMATAN SAYUNG ===
            [
                'reporter_name' => 'Muhammad Rizky',
                'reporter_phone' => '08131000003',
                'category_id' => 2,
                'description' => 'Air sungai di Sayung berubah warna hitam dan berbau busuk. Diduga ada pembuangan limbah dari pabrik.',
                'latitude' => -6.9234,
                'longitude' => 110.5891,
                'address' => 'Sungai Dombo, Kec. Sayung',
                'kelurahan' => 'Sayung',
                'kecamatan' => 'Sayung',
                'status' => 'diproses',
                'priority' => 'darurat',
                'assigned_to' => 2,
                'days_ago' => 5,
            ],
            [
                'reporter_name' => 'Dewi Kartika',
                'reporter_phone' => '08131000004',
                'category_id' => 1,
                'description' => 'Sampah plastik menumpuk di area tambak warga. Sangat banyak dan mencemari tambak.',
                'latitude' => -6.9356,
                'longitude' => 110.5743,
                'address' => 'Area Tambak Desa Bedono',
                'kelurahan' => 'Bedono',
                'kecamatan' => 'Sayung',
                'status' => 'pending',
                'priority' => 'sedang',
                'assigned_to' => null,
                'days_ago' => 1,
            ],
            // === KECAMATAN KARANGAWEN ===
            [
                'reporter_name' => 'Agus Prayitno',
                'reporter_phone' => '08131000005',
                'category_id' => 3,
                'description' => 'Asap hitam tebal dari pabrik batu bata menyebabkan polusi udara di sekitar perumahan warga.',
                'latitude' => -7.0312,
                'longitude' => 110.5967,
                'address' => 'Desa Pundenarum, dekat pabrik batu bata',
                'kelurahan' => 'Pundenarum',
                'kecamatan' => 'Karangawen',
                'status' => 'pending',
                'priority' => 'tinggi',
                'assigned_to' => null,
                'days_ago' => 2,
            ],
            // === KECAMATAN MRANGGEN ===
            [
                'reporter_name' => 'Nurul Hidayah',
                'reporter_phone' => '08131000006',
                'category_id' => 1,
                'description' => 'Tumpukan sampah besar di pinggir jalan raya Mranggen-Semarang. Sudah seminggu tidak ada pengangkutan.',
                'latitude' => -7.0189,
                'longitude' => 110.5123,
                'address' => 'Jl. Raya Mranggen, depan terminal',
                'kelurahan' => 'Mranggen',
                'kecamatan' => 'Mranggen',
                'status' => 'selesai',
                'priority' => 'sedang',
                'assigned_to' => 4,
                'days_ago' => 20,
            ],
            [
                'reporter_name' => 'Rudi Hermawan',
                'reporter_phone' => '08131000007',
                'category_id' => 4,
                'description' => 'Ditemukan drum-drum bekas berisi cairan kimia berbau tajam di lahan kosong dekat pemukiman.',
                'latitude' => -7.0245,
                'longitude' => 110.5201,
                'address' => 'Lahan kosong Desa Batursari',
                'kelurahan' => 'Batursari',
                'kecamatan' => 'Mranggen',
                'status' => 'diproses',
                'priority' => 'darurat',
                'assigned_to' => 2,
                'days_ago' => 4,
            ],
            // === KECAMATAN GUNTUR ===
            [
                'reporter_name' => 'Sulastri',
                'reporter_phone' => '08131000008',
                'category_id' => 1,
                'description' => 'Sampah rumah tangga dibuang sembarangan di tepi sawah. Sudah jadi tempat pembuangan liar.',
                'latitude' => -6.9178,
                'longitude' => 110.6789,
                'address' => 'Tepi sawah Desa Guntur',
                'kelurahan' => 'Guntur',
                'kecamatan' => 'Guntur',
                'status' => 'pending',
                'priority' => 'sedang',
                'assigned_to' => null,
                'days_ago' => 1,
            ],
            // === KECAMATAN WONOSALAM ===
            [
                'reporter_name' => 'Eko Prasetyo',
                'reporter_phone' => '08131000009',
                'category_id' => 2,
                'description' => 'Limbah pabrik tahu dibuang langsung ke sungai. Air sungai berubah keruh dan berbau asam.',
                'latitude' => -6.9456,
                'longitude' => 110.6123,
                'address' => 'Sungai dekat pabrik tahu Desa Wonosalam',
                'kelurahan' => 'Wonosalam',
                'kecamatan' => 'Wonosalam',
                'status' => 'selesai',
                'priority' => 'tinggi',
                'assigned_to' => 3,
                'days_ago' => 30,
            ],
            // === KECAMATAN DEMPET ===
            [
                'reporter_name' => 'Bambang Sutrisno',
                'reporter_phone' => '08131000010',
                'category_id' => 1,
                'description' => 'Sampah pasar mingguan tidak dibersihkan. Menumpuk di sekitar area pasar Dempet.',
                'latitude' => -6.8867,
                'longitude' => 110.6945,
                'address' => 'Area Pasar Dempet',
                'kelurahan' => 'Dempet',
                'kecamatan' => 'Dempet',
                'status' => 'pending',
                'priority' => 'rendah',
                'assigned_to' => null,
                'days_ago' => 0,
            ],
            // === KECAMATAN GAJAH ===
            [
                'reporter_name' => 'Fatimah Azzahra',
                'reporter_phone' => '08131000011',
                'category_id' => 3,
                'description' => 'Warga membakar sampah setiap sore hari. Asap mengganggu pernafasan anak-anak di sekitar.',
                'latitude' => -6.8523,
                'longitude' => 110.7123,
                'address' => 'RT 03 RW 02, Desa Gajah',
                'kelurahan' => 'Gajah',
                'kecamatan' => 'Gajah',
                'status' => 'diproses',
                'priority' => 'sedang',
                'assigned_to' => 4,
                'days_ago' => 7,
            ],
            // === KECAMATAN KARANGTENGAH ===
            [
                'reporter_name' => 'Yusuf Maulana',
                'reporter_phone' => '08131000012',
                'category_id' => 1,
                'description' => 'Sampah menumpuk di gorong-gorong menyebabkan banjir saat hujan di area perumahan.',
                'latitude' => -6.8712,
                'longitude' => 110.6012,
                'address' => 'Perumahan Griya Karangtengah Indah',
                'kelurahan' => 'Karangtengah',
                'kecamatan' => 'Karangtengah',
                'status' => 'selesai',
                'priority' => 'tinggi',
                'assigned_to' => 2,
                'days_ago' => 25,
            ],
            // === KECAMATAN BONANG ===
            [
                'reporter_name' => 'Indah Permatasari',
                'reporter_phone' => '08131000013',
                'category_id' => 2,
                'description' => 'Air sumur warga berubah warna kecoklatan dan berbau. Diduga tercemar limbah peternakan.',
                'latitude' => -6.8345,
                'longitude' => 110.6567,
                'address' => 'Desa Jatirogo, Kec. Bonang',
                'kelurahan' => 'Jatirogo',
                'kecamatan' => 'Bonang',
                'status' => 'pending',
                'priority' => 'tinggi',
                'assigned_to' => null,
                'days_ago' => 1,
            ],
            // === KECAMATAN WEDUNG ===
            [
                'reporter_name' => 'Supardi',
                'reporter_phone' => '08131000014',
                'category_id' => 1,
                'description' => 'Sampah kiriman dari laut menumpuk di pantai Wedung. Sangat banyak plastik dan styrofoam.',
                'latitude' => -6.7789,
                'longitude' => 110.6234,
                'address' => 'Pantai Desa Berahan Kulon',
                'kelurahan' => 'Berahan Kulon',
                'kecamatan' => 'Wedung',
                'status' => 'pending',
                'priority' => 'sedang',
                'assigned_to' => null,
                'days_ago' => 2,
            ],
            [
                'reporter_name' => 'Kusuma Dewi',
                'reporter_phone' => '08131000015',
                'category_id' => 5,
                'description' => 'Penebangan pohon bakau secara liar di area mangrove Wedung. Perlu penanganan segera.',
                'latitude' => -6.7856,
                'longitude' => 110.6178,
                'address' => 'Area Mangrove Desa Kedung Mutih',
                'kelurahan' => 'Kedung Mutih',
                'kecamatan' => 'Wedung',
                'status' => 'diproses',
                'priority' => 'darurat',
                'assigned_to' => 3,
                'days_ago' => 6,
            ],
            // === KECAMATAN KEBONAGUNG ===
            [
                'reporter_name' => 'Wahyu Nugroho',
                'reporter_phone' => '08131000016',
                'category_id' => 1,
                'description' => 'Container sampah di Kebonagung sudah penuh meluap. Sampah berserakan ke jalan.',
                'latitude' => -6.9023,
                'longitude' => 110.6678,
                'address' => 'Jl. Raya Kebonagung, dekat balai desa',
                'kelurahan' => 'Kebonagung',
                'kecamatan' => 'Kebonagung',
                'status' => 'selesai',
                'priority' => 'sedang',
                'assigned_to' => 4,
                'days_ago' => 12,
            ],
            // === KECAMATAN MIJEN ===
            [
                'reporter_name' => 'Ratna Sari',
                'reporter_phone' => '08131000017',
                'category_id' => 2,
                'description' => 'Saluran irigasi tercemar limbah rumah tangga. Air sawah di sekitar ikut tercemar.',
                'latitude' => -6.9534,
                'longitude' => 110.7234,
                'address' => 'Saluran irigasi Desa Mijen',
                'kelurahan' => 'Mijen',
                'kecamatan' => 'Mijen',
                'status' => 'pending',
                'priority' => 'sedang',
                'assigned_to' => null,
                'days_ago' => 0,
            ],
            // === TAMBAHAN LAPORAN ===
            [
                'reporter_name' => 'Dian Purnomo',
                'reporter_phone' => '08131000018',
                'category_id' => 4,
                'description' => 'Terdapat tumpahan oli bekas di lahan kosong dekat sungai. Warga khawatir mencemari sumber air.',
                'latitude' => -6.9001,
                'longitude' => 110.6501,
                'address' => 'Lahan kosong dekat sungai, Kel. Mangunjiwan',
                'kelurahan' => 'Mangunjiwan',
                'kecamatan' => 'Demak',
                'status' => 'pending',
                'priority' => 'tinggi',
                'assigned_to' => null,
                'days_ago' => 1,
            ],
            [
                'reporter_name' => 'Lina Marlina',
                'reporter_phone' => '08131000019',
                'category_id' => 1,
                'description' => 'Sampah menumpuk di bantaran Sungai Tuntang, Sayung. Menutupi aliran air dan berpotensi banjir.',
                'latitude' => -6.9289,
                'longitude' => 110.5812,
                'address' => 'Bantaran Sungai Tuntang, Desa Tugu',
                'kelurahan' => 'Tugu',
                'kecamatan' => 'Sayung',
                'status' => 'diproses',
                'priority' => 'tinggi',
                'assigned_to' => 4,
                'days_ago' => 8,
            ],
            [
                'reporter_name' => 'Hendro Gunawan',
                'reporter_phone' => '08131000020',
                'category_id' => 3,
                'description' => 'Bau menyengat dari tempat pengolahan ikan asin yang tidak memiliki pengelolaan limbah.',
                'latitude' => -6.8623,
                'longitude' => 110.6089,
                'address' => 'Sentra ikan asin, Desa Karangsari',
                'kelurahan' => 'Karangsari',
                'kecamatan' => 'Karangtengah',
                'status' => 'selesai',
                'priority' => 'sedang',
                'assigned_to' => 3,
                'days_ago' => 18,
            ],
        ];

        foreach ($reports as $index => $data) {
            $daysAgo = $data['days_ago'];
            unset($data['days_ago']);

            // Generate ticket code: DLH-YYYYMMDD-XXX
            $date = Carbon::now()->subDays($daysAgo);
            $data['ticket_code'] = 'DLH-' . $date->format('Ymd') . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $data['created_at'] = $date;
            $data['updated_at'] = $date;

            // Set resolved_at untuk status selesai
            if ($data['status'] === 'selesai') {
                $data['resolved_at'] = $date->copy()->addDays(rand(1, 3));
            }

            // Set assigned_at untuk yang sudah ditugaskan
            if ($data['assigned_to']) {
                $data['assigned_at'] = $date->copy()->addHours(rand(1, 12));
            }

            $report = Report::create($data);

            // Buat log entry awal
            ReportLog::create([
                'report_id' => $report->id,
                'user_id' => null,
                'action' => 'created',
                'description' => 'Laporan pengaduan dikirim oleh ' . $report->reporter_name,
                'new_status' => 'pending',
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Tambah log untuk yang sudah diproses/selesai
            if (in_array($data['status'], ['diproses', 'selesai'])) {
                ReportLog::create([
                    'report_id' => $report->id,
                    'user_id' => 1, // admin
                    'action' => 'status_changed',
                    'description' => 'Status diubah menjadi Diproses',
                    'old_status' => 'pending',
                    'new_status' => 'diproses',
                    'created_at' => $date->copy()->addHours(rand(2, 24)),
                    'updated_at' => $date->copy()->addHours(rand(2, 24)),
                ]);
            }

            if ($data['status'] === 'selesai') {
                ReportLog::create([
                    'report_id' => $report->id,
                    'user_id' => $data['assigned_to'],
                    'action' => 'resolved',
                    'description' => 'Laporan selesai ditangani oleh petugas lapangan',
                    'old_status' => 'diproses',
                    'new_status' => 'selesai',
                    'created_at' => $data['resolved_at'],
                    'updated_at' => $data['resolved_at'],
                ]);
            // Tambah foto bukti untuk laporan
            $existingPhotos = \Illuminate\Support\Facades\Storage::disk('public')->files('reports/evidence');
            if (!empty($existingPhotos)) {
                $photoPath = $existingPhotos[$index % count($existingPhotos)];
                ReportPhoto::create([
                    'report_id' => $report->id,
                    'photo_path' => $photoPath,
                    'type' => 'bukti',
                    'caption' => 'Foto Bukti Pengaduan',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
