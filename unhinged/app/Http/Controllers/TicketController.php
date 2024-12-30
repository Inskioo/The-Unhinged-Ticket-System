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
            'currentQueue' => [
                'total' => Ticket::byNotResolved()->count(),
                'unassigned' => Ticket::whereNull('assigned_to')->byNotResolved()->count(),
                'assignedIncomplete' => Ticket::whereNotNull('assigned_to')->byNotResolved()->count()
            ],
            'typeBreakdown' => [
                'slightlyUnhinged' => Ticket::byType('slightly_unhinged')->byNotResolved()->count(),
                'wildlyUnhinged' => Ticket::byType('wildly_unhinged')->byNotResolved()->count()
            ],
            'resolvedStats' => [
                'totalComplete' => Ticket::byResolved()->count(),
                'slightlyUnhinged' => Ticket::byType('slightly_unhinged')->byResolved()->count(),
                'wildlyUnhinged' => Ticket::byType('wildly_unhinged')->byResolved()->count()
            ]
        ]);
    }


    public function agentStats()
    {
        $agents = User::where('role', 'support')->get();
    
        return response()->json($agents->map(function($agent) {
            $assigned = Ticket::bySupportAssigned($agent->id)->count();
            $completed = Ticket::bySupportAssigned($agent->id)->byResolved()->count();
            
            return [
                'id' => $agent->id,
                'name' => $agent->name,
                'assignedCount' => $assigned,
                'completedCount' => $completed,
                'qtr' => $assigned > 0 ? round(($completed / $assigned) * 100) : 0
            ];
        }));

        return response()->json($stats);
    }


}