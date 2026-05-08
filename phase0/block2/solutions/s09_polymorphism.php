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

class Cat extends Animal
{
    public function speak(): string
    {
        return "{$this->name}: ニャー";
    }
}

// 動物を1つの配列に混ぜて入れる
$animals = [
    new Dog('ポチ'),
    new Cat('タマ'),
    new Dog('シロ'),
    new Cat('ミケ'),
];

// 共通の親型として統一的に扱う
foreach ($animals as $animal) {
    echo $animal->speak() . PHP_EOL;
}