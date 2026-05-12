<?php

declare(strict_types=1);

// 在庫不足
class OutOfStockException extends \Exception
{
}
// 廃番
class ProductDiscontinuedException extends \Exception
{
}

class Product
{
    private int $stock = 0;
    private bool $discontinued = false;

    public function __construct(
        private string $name,
    ) {
    }

    // 入荷
    public function receive(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("数量は1以上で指定してください");
        }

        if ($this->discontinued === true) {
            throw new ProductDiscontinuedException("この商品は廃番です");
        }

        $this->stock += $quantity;
        echo "入荷完了" . PHP_EOL;
    }

    // 出荷
    public function ship(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("数量は1以上で指定してください");
        }

        if ($this->discontinued === true) {
            throw new ProductDiscontinuedException("この商品は廃番です");
        }

        if ($this->stock < $quantity) {
            throw new OutOfStockException("在庫が不足しています(在庫: {$this->stock}個、要求: {$quantity}個)");
        }

        $this->stock -= $quantity;
        echo "出荷完了" . PHP_EOL;
    }
    
    // 廃番にする
    public function discontinue(): void
    {
        $this->discontinued = true;
    }

    // 在庫数を返す
    public function getStock(): int
    {
        return $this->stock;
    }
}

$product = new Product("ノートPC");

// 1 例外
try {
    $product->receive(0);
} catch (\InvalidArgumentException $e) {
    echo "エラー(引数不正): {$e->getMessage()}" . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}

// 2
try {
    $product->receive(10);
} catch (\InvalidArgumentException $e) {
    echo "エラー(引数不正): {$e->getMessage()}" . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}

// 3 例外
try {
    $product->ship(15);
} catch (OutOfStockException $e) {
    echo "エラー(在庫不足): {$e->getMessage()}" . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}

// 4
try {
    $product->ship(5);
} catch (OutOfStockException $e) {
    echo "エラー(在庫不足): {$e->getMessage()}" . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}

// 5
$product->discontinue();

// 6 例外
try {
    $product->receive(3);
} catch (ProductDiscontinuedException $e) {
    echo "エラー(廃番): {$e->getMessage()}" . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}