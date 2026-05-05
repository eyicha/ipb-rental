<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminCommand extends Command
{
    protected $signature = 'app:make-admin {email}';
    protected $description = 'Make a user an admin';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }
        
        $user->role = 'admin';
        $user->save();
        
        $this->info("User '{$email}' is now an admin!");
        return 0;
    }
}
