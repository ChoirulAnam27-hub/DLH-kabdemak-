<?php

namespace App\Services;

use App\Models\Report;
use Carbon\Carbon;

class TicketService
{
    /**
     * Generate unique ticket code: DLH-YYYYMMDD-XXX
     */
    public function generateTicketCode(): string
    {
        $datePrefix = Carbon::now()->format('Ymd');
        $baseCode = 'DLH-' . $datePrefix . '-';

        // Find the last ticket for today
        $lastReport = Report::where('ticket_code', 'like', $baseCode . '%')
            ->orderBy('ticket_code', 'desc')
            ->first();

        if (!$lastReport) {
            $sequence = 1;
        } else {
            // Extract the XXX part and increment
            $lastSequence = (int) substr($lastReport->ticket_code, -3);
            $sequence = $lastSequence + 1;
        }

        return $baseCode . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
}
