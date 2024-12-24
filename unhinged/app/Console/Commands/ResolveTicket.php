<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;

class ResolveTicket extends Command{
    protected $signature = 'ticket:resolve';
    protected $description = 'Put an assigned ticket out of its misery.';

    public function handle(){
        // I will set the the oldest ticket that is assigned.
        $ticket = Ticket::bySupportAssigned(true)->byNotResolved()->orderBy('created_at', 'asc')->first();

        if ( $ticket ) {
            $ticket->update([
                'status' => 'resolved',
                'resolved_at' => now(),
            ]);

            $this->info('Ticket ' . $ticket->id . ' has been resolved, for good, forever.');
        }  else {
            $this->info('uhm - nothing found to resolve. Is everything okay?');
        }
    }
}