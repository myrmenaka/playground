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
    public function __construct(
        string $name,
        protected string $breed,
    ) {
        parent::__construct($name);  // 親のコンストラクタを呼んで $name を初期化
    }

    public function speak(): string
    {
        return "{$this->name}({$this->breed}): ワンワン!";
    }

    public function describe(): string
    {
        return "{$this->name}({$this->breed})が鳴きます";
    }
}

// 動作確認
$dog = new Dog('ポチ', '柴犬');
echo $dog->describe() . PHP_EOL;
echo $dog->speak() . PHP_EOL;