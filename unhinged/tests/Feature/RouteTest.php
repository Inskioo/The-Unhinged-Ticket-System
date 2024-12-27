<?php 

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteTest extends TestCase{
    use RefreshDatabase;

    protected $user;
    protected $supportUser;

    protected function setUp(): void{
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        $this->supportUser = User::create([
            'name' => 'Testy Support Test',
            'email' => 'test@test.test',
            'role' => 'support',
            'password' => 'password',
        ]);

        Ticket::factory()->count(5)->create();
        
        Ticket::factory()->create([
            'assigned_to' => $this->supportUser->id
        ]);

        Ticket::factory()->create([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    public function testUnauthenticatedUserCannotAccessApi(){
        $response = $this->getJson('/api/tickets');
        $response->assertStatus(401);
    }

    public function testCanGetAllTickets(){
        Sanctum::actingAs($this->user);
        
        $response = $this->getJson('/api/tickets');
        
        $response->assertStatus(200)
                ->assertJsonCount(7);
    }

    public function testCanGetSpecificTicket(){
        Sanctum::actingAs($this->user);
        
        $ticket = Ticket::first();
        
        $response = $this->getJson("/api/tickets/{$ticket->id}");
        
        $response->assertStatus(200)
                ->assertJson([
                    'id' => $ticket->id,
                    'subject' => $ticket->subject
                 ]);
    }

    public function testCanGetAssignedTickets(){
        Sanctum::actingAs($this->user);
        
        $response = $this->getJson('/api/tickets/assigned/' . $this->supportUser->id);
        
        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    public function testCanGetResolvedTickets(){
        Sanctum::actingAs($this->user);
        
        $response = $this->getJson('/api/tickets/resolved');
        
        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    public function testCanGetTicketsByCategory(){
        Sanctum::actingAs($this->user);
        
        $ticket = Ticket::factory()->create([
            'type' => 'slightly_unhinged'
        ]);
        
        $response = $this->getJson('/api/tickets/category/slightly_unhinged');
        
        $response->assertStatus(200);
        $this->assertTrue(collect($response->json())
            ->contains('type', 'slightly_unhinged'));
    }

    public function testCanGetTicketsByPriority(){
        Sanctum::actingAs($this->user);
        
        $ticket = Ticket::factory()->create([
            'priority' => 'p1'
        ]);
        
        $response = $this->getJson('/api/tickets/priority/p1');
        
        $response->assertStatus(200);
        $this->assertTrue(collect($response->json())
            ->contains('priority', 'p1'));
    }

    public function testCanGetTicketsByUser(){
        Sanctum::actingAs($this->user);
        
        $ticket = Ticket::factory()->create([
            'user_id' => $this->user->id
        ]);
        
        $response = $this->getJson("/api/tickets/user/{$this->user->id}");
        
        $response->assertStatus(200);
        $this->assertTrue(collect($response->json())
            ->contains('user_id', $this->user->id));
    }

    public function testInvalidTicketIdReturns404(){
        Sanctum::actingAs($this->user);
        
        $response = $this->getJson('/api/tickets/99999');
        
        $response->assertStatus(404);
    }
}