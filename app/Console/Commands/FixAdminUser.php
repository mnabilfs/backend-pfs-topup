<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class FixAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:fix-admin {email} {password} {role=admin}';

    /**
     * The console command description.
     */
    protected $description = 'Fix user password (with proper hash) and role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $role = $this->argument('role');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }

        $user->password = Hash::make($password);
        $user->role = $role;
        $user->save();

        $this->info("✅ User '{$user->name}' ({$email}) - password updated & role set to '{$role}'");
        return 0;
    }
}
