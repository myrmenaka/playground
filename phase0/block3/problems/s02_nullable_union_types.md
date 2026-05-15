# S2: nullable 型と Union 型を使った関数

## 目的

PHP の型宣言のうち、特に業務で頻出する2つを身体に入れる:

- **nullable 型** (`?string`): 「文字列、または null」を許容する型
- **Union 型** (`int|string`): 「整数、または文字列」のように複数の型を許容する型

## ファイル名

`my_answers/s02_nullable_union_types.php`

## 取り組み方

このStepは **写経 → 再現** 方式です。

1. `solutions/s02_nullable_union_types.php` を見ながら `my_answers/s02_nullable_union_types.php` に書き写す
2. 実行して結果を確認する: `php my_answers/s02_nullable_union_types.php`
3. ファイルを閉じて、何も見ずに再現する

## 書く関数

### 1. nullable 型を使った関数: `findUserName(?int $id): ?string`

ユーザーIDからユーザー名を返す関数。

- 引数 `$id` は `int` または `null`
- 戻り値は `string`(見つかった場合)または `null`(見つからない、または `$id` が null の場合)
- 内部で簡単な連想配列をユーザー一覧として持つ

### 2. Union 型を使った関数: `formatId(int|string $id): string`

ID を整形して文字列で返す関数。

- 引数 `$id` は `int` または `string`
- 戻り値は必ず `string`
- 整数なら `"ID-0001"` のようにゼロパディングして返す
- 文字列なら `"ID-<そのまま>"` で返す
- 内部で `is_int()` / `is_string()` で型分岐する

## 出力例

```
=== nullable 型のテスト ===
findUserName(1)    : string(5) "Alice"
findUserName(99)   : NULL          (存在しないID)
findUserName(null) : NULL          (null を渡した)

=== Union 型のテスト ===
formatId(1)       : string(7) "ID-0001"
formatId(42)      : string(7) "ID-0042"
formatId("abc")   : string(6) "ID-abc"
```

## ゴール

このStepが終わった時に、以下を口頭で説明できる:

- `?string` と `string|null` は何が違うか(実は同じ、表記が違うだけ)
- nullable 型はどんな場面で使うか
- Union 型はどんな場面で使うか
- 型分岐するときに使う関数(`is_int`, `is_string` など)