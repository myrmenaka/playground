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