# S18 解説: 配列の合計を返す関数

## ポイント

### 配列を引数に取る関数

```php
function sum(array $nums): int {
    // 関数内で $nums を配列として扱える
}
```

`array` 型を宣言することで、引数が配列であることを保証する。strict_types 有効下では、配列以外を渡すと `TypeError` になる。

### 「初期化 → 加算 → return」の3ステップ

```php
$total = 0;                      // ① 累積用の変数を初期化
foreach ($nums as $num) {        // ② 各要素を取り出して
    $total += $num;              //    累積する
}
return $total;                   // ③ 最終結果を返す
```

これは集計処理の典型的なパターン(**畳み込み**と呼ばれる)。「合計」「最大値」「個数」などの集計はすべて同じ構造で書ける。

### 複合代入演算子 `+=`

```php
$total += $num;     // $total = $total + $num; と同じ
```

Java と同じ。他にも `-=`, `*=`, `/=`, `%=`, `.=`(文字列連結)などがある。

## 別解

### 組み込み関数 `array_sum` を使う

```php
function sum(array $nums): int {
    return array_sum($nums);
}
```

PHPには「**配列の合計を求める**」という超頻出処理のために `array_sum` が用意されている。実務ではこちらを使うのが圧倒的に多い。今回は `foreach` を使う訓練のため、あえて手書きしている。

### `array_reduce` で書く(関数型的)

```php
function sum(array $nums): int {
    return array_reduce($nums, fn($carry, $item) => $carry + $item, 0);
}
```

`array_reduce` は「畳み込み」を抽象化した関数。Java Stream の `reduce` と同じ概念。最初の引数が累積、2番目が現在の要素、第3引数 `0` が初期値。

少し難しいが、Laravel のコレクション操作で頻出するので「こういう書き方もある」と覚えておくとよい。

### 早期リターンで空配列対応

```php
function sum(array $nums): int {
    if (empty($nums)) {
        return 0;
    }
    
    $total = 0;
    foreach ($nums as $num) {
        $total += $num;
    }
    return $total;
}
```

実際には今回の実装でも空配列で 0 が返るので不要だが、「ガード節」として冒頭で特殊ケースを処理する書き方は読みやすさを上げる。実務でよく使うパターン。

## つまずきやすい点

### 1. 累積変数の初期化忘れ

```php
function sum(array $nums): int {
    foreach ($nums as $num) {
        $total += $num;   // ❌ $total が未定義
    }
    return $total;        // ❌ 未定義変数を返す
}
```

`$total = 0;` の初期化を忘れると、`Notice: Undefined variable` が出る(strict_types でも、これは型エラーではないので Notice 止まり)。**累積変数は必ずループ前に初期化**する。

### 2. return の位置を間違える

```php
function sum(array $nums): int {
    $total = 0;
    foreach ($nums as $num) {
        $total += $num;
        return $total;   // ❌ 1回目のループで関数を抜けてしまう
    }
}
```

`return` をループの中に書くと、最初の要素を加算した時点で関数が終わってしまう。**`return` はループの外**に書く。

### 3. 配列要素の型が int でない場合

```php
declare(strict_types=1);

function sum(array $nums): int {
    $total = 0;
    foreach ($nums as $num) {
        $total += $num;
    }
    return $total;
}

sum([1.5, 2.5, 3]);   // ❓ $total が float になる
```

`array` 型は中身の型までは制限できない。`int|float` が混ざった配列を渡すと、累積結果が `float` になり、戻り値の型 `int` と矛盾して `TypeError` になる(strict_types 有効下)。

実務ではこの問題を防ぐため、**ジェネリクス相当の表記**(PHPDoc の `@param int[] $nums`)で配列の中身の型を示すことが多い。これは PHPStan などの静的解析ツールがチェックしてくれる。

```php
/**
 * @param int[] $nums
 */
function sum(array $nums): int {
    // ...
}
```

PHP 言語自体にはジェネリクスがないので、コメントで補う形になる。

### 4. 命名の慣習

引数名は**複数形**、ループの要素は**単数形**で対応させる。

```php
function sum(array $nums): int {
    foreach ($nums as $num) {       // ⭕ 複数形 → 単数形
        // ...
    }
}

function sum(array $num): int {
    foreach ($num as $value) {      // △ 配列名が単数形だと違和感
        // ...
    }
}
```

これは Java でも同じ慣習(`for (Item item : items)`)。コードを読む人が「これはコレクション、これは1つの要素」と直感的に理解できるかどうかの差。

## Java との対比

```java
// Java
public static int sum(int[] nums) {
    int total = 0;
    for (int num : nums) {
        total += num;
    }
    return total;
}

System.out.println(sum(new int[]{1, 2, 3, 4, 5}));   // 15
```

```php
// PHP
declare(strict_types=1);

function sum(array $nums): int {
    $total = 0;
    foreach ($nums as $num) {
        $total += $num;
    }
    return $total;
}

echo sum([1, 2, 3, 4, 5]) . PHP_EOL;   // 15
```

| 観点 | Java | PHP |
|------|------|-----|
| 配列の型 | `int[]`(中身の型まで指定) | `array`(中身の型は指定不可) |
| 配列リテラル | `new int[]{1, 2, 3}` または `{1, 2, 3}` | `[1, 2, 3]` |
| ループ | 拡張for文 | `foreach` |
| 累積処理 | 同じパターン | 同じパターン |

**ロジック構造はほぼ同じ**。違いは構文の細部(`$` の有無、配列リテラル、ループキーワード)のみ。Java で書ける人がPHPで詰まるのは、ほぼこの「**構文の差分**」だけと言ってよい。Phase 0 はその差分を体に染み込ませる作業。

実務では `array_sum` のような組み込み関数を使う場面がほとんどだが、**手書きできることが土台**になる。Laravel のコレクション(`Collection::sum()`, `Collection::reduce()` など)も、内部的にはこの「初期化 → ループ → 累積 → 返す」というパターンで実装されている。