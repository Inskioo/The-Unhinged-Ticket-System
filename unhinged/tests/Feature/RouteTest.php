<?php 

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteTest extends TestCase{
    use RefreshDatabase;

    protected $supportUser;

    protected function setUp(): void{
        parent::setUp();
        
        // Create support user
        $this->supportUser = User::create([
            'name' => 'Testy Support Test',
            'email' => 'test@test.test',
            'role' => 'support',
            'password' => 'password',
        ]);

        // Create test tickets
        Ticket::factory()->count(5)->create();
        
        // Create assigned ticket
        Ticket::factory()->create([
            'assigned_to' => $this->supportUser->id
        ]);

        // Create resolved ticket
        Ticket::factory()->create([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    public function testCanGetAllTickets(){
        $response = $this->getJson('/api/tickets');
        
        $response->assertStatus(200)
                ->assertJsonCount(7);
    }

    public function testCanGetSpecificTicket(){
        $ticket = Ticket::first();
        
        $response = $this->getJson("/api/tickets/{$ticket->id}");
        
        $response->assertStatus(200)
                ->assertJson([
                    'id' => $ticket->id,
                    'subject' => $ticket->subject
                ]);
    }

    public function testCanGetAssignedTickets(){
        $response = $this->getJson('/api/tickets/assigned/' . $this->supportUser->id);
        
        $response->assertStatus(200)
                ->assertJsonCount(1);
    }

    public function testCanGetResolvedTickets(){
        $response = $this->getJson('/api/tickets/resolved');
        
        $response->assertStatus(200)
                ->assertJsonCount(1);
    }

    public function testCanGetTicketsByCategory(){
        $ticket = Ticket::factory()->create([
            'type' => 'slightly_unhinged'
        ]);
        
        $response = $this->getJson('/api/tickets/category/slightly_unhinged');
        
        $response->assertStatus(200);
        $this->assertTrue(collect($response->json())
            ->contains('type', 'slightly_unhinged'));
    }

    public function testCanGetTicketsByPriority(){
        $ticket = Ticket::factory()->create([
            'priority' => 'p1'
        ]);
        
        $response = $this->getJson('/api/tickets/priority/p1');
        
        $response->assertStatus(200);
        $this->assertTrue(collect($response->json())
            ->contains('priority', 'p1'));
    }

    public function testCanGetTicketsByUser(){
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create([
            'user_id' => $user->id
        ]);
        
        $response = $this->getJson("/api/tickets/user/{$user->id}");
        
        $response->assertStatus(200);
        $this->assertTrue(collect($response->json())
            ->contains('user_id', $user->id));
    }

    public function testCanGetQueueStats(){
        $response = $this->getJson('/api/tickets/stats/queue');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'inQueue',
                    'unassigned',
                    'assignedIncomplete',
                    'slightlyUnhinged',
                    'wildlyUnhinged',
                ]);
    }

    public function testCanGetAgentStats(){
        $response = $this->getJson('/api/tickets/stats/agents');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'name',
                        'assignedCount',
                        'completedCount',
                        'completionRate'
                    ]
                ]);
    }

    public function testInvalidTicketIdReturns404(){
        $response = $this->getJson('/api/tickets/99999');
        
        $response->assertStatus(404);
    }
}