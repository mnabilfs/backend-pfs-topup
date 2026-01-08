<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:create-admin {name} {email} {password}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->warn("⚠️ User with email '{$email}' already exists. Skipping creation.");
            return 0;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,  // Model auto-hashes via cast
            'role' => 'admin',
        ]);

        $this->info("✅ Admin user created: {$name} ({$email})");
        return 0;
    }
}
