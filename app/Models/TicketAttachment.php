<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'field_name',
        'field_path',
        'mime_type',
        'file_size',
    ];

    public function ticket():BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function uploader():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
