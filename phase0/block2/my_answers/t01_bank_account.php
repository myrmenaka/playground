<?php

declare(strict_types=1);

class BankAccount 
{
    public function __construct(
        private int $balance,
    ) {
    }

    public function deposit(int $amount): void
    {
        $this->balance += $amount;
    }

    public function withdraw(int $amount): void
    {
        if ($amount > $this->balance) {
            throw new \Exception("エラー: 残高不足です(残高: {$this->balance}円、出金額: {$amount}円)");
        }
        $this->balance -= $amount;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }
}

$bank = new BankAccount(1000);
$bank->deposit(500);
echo "入金後の残高: {$bank->getBalance()}円" . PHP_EOL;

$bank->withdraw(800);
echo "出金後の残高: {$bank->getBalance()}円" . PHP_EOL;

try {
    $bank->withdraw(1000);
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}