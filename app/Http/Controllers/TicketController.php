<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Http\Requests\AssignTicketRequest;
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

    public function updateStatus(
        UpdateTicketStatusRequest $request,
        Ticket $ticket,
        TicketService $ticketService
    ): JsonResponse {
        $updatedTicket = $ticketService->updateStatus(
            $ticket,
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'status tiket berhasil diperbaharui',
            'data' =>  $updatedTicket,
        ]);
    }

    public function assignTicket(
        AssignTicketRequest $request,
        Ticket $ticket,
        TicketService $ticketService
    ): JsonResponse {
        $assignedTicket = $ticketService->assignTicket(
            $ticket,
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Tiket berhasil ditugaskan',
            'data' => $assignedTicket,
        ]);
    }
}
