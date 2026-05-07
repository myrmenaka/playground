# S20 解説: array_filter で偶数を抽出

## ポイント

### `array_filter` の基本構文

```php
array_filter(配列, コールバック関数)
```

- **配列**: フィルター対象の配列
- **コールバック関数**: 各要素に対して呼ばれ、`true` を返した要素だけが結果に残る

```php
$result = array_filter([1, 2, 3, 4, 5], fn($n) => $n > 2);
// [2 => 3, 3 => 4, 4 => 5]
```

「**条件を満たす要素だけを抜き出す**」というフィルター処理を1行で書ける。

### アロー関数 `fn` の構文

```php
fn($引数) => 式
```

PHP 7.4 で導入された短い無名関数。S15 の解説でも触れたが、ここでは実用例として登場。

```php
fn($n) => $n % 2 === 0   // $n を受け取って、偶数なら true を返す
```

特徴:
- **1行の式しか書けない**(複数行の処理は書けない)
- **return は不要**(自動で `=>` の右側の値が返る)
- **外側の変数を自動で取り込む**(クロージャと同じ挙動)

複雑な処理が必要なら、従来の無名関数 `function() { ... }` を使う。

### 重要な落とし穴: `array_filter` はキーを保持する

これが今回の最重要ポイント。

```php
$nums = [1, 2, 3, 4, 5, 6];
$result = array_filter($nums, fn($n) => $n % 2 === 0);

print_r($result);
// Array
// (
//     [1] => 2     ← キーが元のまま!
//     [3] => 4
//     [5] => 6
// )
```

`array_filter` は**元のキーをそのまま残す**仕様。S19 で手書きした実装は `0, 1, 2, ...` と振り直されていたので、挙動が違う。

### `array_values` でキーを振り直す

```php
$result = array_filter([1, 2, 3, 4, 5, 6], fn($n) => $n % 2 === 0);
$result = array_values($result);

print_r($result);
// Array
// (
//     [0] => 2     ← 振り直された
//     [1] => 4
//     [2] => 6
// )
```

`array_values` は配列のキーを捨てて、値だけを取り出して `0, 1, 2, ...` のキーで返す。`array_filter` とセットで使うのが定番パターン。

## 別解

### 関数の中で1行にまとめる

```php
function filterEven(array $numbers): array
{
    return array_values(array_filter($numbers, fn($n) => $n % 2 === 0));
}
```

中間変数を作らず、関数を入れ子で呼ぶ。短いが、内側から読む必要があるので可読性は好み。

### `ARRAY_FILTER_USE_KEY` でキーをフィルター

```php
// キーが偶数の要素だけを抽出
$result = array_filter(
    ['a' => 1, 'b' => 2, 'c' => 3],
    fn($key) => strlen($key) === 1,
    ARRAY_FILTER_USE_KEY
);
```

第3引数で「コールバックに何を渡すか」を指定できる。
- 省略時: 値のみ
- `ARRAY_FILTER_USE_KEY`: キーのみ
- `ARRAY_FILTER_USE_BOTH`: 値とキーの両方

実務ではあまり使わないが、知っておくと便利。

### コールバックを省略すると?

```php
$mixed = [0, 1, '', 'hello', null, false, 'world'];
$result = array_filter($mixed);
// [1 => 1, 3 => 'hello', 6 => 'world']
```

コールバックを省略すると、**falsy な値(`0`, `''`, `null`, `false`)を除外**する。「空っぽな値を取り除く」用途で便利。

## つまずきやすい点

### 1. キーが残ることに気づかず、後続のコードがバグる

```php
$result = array_filter([1, 2, 3, 4, 5], fn($n) => $n > 2);
// [2 => 3, 3 => 4, 4 => 5]

// JSON にすると...
echo json_encode($result);
// {"2":3,"3":4,"4":5}   ← オブジェクトとしてエンコードされる!
```

JavaScript で配列として受け取りたい場合、これは大きな問題。`array_values` でキーを振り直してから JSON にすれば配列としてエンコードされる。

```php
echo json_encode(array_values($result));
// [3,4,5]   ⭕ 配列として正しくエンコード
```

**API のレスポンスで配列を返す時は要注意**。Laravel でも頻出のバグパターン。

### 2. アロー関数の `=>` と配列の `=>` を混同する

```php
fn($n) => $n % 2 === 0          // ⭕ アロー関数
['key' => 'value']               // ⭕ 連想配列
fn($n) => $n => 'even'           // ❌ 構文混乱
```

どちらも `=>` を使うが、文脈で区別される。アロー関数では `=>` の右側に**1つの式**が来る。

### 3. アロー関数で複数行の処理を書こうとする

```php
fn($n) => {                       // ❌ ブロックは書けない
    if ($n % 2 === 0) {
        return true;
    }
    return false;
}

// 複数行が必要なら従来の無名関数を使う
function($n) {                    // ⭕
    if ($n % 2 === 0) {
        return true;
    }
    return false;
}
```

アロー関数は**式専用**。条件分岐があっても、三項演算子や `match` で1行に収められる場合のみ使える。

### 4. `array_values` を `array_value`(単数形)と書く

```php
array_values($result)   // ⭕
array_value($result)    // ❌ そんな関数は存在しない
```

PHP の組み込み関数は**末尾の s に注意**。`array_keys`, `array_values` も複数形(返すのが配列だから)、`array_key_exists` は単数形(キーの存在をチェックするから)。エディタの補完を信用すれば防げる。

## Java との対比

```java
// Java 8+ Stream
public static List<Integer> filterEven(List<Integer> numbers) {
    return numbers.stream()
        .filter(n -> n % 2 == 0)
        .collect(Collectors.toList());
}
```

```php
// PHP(array_filter + array_values)
function filterEven(array $numbers): array
{
    $filtered = array_filter($numbers, fn($n) => $n % 2 === 0);
    return array_values($filtered);
}
```

| 観点 | Java Stream | PHP array_filter |
|------|------------|------------------|
| ラムダ/アロー関数 | `n -> n % 2 == 0` | `fn($n) => $n % 2 === 0` |
| 結果のインデックス | 自動で `0, 1, 2, ...` | **元のキーが残る** |
| インデックスのリセット | 不要(Stream は元のキー概念がない) | `array_values` が必要 |
| メソッドチェーン | `.filter().collect()` | 関数を入れ子で呼ぶ |

**最大の違いはインデックスの扱い**。Java の Stream では filter 後のインデックスが自動的に振り直されるが、PHP の `array_filter` は元のキーを保持する。これは PHP の配列が「インデックス配列」と「連想配列」を区別しない設計の影響。

実務での使い分けの目安:
- **`array_filter`**: 単純な条件で要素を絞る時(可読性が高い)
- **`foreach`**: 副作用がある処理、複雑な条件分岐、エラー処理を含む場合(デバッグしやすい)
- **Laravel の Collection**: メソッドチェーンで複雑な変換をしたい時(`$collection->filter()->map()->values()` のように書ける)

Laravel では `Collection` が圧倒的に多用される。`Collection::filter()` も同じく元のキーを保持するので、**`->values()` をセットで呼ぶ**のが定番。

```php
// Laravel Collection
$result = collect([1, 2, 3, 4, 5, 6])
    ->filter(fn($n) => $n % 2 === 0)
    ->values();
```

Phase 2 で Eloquent と Collection を扱う時に、この知識がそのまま生きる。