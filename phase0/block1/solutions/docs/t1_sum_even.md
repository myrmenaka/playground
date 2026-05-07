# T1 解説: 偶数の合計を返す関数

## ポイント

### 「集計」と「条件判定」の組み合わせ

このテストの本質は、**S18(集計)** と **S19(条件判定)** を1つの関数に統合できるかどうか。

```php
$result = 0;                            // ① 集計用変数を初期化
foreach ($numbers as $number) {         // ② ループ
    if ($number % 2 === 0) {            // ③ 条件判定
        $result += $number;             // ④ 条件を満たすものだけ加算
    }
}
return $result;                         // ⑤ 結果を返す
```

S18 のパターン(集計)に S19 のパターン(条件判定)が入れ子になった形。**実務でも頻出する基本構造**。

## 別解

### 早期 continue でネストを浅くする(推奨)

```php
function sumEven(array $numbers): int
{
    $result = 0;
    foreach ($numbers as $number) {
        if ($number % 2 !== 0) {
            continue;   // 奇数ならスキップ
        }
        $result += $number;   // ここがメイン処理
    }
    return $result;
}
```

「条件を満たさないものは早めに弾いて、メイン処理を if のネストの外に出す」スタイル。**メイン処理が見やすくなる**ため、リーダブルコードや Laravel のソースコードでも推奨されている書き方。

模範解答(if のネスト版)とこの早期 continue 版、どちらも正解だが、処理が複雑になるほど早期 continue 版の優位性が高くなる。

### 組み込み関数を組み合わせる

```php
function sumEven(array $numbers): int
{
    return array_sum(array_filter($numbers, fn($n) => $n % 2 === 0));
}
```

`array_filter` で偶数だけ抜き出し、`array_sum` で合計する。1行で書けて簡潔だが、今回は `foreach` 縛りなので使えない。実務ではこちらを使うことが多い。

### `array_reduce` で書く

```php
function sumEven(array $numbers): int
{
    return array_reduce(
        $numbers,
        fn($carry, $n) => $n % 2 === 0 ? $carry + $n : $carry,
        0
    );
}
```

「畳み込み」を1関数で表現。Java Stream の `reduce` 相当。慣れると強力だが、可読性は手書きの `foreach` のほうが高い場面も多い。

## つまずきやすい点

### 1. フィルターと集計を別関数で書こうとする

```php
function sumEven(array $numbers): int
{
    $evens = filterEven($numbers);   // S19 の関数を呼ぶ
    return sum($evens);              // S18 の関数を呼ぶ
}
```

これも動くが、**1つのループで完結できる処理を2回ループする**のはパフォーマンス上もったいない。今回のような単純な処理は、1つの `foreach` の中で集計まで済ませるのが定石。

### 2. 条件式の向きを間違える

```php
if ($number % 2 === 0)    // ⭕ 偶数の時
if ($number % 2 !== 0)    // 奇数の時(早期 continue ではこちら)
if ($number % 2 == 1)     // △ 奇数の時(動くが負数で破綻)
```

特に `$number % 2 == 1` は要注意。**負数の剰余では `-1` が返る**ため、奇数の負数を判定できない。

```php
-3 % 2  // -1(0でも1でもない)
```

「**偶数判定は `% 2 === 0`、奇数判定は `% 2 !== 0`**」と覚えるのが安全。

### 3. 累積変数のリセット忘れ

```php
function sumEven(array $numbers): int
{
    foreach ($numbers as $number) {
        if ($number % 2 === 0) {
            $result += $number;   // ❌ $result が未定義
        }
    }
    return $result;
}
```

`$result = 0;` の初期化を忘れると Notice。**累積変数は必ずループ前に初期化**する習慣を。

## Java との対比

```java
// Java
public static int sumEven(int[] numbers) {
    int result = 0;
    for (int number : numbers) {
        if (number % 2 == 0) {
            result += number;
        }
    }
    return result;
}

// Java 8+ Stream
public static int sumEven(int[] numbers) {
    return Arrays.stream(numbers)
        .filter(n -> n % 2 == 0)
        .sum();
}
```

```php
// PHP(foreach)
function sumEven(array $numbers): int
{
    $result = 0;
    foreach ($numbers as $number) {
        if ($number % 2 === 0) {
            $result += $number;
        }
    }
    return $result;
}

// PHP(組み込み関数)
function sumEven(array $numbers): int
{
    return array_sum(array_filter($numbers, fn($n) => $n % 2 === 0));
}
```

ロジック構造は両言語で完全に同じ。**Java Silver 保持者であれば、構文の差分(`$`、`foreach`、改行コード)さえ覚えれば、Java で書けるロジックはそのまま PHP で書ける**ことが、このテストでも確認できる。
