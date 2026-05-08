<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::where('name', 'Nurafia Avanza')->first();
if ($user) {
    echo "User: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "is_blocked: " . ($user->is_blocked ? 'Yes (1)' : 'No (0)') . "\n";
    echo "blocked_reason: " . ($user->blocked_reason ?? 'null') . "\n";
} else {
    echo "User not found\n";
}
