# S19 解説: 偶数だけを抽出する関数

## ポイント

### 「フィルター」の典型パターン

```php
$result = [];                       // ① 結果を入れる空の配列
foreach ($numbers as $number) {     // ② 各要素を取り出す
    if (条件) {                     // ③ 条件を満たすかチェック
        $result[] = $number;        // ④ 満たすなら結果に追加
    }
}
return $result;                     // ⑤ 結果を返す
```

これが**フィルター処理の定型パターン**。S18 の「集計」パターンに「条件判定」が加わった形。実務でもこの構造のコードは無数に書く。

### 配列に要素を追加する記法

```php
$result[] = $value;            // ⭕ 末尾に追加(最も一般的)
array_push($result, $value);   // ⭕ 同じ意味だが冗長
```

`$配列名[] = 値;` が PHP の慣用句。インデックスは自動的に振られる(0, 1, 2, ...)。

### `print_r` と `var_dump` の使い分け

```php
print_r([1, 2, 3]);
// Array
// (
//     [0] => 1
//     [1] => 2
//     [2] => 3
// )

var_dump([1, 2, 3]);
// array(3) {
//   [0]=> int(1)
//   [1]=> int(2)
//   [2]=> int(3)
// }
```

| 関数 | 用途 |
|------|------|
| `print_r` | **見た目重視のデバッグ表示**。人が読みやすい |
| `var_dump` | **型情報も含めたデバッグ表示**。型のバグを追う時に必須 |

開発中の確認では `var_dump` がより詳しい情報を返してくれるので便利。Laravel では `dd()`(dump and die)というヘルパー関数があり、これは `var_dump` の進化版。

## 別解

### 早期 continue でネストを浅くする

```php
function filterEven(array $numbers): array
{
    $result = [];
    foreach ($numbers as $number) {
        if ($number % 2 !== 0) {
            continue;   // 奇数ならスキップ
        }
        $result[] = $number;
    }
    return $result;
}
```

「条件を満たさない場合は `continue` で次のループへ」という早期 return スタイル。ネストが浅くなり、メインの処理(`$result[] = $number;`)が目立つ。実務でよく使うパターン。

### `array_filter` を使う(次の S20 で扱う)

```php
function filterEven(array $numbers): array
{
    return array_filter($numbers, fn($n) => $n % 2 === 0);
}
```

PHP には `array_filter` という組み込み関数がある。条件を満たす要素だけを抜き出してくれる。今回は `foreach` の練習のため使わなかったが、実務ではこちらを使うことが圧倒的に多い。

ただし `array_filter` は **元の配列のキーを保持する** という落とし穴があるので、次の Step で詳しく扱う。

### キーを振り直したい場合

```php
function filterEven(array $numbers): array
{
    $result = [];
    foreach ($numbers as $number) {
        if ($number % 2 === 0) {
            $result[] = $number;   // 新しいキーが自動で振られる
        }
    }
    return $result;
}
```

今回の手書き実装の良いところは、結果の配列のキーが自動で `0, 1, 2, ...` になること。`array_filter` の挙動と異なる重要なポイント。

## つまずきやすい点

### 1. 結果の配列の初期化忘れ

```php
function filterEven(array $numbers): array
{
    foreach ($numbers as $number) {
        if ($number % 2 === 0) {
            $result[] = $number;   // ❌ $result が未定義のまま
        }
    }
    return $result;   // ❌ 未定義変数を返そうとする
}
```

`$result = [];` の初期化を忘れると Notice が出る。**ループ前に必ず初期化**する。

### 2. 結果配列ではなく元の配列を return する

```php
function filterEven(array $numbers): array
{
    $result = [];
    foreach ($numbers as $number) {
        if ($number % 2 === 0) {
            $result[] = $number;
        }
    }
    return $numbers;   // ❌ 元の配列を返してしまっている
}
```

`return $numbers;` と `return $result;` を混同しやすい。**何を返すべきか**を意識する。

### 3. 関数名のタイポ

```php
filterEven   // ⭕ filter(フィルター)
finalEven    // ❌ final(最終的な)→ 意図不明
filtelEven   // ❌ ただのスペルミス
```

関数名は処理の意図を伝える重要な情報。タイポすると、後でコードを読み返す時や、チームメンバーが読む時に意味が伝わらなくなる。

**対策**:
- エディタの補完(Intelephense)を活用する
- 書いた後に関数名を声に出して読んでみる
- リファクタリング時は IDE のリネーム機能を使う(全箇所一括変更)

### 4. `print_r` の末尾に改行が入らない

```php
print_r([1, 2, 3]);
print_r([4, 5, 6]);
```

`print_r` は配列の最後に改行を入れない。複数の `print_r` を並べると間に空行が入らないことがある。詳細にこだわるなら:

```php
print_r([1, 2, 3]);
echo PHP_EOL;
print_r([4, 5, 6]);
```

または `print_r($arr, true)` で文字列として取得し、加工してから出力する手もある。

## Java との対比

```java
// Java(for-each + ArrayList)
public static List<Integer> filterEven(List<Integer> numbers) {
    List<Integer> result = new ArrayList<>();
    for (int number : numbers) {
        if (number % 2 == 0) {
            result.add(number);
        }
    }
    return result;
}

// Java 8+ Stream
public static List<Integer> filterEven(List<Integer> numbers) {
    return numbers.stream()
        .filter(n -> n % 2 == 0)
        .collect(Collectors.toList());
}
```

```php
// PHP(foreach)
function filterEven(array $numbers): array
{
    $result = [];
    foreach ($numbers as $number) {
        if ($number % 2 === 0) {
            $result[] = $number;
        }
    }
    return $result;
}

// PHP(array_filter + アロー関数)
function filterEven(array $numbers): array
{
    return array_filter($numbers, fn($n) => $n % 2 === 0);
}
```

| 観点 | Java | PHP |
|------|------|-----|
| 結果コレクションの初期化 | `new ArrayList<>()` | `[]` |
| 要素の追加 | `result.add(value)` | `$result[] = $value` |
| 関数型スタイル | Stream API(`filter`, `map`, `collect`) | `array_filter`, `array_map`, `array_reduce` |
| ラムダ式 | `n -> n % 2 == 0` | `fn($n) => $n % 2 === 0` |

**ロジック構造は完全に同じ**。違いは構文だけ。Java で Stream API に慣れている人は、PHP の `array_filter` などを「ちょっと書き方が違う Stream」と捉えれば違和感なく使える。

実務での重要ポイント:
- **シンプルなフィルター処理は `array_filter` を使う**(可読性が高い)
- **複雑な条件や副作用を伴う処理は `foreach` で手書き**(デバッグしやすい)
- Laravel のコレクション(`Collection::filter()`)も同じ概念

「フィルター」は実務で**最も頻出する操作の1つ**。「条件を満たす要素だけ抜き出す」というパターンは、ユーザー一覧から特定ステータスのユーザーを抜き出す、商品一覧から在庫ありのものを抜き出す、ログから特定の日付のものを抜き出す...など、どこでも使う。今のうちに**手書きで書ける**ようになっておくと、組み込み関数の挙動も理解しやすくなる。