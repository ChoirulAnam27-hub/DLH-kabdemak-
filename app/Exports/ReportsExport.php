<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Export ReportsExport — Mengekspor data laporan ke file Excel (.xlsx).
 * Digunakan untuk rekapitulasi bulanan DLH Demak.
 */
class ReportsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Report::with('assignedUser');

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }
        if ($this->request->filled('date_from') && $this->request->filled('date_to')) {
            $query->whereBetween('created_at', [
                $this->request->date_from,
                $this->request->date_to . ' 23:59:59'
            ]);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Tiket',
            'Tanggal Laporan',
            'Nama Pelapor',
            'No. WhatsApp',
            'Kategori',
            'Deskripsi',
            'Alamat',
            'Kecamatan',
            'Kelurahan',
            'Latitude',
            'Longitude',
            'Status',
            'Prioritas',
            'Petugas',
            'Tanggal Selesai',
        ];
    }

    public function map($report): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $report->ticket_code,
            $report->created_at->format('d/m/Y H:i'),
            $report->reporter_name,
            $report->reporter_phone,
            $report->category_label,
            $report->description,
            $report->address,
            $report->kecamatan,
            $report->kelurahan,
            $report->latitude,
            $report->longitude,
            $report->status_label,
            ucfirst($report->priority),
            $report->assignedUser?->name ?? '-',
            $report->resolved_at?->format('d/m/Y H:i') ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
