<?php

declare(strict_types=1);

// declare(strict_types=1) を「書く」パターン

function add(int $a, int $b): int
{
    return $a + $b;
}

// 正常
echo "add(1, 2) = ";
var_dump(add(1, 2));

// 数値として解釈される文字列
echo "\nadd(\"3\", \"4\") = ";
try {
    var_dump(add("3", "4"));
} catch (TypeError $e) {
    echo "TypeError: " . $e->getMessage() . "\n";
}

// 数値として解釈されない文字列
echo "\nadd(\"abc\", \"def\") = ";
try {
    var_dump(add("abc", "def"));
} catch (TypeError $e) {
    echo "TypeError: " . $e->getMessage() . "\n";
}