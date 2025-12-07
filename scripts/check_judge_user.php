<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'judge@example.com';
$user = App\Models\User::where('email', $email)->first();
if ($user) {
    echo "FOUND|" . $user->email . "|" . $user->name . PHP_EOL;
} else {
    echo "NOT_FOUND" . PHP_EOL;
}
