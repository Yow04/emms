<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = Ticket::all();

        foreach ($tickets as $ticket){
            //Menghindari dupliaksi riwayat/histori
            if ($ticket->histories()->exists()){
                continue;
            }

            TicketHistory::create([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->user_id,
                'old_status' => null,
                'new_status' => $ticket->status,
                'old_assigned_to' => null,
                'new_assigned_to' => $ticket->assigned_to,
                'description' => 'Tiket dibuat oleh requester',
            ]);
        }
    }
}
