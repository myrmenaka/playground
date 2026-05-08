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

class Cat extends Animal
{
    public function speak(): string
    {
        return "{$this->name}: ニャー";
    }
}

// 動作確認
$cat = new Cat('タマ');
echo $cat->speak() . PHP_EOL;