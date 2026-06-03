<?php

declare(strict_types=1);

// 在庫状態
Enum StockStatus
{
    case InStock; // 在庫あり
    case LowStock; // 在庫少ない
    case OutOfStock; // 在庫なし
}

// 在庫状態を処理するクラス
class Product
{
    // 在庫状態を保持するプロパティ
    private ?StockStatus $status = null;

    // 商品名、数量を受け取るコンストラクタ
    public function __construct(
        public string $name,
        protected int $quantity, 
    ) {
    }

    // quantityに応じてstatusを更新するメソッド
    public function refreshStatus(): void
    {
        if ($this->quantity === 0) {
            $this->status = StockStatus::OutOfStock;
        } elseif ($this->quantity <= 5) { // 境界値注意
            $this->status = StockStatus::LowStock;
        } else {
            $this->status = StockStatus::InStock;
        }
    }

    // 現在のstatusに応じた説明を返すメソッド
    public function describe(): string
    {
        if ($this->status === null) {
            return "状態未設定";
        }

        return match ($this->status) {
            StockStatus::OutOfStock => "在庫切れ",
            StockStatus::LowStock => "残りわずか(あと{$this->quantity}個)",
            StockStatus::InStock => "在庫あり({$this->quantity}個)",
        };
    }
}

// 出力
$pc = new Product("ノートPC", 10);
$pc->refreshStatus();
echo "{$pc->name}: {$pc->describe()}" . PHP_EOL; // 在庫あり(10個)

$mause = new Product("マウス", 3);
$mause->refreshStatus();
echo "{$mause->name}: {$mause->describe()}" . PHP_EOL; // 残りわずか(あと3個)

$keyboard = new Product("キーボード", 0);
$keyboard->refreshStatus();
echo "{$keyboard->name}: {$keyboard->describe()}" . PHP_EOL; // 在庫切れ

$heading = new Product("ヘッドホン", 8);
echo "{$heading->name}: {$heading->describe()}" . PHP_EOL; // 状態未設定

