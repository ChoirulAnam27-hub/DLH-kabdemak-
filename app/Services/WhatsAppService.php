<?php

namespace App\Services;

use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiUrl;
    protected string $token;
    protected bool $enabled;

    public function __construct()
    {
        $this->apiUrl = env('WHATSAPP_API_URL', 'https://api.fonnte.com/send');
        $this->token = env('WHATSAPP_TOKEN', '');
        $this->enabled = !empty($this->token);
    }

    /**
     * Kirim pesan WhatsApp melalui API (Fonnte)
     */
    protected function sendMessage(string $target, string $message): void
    {
        if (!$this->enabled) {
            Log::info("WhatsApp log (Token unset) to {$target}: {$message}");
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->post($this->apiUrl, [
                'target' => $target,
                'message' => $message,
                'delay' => '1',
            ]);

            if (!$response->successful()) {
                Log::error("Failed to send WhatsApp message: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp service exception: " . $e->getMessage());
        }
    }

    /**
     * Notifikasi saat tiket baru dibuat
     */
    public function sendTicketCreated(Report $report): void
    {
        if (empty($report->reporter_phone)) return;
        
        $phone = $this->formatPhone($report->reporter_phone);
        
        $message = "Halo *{$report->display_reporter_name}*,\n\n";
        $message .= "Terima kasih telah berpartisipasi menjaga lingkungan Demak. Laporan Anda telah kami terima dengan detail berikut:\n\n";
        $message .= "🎫 *No. Tiket*: {$report->ticket_code}\n";
        $message .= "🗂️ *Kategori*: {$report->category->name}\n";
        $message .= "📍 *Lokasi*: {$report->kecamatan}\n";
        $message .= "⚠️ *Status*: Menunggu Verifikasi\n\n";
        $message .= "Anda dapat melacak progres laporan Anda melalui link berikut:\n";
        $message .= route('public.track.show', $report->ticket_code) . "\n\n";
        $message .= "Sistem Pengaduan DLH Kabupaten Demak.";

        $this->sendMessage($phone, $message);
    }

    /**
     * Notifikasi saat laporan ditugaskan ke petugas
     */
    public function sendAssigned(Report $report, User $petugas): void
    {
        if (empty($petugas->phone)) return;

        $phone = $this->formatPhone($petugas->phone);

        $message = "Halo Petugas *{$petugas->name}*,\n\n";
        $message .= "Anda mendapat *tugas baru* untuk menangani laporan:\n\n";
        $message .= "🎫 *No. Tiket*: {$report->ticket_code}\n";
        $message .= "🗂️ *Kategori*: {$report->category->name}\n";
        $message .= "📍 *Lokasi*: {$report->address}, Kec. {$report->kecamatan}\n";
        $message .= "🚨 *SLA*: " . ($report->sla_due_at ? $report->sla_due_at->format('d/m/Y H:i') : '-') . "\n\n";
        $message .= "Harap segera menindaklanjuti laporan tersebut dan mengunggah foto bukti penyelesaian.\n";
        $message .= route('admin.reports.show', $report->id);

        $this->sendMessage($phone, $message);
    }

    /**
     * Notifikasi saat laporan selesai ditangani
     */
    public function sendResolved(Report $report): void
    {
        if (empty($report->reporter_phone) || $report->is_anonymous) return;

        $phone = $this->formatPhone($report->reporter_phone);

        $message = "Halo *{$report->reporter_name}*,\n\n";
        $message .= "Kabar baik! Laporan Anda dengan tiket *{$report->ticket_code}* telah *SELESAI* ditangani oleh tim DLH Demak.\n\n";
        $message .= "Terima kasih atas kepedulian Anda terhadap lingkungan Kabupaten Demak.\n\n";
        $message .= "Anda dapat melihat hasil penanganan melalui link berikut:\n";
        $message .= route('public.track.show', $report->ticket_code);

        $this->sendMessage($phone, $message);
    }

    /**
     * Helper untuk format nomor HP ke standard +62
     */
    protected function formatPhone(string $phone): string
    {
        // Bersihkan karakter non digit
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Ubah 08xxx jadi 628xxx
        if (substr($phone, 0, 1) === '0') {
            return '62' . substr($phone, 1);
        }
        
        return $phone;
    }
}
