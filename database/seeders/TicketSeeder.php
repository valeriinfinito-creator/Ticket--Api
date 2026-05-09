<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::create(['user_id' => 1, 'title' => 'PC no enciende', 'description' => 'La PC del área de ventas no enciende', 'priority' => 'high', 'status' => 'open']);
        Ticket::create(['user_id' => 1, 'title' => 'Mouse dañado', 'description' => 'El mouse no funciona correctamente', 'priority' => 'low', 'status' => 'open']);
    }
}
