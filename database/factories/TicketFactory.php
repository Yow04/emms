<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::where('role', 'requester')
            ->inRandomOrder()
            ->value('id'),

            'assigned_to' => User::where('role', 'support')
            ->inRandomOrder()
            ->value('id'),
            
            'category_id' => Category::inRandomOrder()
            ->value('id'),

            'ticket_number' => 'TCK-' . now()->format('Ymd') . '-' . Str::upper(Str::random(8)),

            'subject' => fake()->sentence(6),

            'description' => fake()->paragraphs(3, true),

            'priority' => fake()->randomElement([
                'low',
                'medium',
                'high',
                'urgent'
            ]),

            'status' => fake()->randomElement([
                'open',
                'in_progress',
                'pending',
                'resolved',
                'closed'
            ]),
        ];
    }
}
