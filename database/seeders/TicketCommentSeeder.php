<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = Ticket::with(['requester', 'assignedAgent'])->get();

        foreach ($tickets as $ticket) {
            //Menghindari komentar ganda saat seeder dijalankan ulang
            if ($ticket->comments()->exists()){
                continue;
            }

            $ticket->comments()->create([
              'user_id' => $ticket->requester->id,
              'comments' => 'Saya mengalami kendala pada tiket ini. Mohon untuk segera tindak lanjut'  
            ]);

            //Komentar dari Agent yang ditugaskan (opsional)
            if ($ticket->assignedAgent){
                $ticket->comments()->create([
                    'user_id' => $ticket->assignedAgent->id,
                    'comments' => 'Baik, laporan sudah diterima dan akan segera ditindak lanjut'
                ]);
            }
        }
    }
}
