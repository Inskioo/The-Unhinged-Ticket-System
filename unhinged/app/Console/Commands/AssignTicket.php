<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;
use App\Models\User;

class AssignTicket extends Command{
    protected $signature = 'ticket:assign';
    protected $description = 'Select a victim to resolve this ticket from our support team.';

    public function handle(){
        // logically, I'm going to selet the oldest ticket that is open and unassigned, just so there is some sort of
        // orderly flow to this unorderly mess.

        $ticket = Ticket::whereNull('assigned_to')->byNotResolved()->orderBy('created_at', 'asc')->first();

        if ( $ticket ){
            $supportVictim = User::where('role', 'support')->inRandomOrder()->first();

            $ticket->update(['assigned_to' => $supportVictim->id]);

            $this->info('Ticket ' . $ticket->id . ' has been assigned to ' . $supportVictim->name);
        } else {
            $this->info('No unassigned tickets to assign - is everything okay?');
        }
    }
}