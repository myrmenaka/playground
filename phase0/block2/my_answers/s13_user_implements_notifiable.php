<?php

declare(strict_types=1);

interface Notifiable 
{
    public function notify(): string;
}

class User implements Notifiable 
{
    public function __construct(
        private string $name,
    ) {
    }

    public function notify(): string
    {
        return "{$this->name} さんに通知を送りました";
    }
}

$user = new User('山田太郎');
echo $user->notify() . PHP_EOL;
