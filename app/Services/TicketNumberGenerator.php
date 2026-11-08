<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TicketNumberGenerator
{
    public function generate(): string
    {
        return DB::transaction(function () {
            $date = now()->format('Ymd');
            $prefix = 'HD-' . $date . '-';

            // Mengunci proses pembuatan nomor berdasarkan tanggal
            DB::select(
                'SELECT pg_advisory_xact_lock(hashtext(?)::bigint)',
                [$date]
            );

            // Mengambil nomor tiket terakhir pada tanggal tersebut
            $lastTicketNumber = Ticket::where(
                'ticket_number',
                'like',
                $prefix . '%'
            )
                ->orderByDesc('ticket_number')
                ->value('ticket_number');

            $nextNumber = $lastTicketNumber
                ? ((int) substr($lastTicketNumber, -4)) + 1
                : 1;

            if ($nextNumber > 9999) {
                throw new RuntimeException(
                    'Nomor tiket harian telah mencapai batas maksimum.'
                );
            }

            return $prefix . str_pad(
                $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );
        });
    }
}