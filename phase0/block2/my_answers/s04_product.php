<?php

declare(strict_types=1);

class Product 
{
    public function __construct(
        private string $name,
        private int $price,
    )
    {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}

$product = new Product("りんご", 150);
echo "商品名: {$product->getName()}\n価格: {$product->getPrice()}円\n";

$product->setPrice(200);
echo "価格を更新しました\n";
echo "新しい価格: {$product->getPrice()}円\n";
