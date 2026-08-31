<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Laporan Pengaduan - DLH Demak</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0 0 5px 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 0; font-size: 12px; }
        .logo { position: absolute; left: 20px; top: 10px; width: 60px; }
        .title { text-align: center; font-size: 14px; font-weight: bold; margin: 20px 0; }
        .info { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 50px; text-align: center; font-size: 9px; border-top: 1px solid #ccc; padding-top: 10px;}
        .page-number:before { content: "Halaman " counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <h1>DINAS LINGKUNGAN HIDUP KABUPATEN DEMAK</h1>
        <p>Jl. Bhayangkara Baru 1 59511, Demak, Jawa Tengah</p>
        <p>Telp: (0291) 685677 | Email: dinlh.demakkab.go.id</p>
    </div>

    <div class="title">
        REKAPITULASI LAPORAN PENGADUAN LINGKUNGAN
    </div>

    <div class="info">
        <table style="width: auto; border: none; margin-bottom: 0;">
            <tr><td style="border: none; padding: 2px 10px 2px 0;"><strong>Tanggal Cetak</strong></td><td style="border: none; padding: 2px;">: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</td></tr>
            <tr><td style="border: none; padding: 2px 10px 2px 0;"><strong>Status Filter</strong></td><td style="border: none; padding: 2px;">: {{ !empty($filters['status']) ? ucfirst($filters['status']) : 'Semua Status' }}</td></tr>
            @if(!empty($filters['kecamatan_name']))
            <tr><td style="border: none; padding: 2px 10px 2px 0;"><strong>Kecamatan</strong></td><td style="border: none; padding: 2px;">: {{ $filters['kecamatan_name'] }}</td></tr>
            @endif
            <tr><td style="border: none; padding: 2px 10px 2px 0;"><strong>Total Data</strong></td><td style="border: none; padding: 2px;">: {{ $reports->count() }} Laporan</td></tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="12%">No. Tiket</th>
                <th width="12%">Tgl Lapor</th>
                <th width="15%">Pelapor</th>
                <th width="12%">Kategori</th>
                <th width="20%">Lokasi (Kecamatan)</th>
                <th width="14%">Status</th>
                <th width="12%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $report)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $report->ticket_code }}</td>
                <td>{{ $report->created_at->format('d/m/Y') }}</td>
                <td>{{ $report->reporter_name }}<br><small>{{ $report->reporter_phone }}</small></td>
                <td>{{ $report->category->name }}</td>
                <td>{{ $report->kecamatan }}</td>
                <td>{{ $report->status_label }}</td>
                <td>{{ $report->assignedUser ? $report->assignedUser->name : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data laporan untuk kriteria tersebut.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="width: 100%; margin-top: 50px;">
        <div style="float: right; width: 250px; text-align: center;">
            <p>Demak, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <br><br><br>
            <p><strong>Admin DLH Demak</strong></p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        Dicetak dari Sistem Pengaduan Lingkungan DLH Kab. Demak | <span class="page-number"></span>
    </div>
</body>
</html>
