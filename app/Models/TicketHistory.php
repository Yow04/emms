<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'old_status',
        'new_status',
        'old_assigned_to',
        'new_assigned_to',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function previousAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'old_assigned_to');
    }

    public function nextAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'new_assigned_to');
    }
}
