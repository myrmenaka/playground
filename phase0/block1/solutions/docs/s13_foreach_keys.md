# S13 解説: foreach でキーと値を同時に取得

## ポイント

### `as $key => $value` 構文

```php
foreach ($配列 as $キー変数 => $値変数) {
    // $キー変数 と $値変数 の両方が使える
}
```

S12 の `foreach ($配列 as $要素変数)` の拡張版。`=>` の左側がキー、右側が値を受け取る変数。変数名は自由(`$k => $v` でも `$key => $value` でもOK)。

### 連想配列でもインデックス配列でも使える

```php
// 連想配列(キーは文字列)
$user = ['name' => 'Alice', 'age' => 25];
foreach ($user as $key => $value) {
    // $key = 'name', 'age'
    // $value = 'Alice', 25
}

// インデックス配列(キーは0からの整数)
$fruits = ['apple', 'banana', 'cherry'];
foreach ($fruits as $key => $value) {
    // $key = 0, 1, 2
    // $value = 'apple', 'banana', 'cherry'
}
```

インデックス配列でも、何番目の要素かを取得したい時に便利。

## 別解

### 文字列連結を `sprintf` で書く

```php
foreach ($user as $key => $value) {
    echo sprintf('%s: %s%s', $key, $value, PHP_EOL);
}
```

書式指定が複雑になる場合は `sprintf` のほうが読みやすい。

### ダブルクォートで変数展開

```php
foreach ($user as $key => $value) {
    echo "{$key}: {$value}\n";
}
```

`{}` で変数を囲むと、変数の境界が明確になる。配列要素やオブジェクトのプロパティを展開する時に必須。

### `array_keys` と `array_values` を別々に取る(推奨しない)

```php
$keys = array_keys($user);
$values = array_values($user);
for ($i = 0; $i < count($keys); $i++) {
    echo $keys[$i] . ': ' . $values[$i] . PHP_EOL;
}
```

動くが冗長で読みにくい。`foreach` の `=>` を使うべき。

## つまずきやすい点

### 1. `=>` を `->` と混同する

```php
foreach ($user as $key => $value)   // ⭕ 配列のキーと値
$object->property                   // ⭕ オブジェクトのプロパティアクセス

foreach ($user as $key -> $value)   // ❌ 構文エラー
```

`=>` は配列専用、`->` はオブジェクト専用。Java にはどちらもないので最初は混乱しがち。

### 2. 値だけほしいのにキーも書いてしまう

```php
// 値だけほしい場合は $key 不要
foreach ($fruits as $key => $fruit) {  // △ 動くが $key を使わないなら冗長
    echo $fruit . PHP_EOL;
}

foreach ($fruits as $fruit) {           // ⭕ こちらが正解
    echo $fruit . PHP_EOL;
}
```

使わない変数を書くと「これは何に使うの?」と読み手が混乱する。必要な時だけ書く。

### 3. 連想配列の順序

PHPの連想配列は**挿入順を保持**する。`foreach` は定義した順で要素を走査する。Java の `LinkedHashMap` と同じ挙動。

```php
$user = ['name' => 'Alice', 'age' => 25];
// foreach すると必ず name → age の順
```

ただし、ソートしたい場合は `ksort()`(キーでソート)や `asort()`(値でソート)を使う。

## Java との対比

```java
// Java の Map を走査
Map<String, Object> user = new LinkedHashMap<>();
user.put("name", "Alice");
user.put("age", 25);

for (Map.Entry<String, Object> entry : user.entrySet()) {
    System.out.println(entry.getKey() + ": " + entry.getValue());
}
```

```php
// PHP の連想配列を走査
$user = [
    'name' => 'Alice',
    'age' => 25,
];

foreach ($user as $key => $value) {
    echo $key . ': ' . $value . PHP_EOL;
}
```

| 観点 | Java | PHP |
|------|------|-----|
| データ構造 | `Map<K, V>`(専用クラス) | 連想配列(`array` の一形態) |
| 走査構文 | `entrySet()` を使う必要あり | `as $key => $value` で直接 |
| キーと値の取り出し | `entry.getKey()`, `entry.getValue()` | 変数として直接受け取る |
| 順序保証 | `LinkedHashMap` で保証 | デフォルトで保証 |

PHP は連想配列の扱いが Java より直感的でシンプル。`Map.Entry` のような中間オブジェクトを意識する必要がない分、コードが短く書ける。Laravel ではコレクション操作で頻繁に登場するので、`foreach ($items as $key => $item)` の形は完全に体に染み込ませておきたい。