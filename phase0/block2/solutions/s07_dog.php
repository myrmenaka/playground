<?php

declare(strict_types=1);

class Animal
{
    public function __construct(
        protected string $name,
    ) {}

    public function speak(): string
    {
        return "{$this->name}が鳴きます";
    }
}

class Dog extends Animal
{
    public function speak(): string
    {
        return "{$this->name}: ワンワン!";
    }
}

// 動作確認
$dog = new Dog('ポチ');
echo $dog->speak() . PHP_EOL;