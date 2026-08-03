<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

/**
 * Seeder WilayahDemak — Data 14 kecamatan dan desa/kelurahan di Kabupaten Demak.
 * Koordinat latitude/longitude adalah titik pusat (centroid) masing-masing kecamatan.
 */
class WilayahDemakSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Demak',
                'lat' => -6.8936,
                'lng' => 110.6381,
                'desa' => ['Bintoro', 'Betokan', 'Kadilangu', 'Katonsari', 'Mangunjiwan', 'Mulyorejo', 'Singorejo', 'Kalikondang', 'Cabean', 'Donorejo', 'Turirejo', 'Sedo', 'Raji'],
            ],
            [
                'name' => 'Sayung',
                'lat' => -6.9147,
                'lng' => 110.4833,
                'desa' => ['Sayung', 'Gemulak', 'Bedono', 'Tugu', 'Sidogemah', 'Purwosari', 'Loireng', 'Karangasem', 'Jetaksari', 'Dombo', 'Bulusan', 'Sriwulan', 'Sidorejo', 'Prampelan', 'Pilangsari', 'Perampelan', 'Banjarsari', 'Timbulsloko', 'Surodadi', 'Kalisari'],
            ],
            [
                'name' => 'Karangtengah',
                'lat' => -6.9015,
                'lng' => 110.5797,
                'desa' => ['Karangtengah', 'Wonowoso', 'Dukun', 'Ploso', 'Pidodo', 'Klitih', 'Donorojo', 'Grogol', 'Tambakbulusan', 'Curug', 'Batu', 'Kedondong'],
            ],
            [
                'name' => 'Guntur',
                'lat' => -6.9463,
                'lng' => 110.6595,
                'desa' => ['Guntur', 'Blerong', 'Tlogoweru', 'Trimulyo', 'Sidoharjo', 'Temuroso', 'Krandon', 'Tangkis', 'Pamongan', 'Bakalrejo', 'Sumberejo', 'Bogosari', 'Sarirejo', 'Tlogopandogan'],
            ],
            [
                'name' => 'Mranggen',
                'lat' => -7.0228,
                'lng' => 110.4853,
                'desa' => ['Mranggen', 'Bandungrejo', 'Brumbung', 'Batursari', 'Kangkung', 'Kebonbatur', 'Kembangarum', 'Menur', 'Ngemplak', 'Banyumeneng', 'Kalitengah', 'Karangsono', 'Sumberejo', 'Tamansari', 'Tegalarum', 'Wringinjajar', 'Candisari', 'Kemiri', 'Sayung'],
            ],
            [
                'name' => 'Karangawen',
                'lat' => -7.0456,
                'lng' => 110.5384,
                'desa' => ['Karangawen', 'Bumirejo', 'Jragung', 'Kuripan', 'Margohayu', 'Pundenarum', 'Rejosari', 'Sidorejo', 'Tlogorejo', 'Telukawur', 'Wonosekar', 'Brambang'],
            ],
            [
                'name' => 'Bonang',
                'lat' => -6.8502,
                'lng' => 110.6977,
                'desa' => ['Bonang', 'Betahwalang', 'Karangrejo', 'Morodemak', 'Purworejo', 'Senik', 'Serangan', 'Jatimulyo', 'Krajanbogo', 'Gebangarum', 'Kembangan', 'Tridonorejo', 'Tawangrejo', 'Jali', 'Margolinduk', 'Gebang', 'Wonokerto'],
            ],
            [
                'name' => 'Wedung',
                'lat' => -6.7758,
                'lng' => 110.6672,
                'desa' => ['Wedung', 'Bungo', 'Jetak', 'Kenduren', 'Mandung', 'Mutih Kulon', 'Mutih Wetan', 'Ngawen', 'Ruwit', 'Tedunan', 'Berahan Kulon', 'Berahan Wetan', 'Jungsemi', 'Kedungmutih', 'Kendalasem', 'Menco', 'Pentangan', 'Salamsari', 'Tempel', 'Rawit'],
            ],
            [
                'name' => 'Gajah',
                'lat' => -6.9095,
                'lng' => 110.7308,
                'desa' => ['Gajah', 'Boyolali', 'Gedangalas', 'Jatisono', 'Medani', 'Mlatiharjo', 'Sambiroto', 'Surodadi', 'Tanjunganom', 'Wilalung', 'Mojosimo', 'Tanggul', 'Sumber', 'Karangliman'],
            ],
            [
                'name' => 'Karanganyar',
                'lat' => -6.8630,
                'lng' => 110.7520,
                'desa' => ['Karanganyar', 'Dempet', 'Donorejo', 'Jerukgulung', 'Kedungwaru', 'Ketanjung', 'Klitih', 'Kuwu', 'Pilangsari', 'Sidomulyo', 'Wonoketingal', 'Batursari'],
            ],
            [
                'name' => 'Mijen',
                'lat' => -6.9544,
                'lng' => 110.7402,
                'desa' => ['Mijen', 'Bermi', 'Geneng', 'Jleper', 'Mlatiharjo', 'Ngegot', 'Pasir', 'Pecuk', 'Rejosari', 'Tanggul', 'Gempolsari', 'Bantengmati'],
            ],
            [
                'name' => 'Kebonagung',
                'lat' => -6.9758,
                'lng' => 110.6905,
                'desa' => ['Kebonagung', 'Banjarsari', 'Jatirejo', 'Karangrejo', 'Kedungmulyo', 'Klitih', 'Mrisen', 'Prigi', 'Sedo', 'Sidodadi', 'Tlogoboyo', 'Pilangwetan'],
            ],
            [
                'name' => 'Dempet',
                'lat' => -6.9194,
                'lng' => 110.7022,
                'desa' => ['Dempet', 'Balerejo', 'Brakas', 'Botorejo', 'Donorejo', 'Godo', 'Harjowinangun', 'Jerukgulung', 'Karanganyar', 'Kebonsari', 'Kunir', 'Merak', 'Sidomulyo', 'Sokareja'],
            ],
            [
                'name' => 'Wonosalam',
                'lat' => -6.9967,
                'lng' => 110.6219,
                'desa' => ['Wonosalam', 'Bunderan', 'Getas', 'Jogoloyo', 'Karangrejo', 'Kuncir', 'Lempuyang', 'Mranak', 'Pilangrejo', 'Siwal', 'Trengguli', 'Kendaldoyong', 'Mojodemak', 'Doreng', 'Botosengon', 'Kalianyar', 'Karangkumpul', 'Mrisen', 'Kerangkulon'],
            ],
        ];

        foreach ($data as $kec) {
            $kecamatan = Kecamatan::create([
                'name' => $kec['name'],
                'latitude' => $kec['lat'],
                'longitude' => $kec['lng'],
            ]);

            foreach ($kec['desa'] as $desa) {
                Kelurahan::create([
                    'kecamatan_id' => $kecamatan->id,
                    'name' => $desa,
                ]);
            }
        }
    }
}
