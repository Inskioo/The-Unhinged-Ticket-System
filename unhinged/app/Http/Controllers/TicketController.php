<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller{

    public function index(Request $request){
    $query = Ticket::with(['user', 'assignedTo']);

    if ($request->status) {
        $status = $request->status;
        if ($status === 'resolved') {
            $query->byResolved();
        } else {
            $query->byNotResolved();
        }
    }

    if ($request->has('assignment')) {
        $isAssigned = filter_var($request->assignment, FILTER_VALIDATE_BOOLEAN);
        if ($isAssigned) {
            $query->whereNotNull('assigned_to');
        } else {
            $query->whereNull('assigned_to');
        }
    }

    $query->when($request->type, function($query) use ($request) {
            $query->byType($request->type);
        })
        ->when($request->priority, function($query) use ($request) {
            $query->byPriority($request->priority);
        })
        ->when($request->assigned_to, function($query) use ($request) {
            $query->bySupportAssigned($request->assigned_to);
        })
        ->when($request->search, function($query) use ($request) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        });
    
    return $query->orderBy('created_at', 'desc')->paginate(10);
}


    public function display(Ticket $ticket)
    {
        return $ticket;
    }

    public function assigned($support_id = null)
    {
        $query = Ticket::with(['user', 'assignedTo']);
        
        if ($support_id) {
            return $query->bySupportAssigned($support_id)->paginate(10);
        }
        
        return $query->bySupportAssigned(true)->paginate(10);
    }

    public function resolved()
    {
        return Ticket::with(['user', 'assignedTo'])->byResolved()->paginate(10);
    }

    public function categorise($type)
    {
        return Ticket::with(['user', 'assignedTo'])->byType($type)->paginate(10);
    }

    public function priority($level)
    {
        return Ticket::with(['user', 'assignedTo'])->byPriority($level)->paginate(10);
    }

    public function user($user_id)
    {
        return Ticket::with(['user', 'assignedTo'])->byUnhingedHuman($user_id)->paginate(10);
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

    public function getUserTicketCounts($userId){
        return response()->json([
            'resolved' => Ticket::byResolved()->byUnhingedHuman($userId)->count(),
            'unresolved' => Ticket::byNotResolved()->byUnhingedHuman($userId)->count()
        ]);
    }

    public function assignTicket(Request $request, Ticket $ticket){
        $validated = $request->validate([
            'agent_id' => 'required|exists:users,id'
        ]);

        $ticket->update([
            'assigned_to' => $validated['agent_id']
        ]);

        return response()->json([
            'message' => 'Poor Agent - Success',
            'ticket' => $ticket->fresh()
        ]);
    }

    public function resolveTicket(Ticket $ticket){
        $ticket->update([
            'status' => 'resolved',
        ]);

        return response()->json([
            'message' => 'Woo - Closed manually',
            'ticket' => $ticket->fresh()
        ]);
    }

    public function setType(Request $request, Ticket $ticket){
        $validated = $request->validate([
            'type' => 'required|in:slightly_unhinged,wildly_unhinged'
        ]);

        $ticket->update([
            'type' => $validated['type']
        ]);

        return response()->json([
            'message' => 'Unhinged Type Set - Woo',
            'ticket' => $ticket->fresh()
        ]);
    }

}