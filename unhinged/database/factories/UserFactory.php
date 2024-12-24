<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory{
    protected $model = User::class;
    
    // known users -  this as part of a testing application and would have infrastructure for these users if a deployed system.
    private $staticHoomans = [
        'admin' => [
            'name' => 'James',
            'email' => 'james@securescreeningservices.com',
            'password' => 'supersecurepassw0rd',
        ],
        'support' => [
            ['name' => 'Sir Digby Chicken-Ceasar', 'email' => 'dcc@securescreeningservices.com'],
            ['name' => 'Derek Zoolander', 'email' => 'derek.zoolander@securescreeningservices.com'],
            ['name' => 'Bruce Wain', 'email' => 'bruce.wain@securescreeningservices.com'],
        ]
    ];

    // automate our unhinged creation
    public function definition(){
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'role' => 'user',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    // create our admin user
    public function admin(){
        return $this->state(function (array $attributes) {
            return [
                'name' => $this->staticHoomans['admin']['name'],
                'email' => $this->staticHoomans['admin']['email'],
                'password' => Hash::make($this->staticHoomans['admin']['password']),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ];
        });
    }

    // create our support team
    public function support(){
        return $this->state(function (array $attributes) {
            $supportUser = $this->faker->randomElement($this->staticHoomans['support']);
            return [
                'name' => $supportUser['name'],
                'email' => $supportUser['email'],
                'role' => 'support',
                'password' => 'password',
                'created_at' => now(),
                'updated_at' => now()
            ];
        });
    }
}