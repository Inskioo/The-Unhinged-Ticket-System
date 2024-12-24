<?php 

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class GenerateTicket extends Command{
    protected $signature = 'ticket:generate';
    protected $description = 'A customer has a problem not quite of this world. Log it with a unhinged ticket';

    public function handle(){
        $ticket = Ticket::factory()->create();
        $this->info('I\'m sorry, we have logged ticket : ' . $ticket->id);
    }
}