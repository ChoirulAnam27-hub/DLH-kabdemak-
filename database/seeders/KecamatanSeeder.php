<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatanDesas = [
            'Bonang' => [
                'Betokan', 'Bonangrejo', 'Gebang', 'Gebangarum', 'Jali', 'Jatimulyo', 'Jatirogo', 'Karangrejo', 'Kembangan', 'Krajanbogo', 'Margolinduk', 'Moro Demak', 'Poncoharjo', 'Prampelan', 'Purworejo', 'Serangan', 'Sukodono', 'Sumberejo', 'Tlogoboyo', 'Tridonorejo', 'Weding'
            ],
            'Demak' => [
                'Bintoro', 'Bango', 'Betokan', 'Cabean', 'Donorejo', 'Kadilangu', 'Kalikondang', 'Kalisusuh', 'Karangmlati', 'Katonsari', 'Kedondong', 'Mangunjiwan', 'Mulyorejo', 'Pemuda', 'Rajawali', 'Sedan', 'Singorejo', 'Syam'
            ],
            'Dempet' => [
                'Balerejo', 'Botosengon', 'Brakas', 'Campurejo', 'Dempet', 'Gempoldenok', 'Harjowinangun', 'Jerukgulung', 'Karangrejo', 'Kedungori', 'Kramat', 'Kupon', 'Mergourip', 'Mergosari', 'Ngemplak', 'Sidomulyo'
            ],
            'Gajah' => [
                'Banjarsari', 'Boya', 'Gajah', 'Gedangalas', 'Jatisono', 'Kedondong', 'Medini', 'Mlatiharjo', 'Mlekang', 'Mojosimo', 'Ngaluran', 'Sari', 'Surodadi', 'Tambo', 'Tanjunganyar'
            ],
            'Guntur' => [
                'Bakalrejo', 'Banjarejo', 'Bantengmati', 'Bogosari', 'Bumiayu', 'Gaji', 'Guntur', 'Krangon', 'Kuwu', 'Pamongan', 'Sarirejo', 'Sidokumpul', 'Sukorejo', 'Tangkis', 'Temuroso', 'Tlogorejo', 'Tlogoweru', 'Trimulyo', 'Turanrejo', 'Wonorejo'
            ],
            'Karanganyar' => [
                'Bandungrejo', 'Cangkring', 'Cangkring Pos', 'Jatirejo', 'Karanganyar', 'Kedungwaru Kidul', 'Kedungwaru Lor', 'Ketanjung', 'Kotaanyar', 'Ngaluran', 'Ngemplik Wetan', 'Tuwang', 'Undaan Kidul', 'Undaan Lor', 'Wonorejo'
            ],
            'Karangawen' => [
                'Brambang', 'Bumirejo', 'Jragung', 'Karangawen', 'Kuripan', 'Margohayu', 'Pundenarum', 'Rejosari', 'Sido Mulyo', 'Teluk', 'Tlogorejo', 'Wonosekar'
            ],
            'Karangtengah' => [
                'Batu', 'Buyaran', 'Dukun', 'Grogol', 'Karangsari', 'Karangtowo', 'Kedunguter', 'Klitih', 'Mranak', 'Pitu', 'Ploso', 'Pidodo', 'Rejosari', 'Sampangan', 'Tambakbulusan', 'Wonokerto', 'Wonoagung'
            ],
            'Kebonagung' => [
                'Baleromo', 'Banjarejo', 'Bunderan', 'Klampoklor', 'Klampoksari', 'Krapyak', 'Mangunanlor', 'Mangunansari', 'Mijen', 'Pilanggwet', 'Prigi', 'Pucanggading', 'Sarimulyo', 'Soko', 'Solowire'
            ],
            'Mijen' => [
                'Bakalmacem', 'Bantengmati', 'Bermi', 'Gempolsongo', 'Geneng', 'Jleper', 'Mijen', 'Mlaten', 'Ngegot', 'Ngelo Wetan', 'Pasir', 'Pecuk', 'Rejosari', 'Sanggawang', 'Tanggul'
            ],
            'Mranggen' => [
                'Banjarejo', 'Bandungrejo', 'Batorejo', 'Brumbung', 'Candisari', 'Jambe', 'Kalitengah', 'Kangkung', 'Karangsono', 'Kebonbatur', 'Kembangarum', 'Menur', 'Mranggen', 'Ngemplak', 'Sumberejo', 'Tamansari', 'Tegalarum', 'Wringinjajar'
            ],
            'Sayung' => [
                'Banjarsari', 'Bedomono', 'Bulusari', 'Dombo', 'Gemulak', 'Jetiksari', 'Kalisari', 'Karangasem', 'Kendalmati', 'Loireng', 'Prampelan', 'Purwosari', 'Sayung', 'Sidogemah', 'Sidorejo', 'Sriwulan', 'Surodadi', 'Tambakroto', 'Timbulsloko', 'Tugu'
            ],
            'Wedung' => [
                'Babo', 'Banjarmelati', 'Buko', 'Bungoh', 'Jetak', 'Jungpasir', 'Jungsemi', 'Kedungkarang', 'Kedungmutih', 'Kendaldoyong', 'Kendalmati', 'Mandung', 'Mutih Kulon', 'Mutih Wetan', 'Ngawen', 'Ruwit', 'Tedunan', 'Tempel', 'Wedung'
            ],
            'Wonosalam' => [
                'Blerong', 'Botorejo', 'Doreng', 'Getas', 'Jogoloyo', 'Kalianyar', 'Karangrejo', 'Kendal Doyong', 'Kerangkulon', 'Lempuyang', 'Mranggen', 'Mrisen', 'Pilkangceng', 'Pulosari', 'Sido Mulyo', 'Tlogodowo', 'Trengguli', 'Wonosalam'
            ]
        ];

        foreach ($kecamatanDesas as $kecName => $desas) {
            $kecamatan = Kecamatan::create(['name' => $kecName]);
            
            foreach ($desas as $desaName) {
                $kecamatan->desas()->create(['name' => $desaName]);
            }
        }
    }
}
