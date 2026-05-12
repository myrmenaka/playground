<?php

declare(strict_types=1);

class OutOfStockException extends \Exception
{
}

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

    public function receive(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("数量は1以上で指定してください");
        }

        if ($this->discontinued) {
            throw new ProductDiscontinuedException("この商品は廃番です");
        }

        $this->stock += $quantity;
        echo "入荷完了" . PHP_EOL;
    }

    public function ship(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException("数量は1以上で指定してください");
        }

        if ($this->discontinued) {
            throw new ProductDiscontinuedException("この商品は廃番です");
        }

        if ($this->stock < $quantity) {
            throw new OutOfStockException("在庫が不足しています(在庫: {$this->stock}個、要求: {$quantity}個)");
        }

        $this->stock -= $quantity;
        echo "出荷完了" . PHP_EOL;
    }

    public function discontinue(): void
    {
        $this->discontinued = true;
    }

    public function getStock(): int
    {
        return $this->stock;
    }
}

$product = new Product("ノートPC");

try {
    $product->receive(0);
} catch (\InvalidArgumentException $e) {
    echo "エラー(引数不正): " . $e->getMessage() . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}

try {
    $product->receive(10);
} catch (\InvalidArgumentException $e) {
    echo "エラー(引数不正): " . $e->getMessage() . PHP_EOL;
} catch (ProductDiscontinuedException $e) {
    echo "エラー(廃番): " . $e->getMessage() . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}

try {
    $product->ship(15);
} catch (\InvalidArgumentException $e) {
    echo "エラー(引数不正): " . $e->getMessage() . PHP_EOL;
} catch (ProductDiscontinuedException $e) {
    echo "エラー(廃番): " . $e->getMessage() . PHP_EOL;
} catch (OutOfStockException $e) {
    echo "エラー(在庫不足): " . $e->getMessage() . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}

try {
    $product->ship(5);
} catch (\InvalidArgumentException $e) {
    echo "エラー(引数不正): " . $e->getMessage() . PHP_EOL;
} catch (ProductDiscontinuedException $e) {
    echo "エラー(廃番): " . $e->getMessage() . PHP_EOL;
} catch (OutOfStockException $e) {
    echo "エラー(在庫不足): " . $e->getMessage() . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}

$product->discontinue();

try {
    $product->receive(3);
} catch (\InvalidArgumentException $e) {
    echo "エラー(引数不正): " . $e->getMessage() . PHP_EOL;
} catch (ProductDiscontinuedException $e) {
    echo "エラー(廃番): " . $e->getMessage() . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}