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

$user = new User('Alice', 25);
echo $user->introduce() . PHP_EOL;