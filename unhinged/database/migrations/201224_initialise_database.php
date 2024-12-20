<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    protected $roles = ['admin', 'user', 'support'];
    protected $statuses = ['open', 'resolved'];
    protected $priorities = ['p1', 'p2', 'p3', 'p4', 'p5'];
    protected $types = ['slightly_unhinged', 'wildly_unhinged', 'completely_unhinged'];

    public function up() {
        // user table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', $this->roles)->default('user');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });

        // the unhinged table
        Schema::create('tickets', function(Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->string('subject', 500);
            $table->text('content')->nullable();
            $table->enum('status', $this->statuses)->default('open');
            $table->enum('priority', $this->priorities)->default('p5');
            $table->enum('type', $this->types)->default('slightly_unhinged');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();
        });
    }
};