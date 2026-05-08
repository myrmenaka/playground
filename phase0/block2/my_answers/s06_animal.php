<?php

declare(strict_types=1);

class Animal 
{
    public function __construct(
        protected string $name,
    )
    {}

    public function speak(): string
    {
        return "{$this->name}が鳴きます";
    }
}

$animal = new Animal('動物');
echo $animal->speak() . PHP_EOL;