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

class Dog extends Animal
{
    public function __construct(
        string $name,
        protected string $breed,
    ){
        parent::__construct($name);
    }

    public function speak(): string
    {
        return "{$this->name}({$this->breed}): ワンワン！";
    }

    public function desctide(): string
    {
        return "{$this->name}({$this->breed})が鳴きます。";
    }
}

$dog = new Dog('ポチ', '柴犬');
echo $dog->speak() . PHP_EOL;
echo $dog->desctide() . PHP_EOL;
