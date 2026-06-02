<?php

declare(strict_types=1);

// 1. 基本 Enum(Pure Enum)- 値を持たない
enum Suit
{
    case Hearts;
    case Diamonds;
    case Clubs;
    case Spades;
}

// 2. Backed Enum - スカラー値(string / int)を紐づける
enum Status: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
}

// 3. メソッドを持つ Enum
enum Priority: int
{
    case Low = 1;
    case Medium = 2;
    case High = 3;

    public function label(): string
    {
        return match ($this) {
            Priority::Low => '低',
            Priority::Medium => '中',
            Priority::High => '高',
        };
    }

    public function isUrgent(): bool
    {
        return $this === Priority::High;
    }
}

// --- 基本 Enum ---
$suit = Suit::Hearts;
echo $suit->name . PHP_EOL;        // Hearts

// --- Backed Enum ---
$status = Status::Active;
echo $status->name . PHP_EOL;      // Active
echo $status->value . PHP_EOL;     // active

// 文字列から Enum を復元(存在しなければ例外)
$restored = Status::from('suspended');
echo $restored->name . PHP_EOL;    // Suspended

// 存在しない値は tryFrom なら null
$maybe = Status::tryFrom('unknown');
var_dump($maybe);                  // NULL

// --- 全ケースを取得してループ ---
foreach (Priority::cases() as $priority) {
    echo $priority->name . ': ' . $priority->label() . PHP_EOL;
}

// --- メソッド呼び出し ---
$task = Priority::High;
echo $task->label() . PHP_EOL;     // 高
var_dump($task->isUrgent());       // bool(true)