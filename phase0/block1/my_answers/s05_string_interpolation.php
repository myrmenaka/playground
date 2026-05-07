<?php

$fruit = "りんご";
$price = 150;

echo "[1]商品：" . $fruit . "、価格：" . $price . "円" . PHP_EOL;
echo "[2]商品：{$fruit}、価格：{$price}円" . PHP_EOL;
echo sprintf("[3]商品：%s、価格：%d円", $fruit, $price) . PHP_EOL;
