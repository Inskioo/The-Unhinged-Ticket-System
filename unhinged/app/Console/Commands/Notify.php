<?php 

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AgentNotifySummary extends Command {

    protected $signature = 'tickets:notify-agents';
    protected $description = 'Send summary to all support agents';
    public function handle() {
        $agents = User::where('role', 'support')->get();
        
        foreach ($agents as $agent) {
            $assignedTickets = Ticket::where('assigned_to', $agent->id)
                ->where('status', '!=', 'resolved')
                ->get();
            
            $stats = [
                'total' => $assignedTickets->count(),
                'slightly_unhinged' => $assignedTickets->where('type', 'slightly_unhinged')->count(),
                'wildly_unhinged' => $assignedTickets->where('type', 'wildly_unhinged')->count(),
                'p1' => $assignedTickets->where('priority', 'p1')->count(),
                'p2' => $assignedTickets->where('priority', 'p2')->count(),
                'p3' => $assignedTickets->where('priority', 'p3')->count(),
                'p4' => $assignedTickets->where('priority', 'p4')->count(),
            ];
            
            $emailContent = "
                {$agent->name},
                
                Your daily summary has arrived, please see below for what's left to do on your list.
                
                Total Unresolved Tickets: {$stats['total']}
                
                Breakdown by Type:
                - Slightly Unhinged: {$stats['slightly_unhinged']}
                - Wildly Unhinged: {$stats['wildly_unhinged']}
                
                Priority Breakdown:
                - P1: {$stats['p1']}
                - P2: {$stats['p2']}
                - P3: {$stats['p3']}
                - P4: {$stats['p4']}
                
                Please address P1 ASAP As SLA's are important
                
                Warmest,
                The Unhinged Ringmaster
            ";
            
            Log::info('Mock Email Sent', [
                'to' => $agent->email,
                'subject' => 'Your Daily Ticket Summary',
                'content' => $emailContent
            ]);
            
            $this->info("Mock email sent to: {$agent->email}");
        }
    }
}