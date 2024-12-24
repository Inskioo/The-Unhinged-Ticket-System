<?php 

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketCommandsTest extends TestCase{
    use RefreshDatabase;

    protected function setUp(): void{
        parent::setUp();
        
        // generate a few different users so we can run our tests against them
        User::factory()->count(10)->create();
        
        User::create([
            'name' => 'Testy Support Test',
            'email' => 'test@test.test',
            'role' => 'support',
            'password' => 'password',
        ]);

    }

    // can we generate a ticket?
    public function testCanGenerateTicket(){
        $ticketCount = Ticket::count();
        $this->artisan('ticket:generate')->assertSuccessful();

        $this->assertEquals($ticketCount + 1, Ticket::count());

        $ticket = Ticket::latest()->first();
        $this->assertNotNull($ticket->subject);
        $this->assertNotNull($ticket->content);
        $this->assertNotNull($ticket->priority);
        $this->assertNotNull($ticket->type);
        $this->assertNotNull($ticket->created_at);
        $this->assertNull($ticket->resolved_at);
    }

    // can we assign a ticket?
    public function testCanAssignTicket(){
        $ticket = Ticket::factory()->create();
       
        $this->artisan('ticket:assign')->assertSuccessful();

        $ticket->refresh();

        $this->assertNotNull($ticket->assigned_to);
        $this->assertEquals('support', User::find($ticket->assigned_to)->role);
    }

    // can we resolve a ticket
    public function testCanResolveTicket(){
        $supportUser = User::where('role', 'support')->first();
        $ticket = Ticket::factory()->create(['assigned_to' => $supportUser->id]);

        $this->artisan('ticket:resolve')->assertSuccessful();

        $ticket->refresh();

        $this->assertEquals('resolved', $ticket->status);
        $this->assertNotNull($ticket->resolved_at);
    }


}