<?php

$user = [
    'name' => 'Alice',
    'age' => 25,
    'email' => 'alice@example.com',
    'role' => 'admin',
];

foreach ($user as $key => $value) {
    echo "{$key}: {$value}" . PHP_EOL;
}
