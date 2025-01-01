<?php 

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    protected $supportUser;

    protected function setUp(): void
    {
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

    public function testCanGetAllTickets()
    {
        $response = $this->getJson('/api/tickets');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'total'
                ]);
        
        $this->assertEquals(7, $response->json('total'));
    }

    public function testCanGetSpecificTicket()
    {
        $ticket = Ticket::first();
        
        $response = $this->getJson("/api/tickets/{$ticket->id}");
        
        $response->assertStatus(200)
                ->assertJson([
                    'id' => $ticket->id,
                    'subject' => $ticket->subject
                ]);
    }

    public function testCanGetAssignedTickets()
    {
        $response = $this->getJson('/api/tickets/assigned/' . $this->supportUser->id);
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'total'
                ]);
        
        $this->assertEquals(1, $response->json('total'));
        $ticket = $response->json('data.0');
        $this->assertNotNull($ticket);
        $this->assertNotNull($ticket['assigned_to']);
    }

    public function testCanGetResolvedTickets()
    {
        $response = $this->getJson('/api/tickets/resolved');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'total'
                ]);
        
        $this->assertEquals(1, $response->json('total'));
        $this->assertEquals('resolved', $response->json('data.0.status'));
    }

    public function testCanGetTicketsByCategory()
    {
        $ticket = Ticket::factory()->create([
            'type' => 'slightly_unhinged'
        ]);
        
        $response = $this->getJson('/api/tickets/category/slightly_unhinged');
        
        $response->assertStatus(200);
        $tickets = $response->json('data');
        $this->assertTrue(collect($tickets)->contains('type', 'slightly_unhinged'));
    }

    public function testCanGetTicketsByPriority()
    {
        $ticket = Ticket::factory()->create([
            'priority' => 'p1'
        ]);
        
        $response = $this->getJson('/api/tickets/priority/p1');
        
        $response->assertStatus(200);
        $tickets = $response->json('data');
        $this->assertTrue(collect($tickets)->contains('priority', 'p1'));
    }

    public function testCanGetTicketsByUser()
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create([
            'user_id' => $user->id
        ]);
        
        $response = $this->getJson("/api/tickets/user/{$user->id}");
        
        $response->assertStatus(200);
        $tickets = $response->json('data');
        $this->assertTrue(collect($tickets)->contains('user_id', $user->id));
    }

    public function testCanGetQueueStats()
    {
        $response = $this->getJson('/api/tickets/stats/queue');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'currentQueue' => [
                        'total',
                        'unassigned',
                        'assignedIncomplete'
                    ],
                    'typeBreakdown' => [
                        'slightlyUnhinged',
                        'wildlyUnhinged'
                    ],
                    'resolvedStats' => [
                        'totalComplete',
                        'slightlyUnhinged',
                        'wildlyUnhinged'
                    ]
                ]);
    }

    public function testCanGetAgentStats()
    {
        $response = $this->getJson('/api/tickets/stats/agents');
        
        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'name',
                        'assignedCount',
                        'completedCount',
                        'qtr'
                    ]
                ]);
    }

    public function testInvalidTicketIdReturns404()
    {
        $response = $this->getJson('/api/tickets/99999');
        
        $response->assertStatus(404);
    }
}