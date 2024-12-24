<?php 

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketRouteTest extends TestCase{
    use RefreshDatabase;

    protected $user;
    protected $ticket;

    protected function setUp(): void{
        // Todo
    }


}