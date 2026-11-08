<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        protected TicketNumberGenerator $numberGenerator
    ) {}

    public function createTicket(User $requester, array $data): Ticket
    {
        return DB::transaction(function () use ($requester, $data){
            //generate nomor tiket
            $ticketNumber = $this->numberGenerator->generate();

            //Membuat tiket baru
            $ticket = Ticket::create([
                'user_id' => $requester->id,
                'assigned_to' => null,
                'category_id' => $data['category_id'],
                'ticket_number' => $ticketNumber,
                'subject' => $data['subject'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? 'medium',
                'status' => 'open',
            ]);

            //membuat riwayat awal tiket
            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => $requester->id,
                'old_status' => null,
                'new_status' => 'open',
                'old_assigned_to' => null,
                'new_assigned_to' => null,
                'description' => 'Tiket dibuat oleh requester.',
            ]);

            return $ticket;
        });
    }
}