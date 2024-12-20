<?php 

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder{
    public function run(): void{
        // create admin user
        user::factory()->admin()->create();

        // our support team next
        user::factory()->support()->count(3)->create();

        // generate 50 random unhinged humans for our tickets
        user::factory()->count(50)->create();
    }
}