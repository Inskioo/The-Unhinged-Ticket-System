<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller{
    public function index()
    {
        return Ticket::orderBy('created_at', 'desc')->get();
    }

    public function display(Ticket $ticket)
    {
        return $ticket;
    }

    public function assigned($support_id = null)
    {
        $query = Ticket::query();
        
        if ($support_id) {
            return $query->bySupportAssigned($support_id)->get();
        }
        
        return $query->bySupportAssigned(true)->get();
    }

    public function resolved()
    {
        return Ticket::byResolved()->get();
    }

    public function categorise($type)
    {
        return Ticket::byType($type)->get();
    }

    public function priority($level)
    {
        return Ticket::byPriority($level)->get();
    }

    public function user($user_id)
    {
        return Ticket::byUnhingedHuman($user_id)->get();
    }

    public function queueStats(){
        return response()->json([
            'inQueue' => Ticket::count(),
            'unassigned' => Ticket::whereNull('assigned_to')->count(),
            'assignedIncomplete' => Ticket::bySupportAssigned()->
                                    byNotResolved()->
                                    count(),
            'slightlyUnhinged' => Ticket::byType('slightly_unhinged')->count(),
            'wildlyUnhinged' => Ticket::byResolved()->count(),
        ]);
    }


    public function agentStats()
    {
        $agents = User::where('role', 'support')->get();
        
        $stats = $agents->map(function($agent) {
            $assignedCount = Ticket::bySupportAssigned($agent->id)->count();
            $completedCount = Ticket::bySupportAssigned($agent->id)->
                            byResolved()->
                            count();
            
            return [
                'id' => $agent->id,
                'name' => $agent->name,
                'assignedCount' => $assignedCount,
                'completedCount' => $completedCount,
                'completionRate' => $assignedCount > 0 
                    ? round(($completedCount / $assignedCount) * 100, 1) 
                    : 0
            ];
        });

        return response()->json($stats);
    }


}