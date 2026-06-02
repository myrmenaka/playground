<?php

declare(strict_types=1);

// 基本 Enum（Pure Enum） - 値を持たない
enum Suit
{
    case Hearts;
    case Diamonds;
    case Clubs;
    case Spades;
}

// Backed Enum - スカラー値(string / int)を紐づける
enum Status: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
}

// メソッドを持つ Enum
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
$requested = Status::from('suspended');
echo $requested->name . PHP_EOL;    // Suspended

// 存在しない値は tryFrom なら null
$mayby = Status::tryFrom('unknown');
var_dump($mayby);                  // NULL

// --- 全ケースを取得してループ ---
foreach (Priority::cases() as $priority) {
    echo $priority->name . ': ' . $priority->label() . PHP_EOL;        // Low: 低, Medium: 中, High: 高
}

// --- メソッドを呼び出す ---
$task = Priority::High;
echo $task->label() . PHP_EOL;      // 高
var_dump($task->isUrgent());        // bool(true)

echo '--- 自力課題 ---' . PHP_EOL;

// Backed Enum int型
enum Weekday: int
{
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;
    case Saturday = 6;
    case Sunday = 7;

    public function isWeekend(): bool
    {
        // 土曜日と日曜日だけ true
        return $this === Weekday::Saturday || $this === Weekday::Sunday;
    }
}

// ループで各曜日について「曜日名: 平日/週末」を表示
foreach (Weekday::cases() as $day) {
    echo $day->name . ': ' . ($day->isWeekend() ? '週末' : '平日') . PHP_EOL;
}

