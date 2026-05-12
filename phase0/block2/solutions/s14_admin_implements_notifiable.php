<?php

declare(strict_types=1);

interface Notifiable
{
    public function notify(): string;
}

class Admin implements Notifiable
{
    public function __construct(
        private string $name,
        private string $department,
    ) {
    }

    public function notify(): string
    {
        return "【管理者通知】{$this->department} の {$this->name} さんへ通知を送信しました";
    }
}

$admin = new Admin('鈴木花子', '営業部');
echo $admin->notify() . PHP_EOL;