<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetUserRole extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'user:set-role {email} {role=admin}';

    /**
     * The console command description.
     */
    protected $description = 'Set user role by email (e.g., php artisan user:set-role user@email.com admin)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found!");
            return 1;
        }

        $oldRole = $user->role;
        $user->role = $role;
        $user->save();

        $this->info("✅ User '{$user->name}' ({$email}) role changed from '{$oldRole}' to '{$role}'");
        return 0;
    }
}
