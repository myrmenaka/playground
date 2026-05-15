<?php

// このファイルは declare(strict_types=1) を「書かない」パターン
// PHP のデフォルトである「強制モード」で動作する

function add(int $a, int $b): int
{
    return $a + $b;
}

echo "=== 強制モード(デフォルト)の挙動 ===\n";

// 1. 正常系
echo "add(1, 2) = ";
var_dump(add(1, 2));

// 2. 数値として解釈できる文字列
//    強制モードでは "3" -> 3, "4" -> 4 に自動変換されて動く
echo "\nadd(\"3\", \"4\") = ";
var_dump(add("3", "4"));

// 3. 数値として解釈できない文字列
//    強制モードでも、これは TypeError になる
echo "\nadd(\"abc\", \"def\") = ";
try {
    var_dump(add("abc", "def"));
} catch (TypeError $e) {
    echo "TypeError: " . $e->getMessage() . "\n";
}