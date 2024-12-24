<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory{
    protected $model = Ticket::class;

    protected $seedfile = 'database/seeders/TicketData.json';

    public function definition(){
        $seed = json_decode(file_get_contents($this->seedfile), true);
        $ticketToUse = collect($seed['tickets'])->random();
        $unhingedHuman = User::inRandomOrder()->first();

        return [
            'user_id' => $unhingedHuman->id,
            'assigned_to' => null,
            'subject' => $ticketToUse['subject'],
            'content' => $ticketToUse['content'],
            'status' => 'open',
            'priority' => $ticketToUse['priority'],
            'type' => $ticketToUse['type'],
            'created_at' => now(),
            'resolved_at' => null,
        ];
    }
}