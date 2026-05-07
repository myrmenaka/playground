<?php

declare(strict_types=1);

class User 
{
    private string $name;
    private int $age;

    public function __construct(string $name, int $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function introduce(): string
    {
        return "私の名前は{$this->name}、年齢は{$this->age}歳です。";
    }
}

$users = [
    new User("Alice", 30),
    new User("Bob", 25),
    new User("Charlie", 35),
];

foreach ($users as $user) {
    echo $user->introduce() . PHP_EOL;
}

