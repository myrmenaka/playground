<?php

declare(strict_types=1);

// nullable 型 (?int, ?string) を使った関数
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

// Union 型 (int|string) を使った関数
function formatId(int|string $id): string
{
    // int の場合: 4桁ゼロパディング
    if (is_int($id)) {
        return sprintf('ID-%04d', $id);
    }

    return 'ID-' . $id;
}

echo "=== nullable 型のテスト ===\n";
var_dump(findUserName(1));    // string(5) "Alice"
var_dump(findUserName(99));   // NULL (存在しないID)
var_dump(findUserName(null)); // NULL (null を渡した)   

echo "\n=== Union 型のテスト ===\n";
var_dump(formatId(1));      // string(7) "ID-0001"
var_dump(formatId(42));     // string(7) "ID-0042"
var_dump(formatId("abc"));  // string(6) "ID-abc"
