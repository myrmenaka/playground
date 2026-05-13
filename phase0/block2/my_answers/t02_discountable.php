<?php

declare(strict_types=1);

// インターフェース：Discountable
interface Discountable 
{
    // 元の価格を受け取り、割引適用後の価格を返す
    public function applyDiscount(int $price): int;
}

// クラス：Member implements Discountable
class Member implements Discountable 
{
    // 10% 引きの価格を返す
    public function applyDiscount(int $price): int 
    {
        return (int)($price * 0.9);
    }
}
// クラス：VIP implements Discountable
class VIP implements Discountable 
{
    // 20% 引きの価格を返す
    public function applyDiscount(int $price): int 
    {
        return (int)($price * 0.8);
    }
}

// インスタンス作成
$member = new Member();
echo "Member: 1000円 → {$member->applyDiscount(1000)}円" . PHP_EOL;

$vip = new VIP();
echo "VIP: 1000円 → {$vip->applyDiscount(1000)}円" . PHP_EOL;

echo PHP_EOL;

// foreach ループでインターフェースを利用
$discountables = [$member, $vip];
echo "【全会員の割引価格】" . PHP_EOL;
foreach ($discountables as $discountable) {
    echo get_class($discountable) . ": {$discountable->applyDiscount(1000)}円" . PHP_EOL;
}
