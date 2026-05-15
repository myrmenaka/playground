<?php

declare(strict_types=1);

/**
 * nullable 型 (?int, ?string) を使った関数
 *
 * 引数 $id が null の場合、または該当ユーザーがいない場合は null を返す。
 * 見つかった場合はユーザー名(string)を返す。
 */
function findUserName(?int $id): ?string
{
    // 簡易的なユーザー一覧
    $users = [
        1 => 'Alice',
        2 => 'Bob',
        3 => 'Charlie',
    ];

    // null チェック
    if ($id === null) {
        return null;
    }

    // 存在チェック
    if (!isset($users[$id])) {
        return null;
    }

    return $users[$id];
}

/**
 * Union 型 (int|string) を使った関数
 *
 * int が渡された場合はゼロパディングして "ID-0001" の形式に整形。
 * string が渡された場合はそのまま "ID-<文字列>" の形式に整形。
 */
function formatId(int|string $id): string
{
    if (is_int($id)) {
        // int の場合: 4桁ゼロパディング
        return sprintf('ID-%04d', $id);
    }

    // string の場合: そのまま付ける
    return 'ID-' . $id;
}

// === 動作確認 ===

echo "=== nullable 型のテスト ===\n";
var_dump(findUserName(1));    // string(5) "Alice"
var_dump(findUserName(99));   // NULL (存在しないID)
var_dump(findUserName(null)); // NULL (null を渡した)

echo "\n=== Union 型のテスト ===\n";
var_dump(formatId(1));      // string(7) "ID-0001"
var_dump(formatId(42));     // string(7) "ID-0042"
var_dump(formatId("abc"));  // string(6) "ID-abc"