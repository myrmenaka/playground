<?php

declare(strict_types=1);

class User
{
    public function __construct(
        private string $name,
        private int $age,
    ) {}

    public function introduce(): string
    {
        return "私は{$this->name}です。{$this->age}歳です。";
    }
}

// ユーザーを5人作って配列に入れる
$users = [
    new User('田中', 25),
    new User('鈴木', 30),
    new User('佐藤', 28),
    new User('山田', 35),
    new User('伊藤', 22),
];

// 全員の自己紹介を出力
foreach ($users as $user) {
    echo $user->introduce() . PHP_EOL;
}