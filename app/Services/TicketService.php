<?php
namespace App\Services;
use App\Models\Ticket;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class TicketService
{
    public function getAll(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return Ticket::where('user_id', $userId)->latest()->get();
    }

    public function getById(int $id): ?Ticket
    {
        return Ticket::find($id);
    }

    public function create(array $data, int $userId): Ticket
    {
        $ticket = Ticket::create([...$data, 'user_id' => $userId]);
        ActivityLog::create([
            'user_id'    => $userId,
            'action'     => 'ticket_created',
            'model_type' => Ticket::class,
            'model_id'   => $ticket->id,
            'data'       => $data,
        ]);
        Log::info('Ticket creado', [
            'ticket_id' => $ticket->id,
            'user_id'   => $userId,
            'title'     => $ticket->title,
            'priority'  => $ticket->priority,
        ]);
        return $ticket;
    }

    public function update(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);
        ActivityLog::create([
            'user_id'    => $ticket->user_id,
            'action'     => 'ticket_updated',
            'model_type' => Ticket::class,
            'model_id'   => $ticket->id,
            'data'       => $data,
        ]);
        Log::info('Ticket actualizado', [
            'ticket_id' => $ticket->id,
            'cambios'   => $data,
        ]);
        return $ticket;
    }

    public function delete(Ticket $ticket): void
    {
        ActivityLog::create([
            'user_id'    => $ticket->user_id,
            'action'     => 'ticket_deleted',
            'model_type' => Ticket::class,
            'model_id'   => $ticket->id,
            'data'       => ['title' => $ticket->title],
        ]);
        Log::warning('Ticket eliminado', [
            'ticket_id' => $ticket->id,
            'title'     => $ticket->title,
            'user_id'   => $ticket->user_id,
        ]);
        $ticket->delete();
    }
}