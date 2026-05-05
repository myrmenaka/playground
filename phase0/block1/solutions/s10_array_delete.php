<?php

$arr = [10, 20, 30, 40, 50];

// パターン1: unset(指定したキーを削除、キーは詰められない)
unset($arr[1]);
print_r($arr);

// パターン2: array_pop(末尾を削除、戻り値で削除した値を取得)
$removed = array_pop($arr);
print_r($arr);

echo "削除した値: {$removed}" . PHP_EOL;