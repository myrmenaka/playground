<?php

$name = "りんご";
$price = 150;

// パターン1: 連結演算子
echo "[1] 商品: " . $name . "、価格: " . $price . "円" . PHP_EOL;

// パターン2: ダブルクォート内の変数展開
echo "[2] 商品: {$name}、価格: {$price}円" . PHP_EOL;

// パターン3: sprintf
echo sprintf("[3] 商品: %s、価格: %d円", $name, $price) . PHP_EOL;