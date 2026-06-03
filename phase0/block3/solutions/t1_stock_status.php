<?php

declare(strict_types=1);

enum StockStatus
{
    case InStock;
    case LowStock;
    case OutOfStock;
}

class Product
{
    private ?StockStatus $status = null;

    public function __construct(
        public string $name,
        protected int $quantity,
    ) {
    }

    public function refreshStatus(): void
    {
        if ($this->quantity === 0) {
            $this->status = StockStatus::OutOfStock;
        } elseif ($this->quantity <= 5) {
            $this->status = StockStatus::LowStock;
        } else {
            $this->status = StockStatus::InStock;
        }
    }

    public function describe(): string
    {
        if ($this->status === null) {
            return '状態未設定';
        }

        return match ($this->status) {
            StockStatus::OutOfStock => '在庫切れ',
            StockStatus::LowStock => "残りわずか（あと{$this->quantity}個）",
            StockStatus::InStock => "在庫あり（{$this->quantity}個）",
        };
    }
}

$pc = new Product('ノートPC', 10);
$pc->refreshStatus();
echo "{$pc->name}: {$pc->describe()}" . PHP_EOL;

$mouse = new Product('マウス', 3);
$mouse->refreshStatus();
echo "{$mouse->name}: {$mouse->describe()}" . PHP_EOL;

$keyboard = new Product('キーボード', 0);
$keyboard->refreshStatus();
echo "{$keyboard->name}: {$keyboard->describe()}" . PHP_EOL;

$headphone = new Product('ヘッドホン', 8);
echo "{$headphone->name}: {$headphone->describe()}" . PHP_EOL;