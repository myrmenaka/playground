<?php

declare(strict_types=1);

// ============================================
// Step 1: 登場人物
// ============================================
// インターフェース: Discountable
// クラス: Member(10%引き)
// クラス: VIP(20%引き)

// ============================================
// Step 2: メソッド一覧
// ============================================
// Discountable::applyDiscount(int $price): int
// Member::applyDiscount(int $price): int     ← 10%引き
// VIP::applyDiscount(int $price): int        ← 20%引き

// ============================================
// Step 3 以降: 実装
// ============================================

interface Discountable
{
    /**
     * 元の価格を受け取り、割引適用後の価格を返す
     */
    public function applyDiscount(int $price): int;
}

class Member implements Discountable
{
    public function applyDiscount(int $price): int
    {
        return (int)($price * 0.9);
    }
}

class VIP implements Discountable
{
    public function applyDiscount(int $price): int
    {
        return (int)($price * 0.8);
    }
}

// ============================================
// 動作確認(正常系のみ)
// ============================================

$member = new Member();
echo "Member: 1000円 → {$member->applyDiscount(1000)}円" . PHP_EOL;

$vip = new VIP();
echo "VIP: 1000円 → {$vip->applyDiscount(1000)}円" . PHP_EOL;

echo PHP_EOL;

// ボーナス: ポリモーフィズムの体感
// 配列の中身が Member か VIP かを気にせず、同じ applyDiscount() が呼べる。
// 将来 Premium や Student クラスが追加されても、この foreach は変更不要。
$discountables = [$member, $vip];

echo "【全会員の割引価格】" . PHP_EOL;
foreach ($discountables as $discountable) {
    echo get_class($discountable) . ": {$discountable->applyDiscount(1000)}円" . PHP_EOL;
}