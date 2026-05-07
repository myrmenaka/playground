<?php

declare(strict_types=1);

class User
{
    public function __construct(
        private string $name,
        private int $age,
    ) {
    }

    public function introduce(): string
    {
        return "私の名前は{$this->name}、年齢は{$this->age}歳です。";
    }
}

$user = new User('Alice', 25);
echo $user->introduce() . PHP_EOL;