<?php

declare(strict_types=1);

class Animal
{
    public function __construct(
        protected string $name,
    ) {
    }

    public function speak(): string
    {
        return "{$this->name}が鳴きます";
    }
}

class Dog extends Animal
{
    public function speak(): string
    {
        return "{$this->name}: ワンワン！";
    }
}

class Cat extends Animal
{
    public function speak(): string
    {
        return "{$this->name}: ニャー";
    }
}

$animals = [
    new Dog('ポチ'),
    new Dog('クロ'),
    new Cat('タマ'),
    new Cat('ミケ'),
];

foreach ($animals as $animal) {
    echo $animal->speak() . PHP_EOL;
}
