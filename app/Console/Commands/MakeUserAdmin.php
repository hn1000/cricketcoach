<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email} {--role=admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user an admin or super admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->option('role');

        // Validate role
        if (!in_array($role, ['admin', 'super_admin'])) {
            $this->error('Invalid role. Must be "admin" or "super_admin"');
            return 1;
        }

        // Find user
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        // Update user role
        $user->update(['role' => $role]);

        $this->info("User '{$user->name}' ({$user->email}) has been made a {$role}!");

        return 0;
    }
}
