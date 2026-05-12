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
            throw new \Exception("残高不足です(残高: {$this->balance}円、出金額: {$amount}円)");
        }
        $this->balance -= $amount;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }
}

$account = new BankAccount(1000);

$account->deposit(500);
echo "入金後の残高: {$account->getBalance()}円" . PHP_EOL;

$account->withdraw(800);
echo "出金後の残高: {$account->getBalance()}円" . PHP_EOL;

try {
    $account->withdraw(1000);
} catch (\Exception $e) {
    echo "エラー: " . $e->getMessage() . PHP_EOL;
}