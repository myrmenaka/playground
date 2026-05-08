<?php

declare(strict_types=1);

class User 
{
    public function __construct(
        private string $name,
        private int $age,
    )
    {}

    public function introduce(): string
    {
        return "私は{$this->name}です。{$this->age}歳です。";
    }
}

$users = [
    new User('Alice', 30),
    new User('Bob', 25),
    new User('Charlie', 35),
    new User('Dave', 28),
    new User('Eve', 22),
];

foreach ($users as $user) {
    echo $user->introduce() . PHP_EOL;
}
