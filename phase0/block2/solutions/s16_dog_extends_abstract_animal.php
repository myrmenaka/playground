<?php

declare(strict_types=1);

abstract class AbstractAnimal
{
    public function __construct(
        protected string $name,
    ) {
    }

    abstract public function speak(): string;

    public function introduce(): string
    {
        return "私は {$this->name} です。{$this->speak()}";
    }
}

class Dog extends AbstractAnimal
{
    public function speak(): string
    {
        return "ワン!";
    }
}

$dog = new Dog('ポチ');
echo $dog->speak() . PHP_EOL;
echo $dog->introduce() . PHP_EOL;