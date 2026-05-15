<?php

declare(strict_types=1);

// PHP の `==` (緩い比較) と `===` (厳密な比較) の違いを5パターンで確認する

echo "=== Pattern 1: \"0\" == false ===\n";
// "0" は文字列、false は真偽値。
// == は型変換してから比較するので、両者が「偽とみなされる値」として一致してしまう。
var_dump("0" == false);   // true
var_dump("0" === false);  // false (型が違うので不一致)

echo "\n=== Pattern 2: \"abc\" == 0 ===\n";
// PHP 7 までは "abc" を 0 に変換して true になる有名な罠だった。
// PHP 8 からは挙動が変わり、数字ではない文字列と数値の比較は false になる。
var_dump("abc" == 0);     // false (PHP 8 以降)
var_dump("abc" === 0);    // false (型が違うので当然 false)

echo "\n=== Pattern 3: null == false ===\n";
// null も false も「偽とみなされる値」なので、緩い比較では一致する。
// しかし型は別物なので、厳密な比較では不一致。
var_dump(null == false);  // true
var_dump(null === false); // false

echo "\n=== Pattern 4: [] == false ===\n";
// 空配列も「偽とみなされる値」。
// このルールは isset() や empty() の挙動にも関係するので覚えておく。
var_dump([] == false);    // true
var_dump([] === false);   // false

echo "\n=== Pattern 5: \"10\" == 10 ===\n";
// 数値として解釈できる文字列と整数の比較。
// 緩い比較では数値に変換されて一致するが、厳密な比較では型が違うので不一致。
// フォームから受け取った値 ($_POST など) は常に文字列なので、ここを意識しないとバグる。
var_dump("10" == 10);     // true
var_dump("10" === 10);    // false

echo "\n=== まとめ ===\n";
echo "業務では原則 === を使う。型まで一致することを保証できるので安全。\n";
echo "== を使うのは「型が違ってもいいから値だけ見たい」という意図が明確なときだけ。\n";