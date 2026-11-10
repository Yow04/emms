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

    public function updateStatus(
        Ticket $ticket,
        User $user,
        array $data
    ): Ticket {
        return DB::transaction(function () use ($ticket, $user, $data){

            $ticket = Ticket::wherekey($ticket->id)
            ->lockForUpdate()
            ->firstOrFail();

            $oldStatus = $ticket->status;
            $newStatus = $data['status'];

            //Jika tidak ada perubahan, tidak perlu mengubah status dari tiket

            if ($oldStatus === $newStatus){
                return $ticket;
            }

            $ticket -> update([
                'status' => $newStatus,
            ]);

            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'old_assigned_to' => $ticket->assigned_to,
                'new_assigned_to' => $ticket->assigned_to,
                'description' => $data['description']
                    ?? "Status tiket diubah dari {$oldStatus} menjadi {$newStatus}",   
            ]);

            return $ticket->fresh();
        });
    }

    public function assignTicket(
        Ticket $ticket,
        User $actor,
        array $data
    ): Ticket {
        return DB::transaction(function () use ($ticket, $actor, $data){

            $ticket = Ticket::whereKey($ticket->id)
            ->lockForUpdate()
            ->firstOrFail();

            $oldAssignedTo = $ticket->assigned_to;
            $newAssignedTo = (int) $data['assigned_to'];

            //Tidak perlu diubah jika sama
            if ((int) $oldAssignedTo === $newAssignedTo){
                return $ticket;
            }

            $ticket -> update([
                'assigned_to' => $newAssignedTo,
            ]);

            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => $actor->id,
                'old_status' => $ticket->status,
                'new_status' => $ticket->status,
                'old_assigned_to' => $oldAssignedTo,
                'new_assigned_to' => $newAssignedTo,
                'description' => $data['description']
                    ?? "Staff support tidak diperbarui",   
            ]);

            return $ticket->fresh();
        });
    }
}