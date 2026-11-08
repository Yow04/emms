<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requesters = User::where('role', 'requester')->get();
        $agents = User::where('role', 'support')->get();
        $categories = Category::where('is_active', true)->get();

        if (
            $requesters->isEmpty() ||
            $agents->isEmpty() ||
            $categories->isEmpty()
        ){
            throw new \RuntimeException(
                'Pastikan ada User Requesters, Support dan Category yang Aktif sebelum menjalankan Seeder ini '
            );
        }

        $status = [
            'open',
            'in_progress',
            'pending',
            'resolved',
            'closed'
        ];

        $priority = [
            'low',
            'medium',
            'high',
            'urgent'
        ];

        for ($i = 1; $i <= 20; $i++){
            Ticket::updateOrCreate(
                [
                    'ticket_number' => 'TCK-2026-' . str_pad($i, 4 ,'0', STR_PAD_LEFT),
                ],
                [
                    'user_id' => $requesters->random()->id,
                    'assigned_to' => $agents->random()->id,
                    'category_id' => $categories->random()->id,
                    'subject' => fake()->sentence(5),
                    'description' => fake()->paragraph(),
                    'priority' => $priority[array_rand($priority)],
                    'status' => $status[array_rand($status)],
                ]
            );
        }
    }
}
