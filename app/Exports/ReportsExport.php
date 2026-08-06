<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Report::with(['category', 'assignedUser'])->orderBy('created_at', 'desc');

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['bulan']) && !empty($this->filters['tahun'])) {
            $query->whereMonth('created_at', $this->filters['bulan'])
                  ->whereYear('created_at', $this->filters['tahun']);
        }
        if (!empty($this->filters['kecamatan_id'])) {
            $query->byKecamatanId($this->filters['kecamatan_id']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No. Tiket',
            'Tanggal Lapor',
            'Nama Pelapor',
            'No. WhatsApp',
            'Kategori',
            'Kecamatan',
            'Desa/Kelurahan',
            'Alamat Detail',
            'Deskripsi',
            'Status',
            'Petugas Ditugaskan'
        ];
    }

    public function map($report): array
    {
        return [
            $report->ticket_code,
            $report->created_at->format('d/m/Y H:i'),
            $report->reporter_name,
            $report->reporter_phone,
            $report->category->name,
            $report->kecamatan,
            $report->kelurahan ?? '-',
            $report->address,
            $report->description,
            ucfirst($report->status),
            $report->assignedUser ? $report->assignedUser->name : 'Belum Ditugaskan'
        ];
    }
}
