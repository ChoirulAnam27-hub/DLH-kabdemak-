<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportLog;
use App\Models\ReportPhoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ReportService
{
    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Create a new report from public form
     */
    public function createReport(array $data, array $photos = []): Report
    {
        return DB::transaction(function () use ($data, $photos) {
            // Generate ticket
            $data['ticket_code'] = $this->ticketService->generateTicketCode();
            $data['status'] = 'pending';
            $data['sla_due_at'] = now()->addHours(24);
            
            // Create report
            $report = Report::create($data);

            // Save photos if any
            foreach ($photos as $photo) {
                $filename = 'reports/evidence/' . uniqid() . '.jpg';
                $image = Image::decode($photo->getRealPath());
                $image->scaleDown(width: 1280);
                Storage::disk('public')->put($filename, (string) $image->encodeUsingFormat(\Intervention\Image\Format::JPEG, 75));

                ReportPhoto::create([
                    'report_id' => $report->id,
                    'photo_path' => $filename,
                    'type' => 'bukti',
                ]);
            }

            // Create log
            ReportLog::create([
                'report_id' => $report->id,
                'user_id' => null,
                'action' => 'created',
                'description' => 'Laporan pengaduan dikirim oleh warga',
                'new_status' => 'pending',
            ]);

            return $report;
        });
    }

    /**
     * Update report status (admin/petugas)
     */
    public function updateStatus(Report $report, string $newStatus, ?int $userId = null, string $description = '', string $rejectionReason = ''): Report
    {
        $oldStatus = $report->status;
        
        DB::transaction(function () use ($report, $newStatus, $oldStatus, $userId, $description) {
            $report->status = $newStatus;
            
            if ($newStatus === 'selesai') {
                $report->resolved_at = now();
            }
            
            if ($newStatus === 'ditolak' && !empty($rejectionReason)) {
                $report->rejection_reason = $rejectionReason;
            }
            
            $report->save();

            ReportLog::create([
                'report_id' => $report->id,
                'user_id' => $userId,
                'action' => 'status_changed',
                'description' => $description ?: "Status diubah dari {$oldStatus} ke {$newStatus}",
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);
        });

        return $report;
    }

    /**
     * Assign report to petugas
     */
    public function assignPetugas(Report $report, int $petugasId, int $adminId): Report
    {
        DB::transaction(function () use ($report, $petugasId, $adminId) {
            $report->assigned_to = $petugasId;
            $report->assigned_at = now();
            // Optional: change status to diproses
            if ($report->status === 'pending') {
                $report->status = 'diproses';
            }
            $report->save();

            ReportLog::create([
                'report_id' => $report->id,
                'user_id' => $adminId,
                'action' => 'assigned',
                'description' => 'Laporan ditugaskan ke petugas lapangan',
            ]);
        });

        return $report;
    }

    /**
     * Add resolution photo (petugas/admin)
     */
    public function addResolutionPhoto(Report $report, $photo, ?int $userId = null, string $caption = ''): ReportPhoto
    {
        return DB::transaction(function () use ($report, $photo, $userId, $caption) {
            $filename = 'reports/resolution/' . uniqid() . '.jpg';
            $image = Image::decode($photo->getRealPath());
            $image->scaleDown(width: 1280);
            Storage::disk('public')->put($filename, (string) $image->encodeUsingFormat(\Intervention\Image\Format::JPEG, 75));
            $path = $filename;
            
            $reportPhoto = ReportPhoto::create([
                'report_id' => $report->id,
                'photo_path' => $path,
                'type' => 'penyelesaian',
                'caption' => $caption,
            ]);

            ReportLog::create([
                'report_id' => $report->id,
                'user_id' => $userId,
                'action' => 'photo_added',
                'description' => 'Foto bukti penyelesaian ditambahkan',
            ]);

            return $reportPhoto;
        });
    }

    /**
     * Mengecek adanya laporan ganda pada radius 100m dengan kategori sama dan belum selesai
     */
    public function checkDuplicate(float $lat, float $lng, int $categoryId): bool
    {
        $radius = 0.1; // 100 meter dalam kilometer
        $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

        return Report::where('category_id', $categoryId)
            ->whereIn('status', ['pending', 'diproses'])
            ->whereRaw("{$haversine} < ?", [$lat, $lng, $lat, $radius])
            ->exists();
    }
}
