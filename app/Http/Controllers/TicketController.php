<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketService) {}

    public function index(Request $request)
    {
        try {
            $tickets = $this->ticketService->getAll($request->user()->id);
            return response()->json(['success' => true, 'data' => $tickets]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $ticket = $this->ticketService->getById($id);
            if (!$ticket) {
                return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
            }
            return response()->json(['success' => true, 'data' => $ticket]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'required|string',
                'priority'    => 'in:low,medium,high,critical',
                'type'        => 'in:incident,device_assignment,device_control',
            ]);

            $ticket = $this->ticketService->create($validated, $request->user()->id);
            return response()->json(['success' => true, 'data' => $ticket, 'message' => 'Ticket creado'], 201);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $ticket = $this->ticketService->getById($id);
            if (!$ticket) {
                return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
            }

            $validated = $request->validate([
                'title'       => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'status'      => 'sometimes|in:open,in_progress,resolved,closed',
                'priority'    => 'sometimes|in:low,medium,high,critical',
            ]);

            $ticket = $this->ticketService->update($ticket, $validated);
            return response()->json(['success' => true, 'data' => $ticket, 'message' => 'Ticket actualizado']);

        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $ticket = $this->ticketService->getById($id);
            if (!$ticket) {
                return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
            }

            $this->ticketService->delete($ticket);
            return response()->json(['success' => true, 'message' => 'Ticket eliminado']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
