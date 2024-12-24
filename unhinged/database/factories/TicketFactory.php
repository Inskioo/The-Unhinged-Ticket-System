<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory{
    protected $model = Ticket::class;

    protected $seedfile = 'seeders/data/tickets.json';
    protected $seed;

    public function __construct($seed = null){
        $this->seed = json_decode(file_get_contents($seedfile), true);
    }

    public function definition(){
        $ticketToUse = collect($this->seed['tickets'])->random();
        $unhingedHuman = user::inRandomOrder()->first();

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