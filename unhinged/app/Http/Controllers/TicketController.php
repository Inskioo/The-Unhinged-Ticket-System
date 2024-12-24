<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
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
}