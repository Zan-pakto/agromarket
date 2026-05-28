<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$u = User::create([
    'name' => 'Test Hashing',
    'email' => 'testhash@example.com',
    'password' => Hash::make('secret'),
    'role' => 'seller',
    'status' => 'approved'
]);

echo 'Password cast class: ' . gettype($u->password) . PHP_EOL;
echo 'Saved hash: ' . $u->password . PHP_EOL;
echo 'Hash matches "secret": ' . (Hash::check('secret', $u->password) ? 'YES' : 'NO') . PHP_EOL;

// Let's also check if registering a user with raw password gets hashed correctly
$u2 = User::create([
    'name' => 'Test Hashing Raw',
    'email' => 'testhashraw@example.com',
    'password' => 'secret',
    'role' => 'seller',
    'status' => 'approved'
]);
echo 'Saved hash for raw password: ' . $u2->password . PHP_EOL;
echo 'Raw password hash matches "secret": ' . (Hash::check('secret', $u2->password) ? 'YES' : 'NO') . PHP_EOL;

$u->delete();
$u2->delete();
