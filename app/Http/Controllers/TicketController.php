<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function store(
        StoreTicketRequest $request,
        TicketService $ticketService
    ): JsonResponse {
        $ticket = $ticketService->createTicket(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Tiket berhasil dibuat.',
            'data' => $ticket,
        ], 201);
    }

    public function index(): JsonResponse
    {
        $user = request()->user();

        $query = \App\Models\Ticket::with([
            'requester:id,name,email',
            'assignedAgent:id,name,email',
            'category:id,name',
        ]);

        //Requester hanya bisa melihat milik sendiri
        if ($user->isRequester()){
            $query->where('user_id', $user->id);
        }

        $tickets = $query
        ->latest()
        ->paginate(10);

        return response()->json([
            'message' => 'List Tiket berhasil diambil',
            'data' => $tickets,
        ]);
    }
}
