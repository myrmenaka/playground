<?php

declare(strict_types=1);

// このファイルは declare(strict_types=1) を「書く」パターン
// 「厳密モード」で動作する

function add(int $a, int $b): int
{
    return $a + $b;
}

echo "=== 厳密モードの挙動 ===\n";

// 1. 正常系
echo "add(1, 2) = ";
var_dump(add(1, 2));

// 2. 数値として解釈できる文字列
//    厳密モードでは型変換しないので、TypeError になる
echo "\nadd(\"3\", \"4\") = ";
try {
    var_dump(add("3", "4"));
} catch (TypeError $e) {
    echo "TypeError: " . $e->getMessage() . "\n";
}

// 3. 数値として解釈できない文字列
//    こちらも当然 TypeError
echo "\nadd(\"abc\", \"def\") = ";
try {
    var_dump(add("abc", "def"));
} catch (TypeError $e) {
    echo "TypeError: " . $e->getMessage() . "\n";
}