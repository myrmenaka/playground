# S17 解説: 型宣言付きの加算関数

## ポイント

### 型宣言の構文

```php
function 関数名(型 $引数1, 型 $引数2): 戻り値の型 {
    return 値;
}
```

- **引数の型**は変数の前に書く(`int $a`)
- **戻り値の型**は `)` の後にコロン `:` を付けて書く(`: int`)

Java では `int add(int a, int b)` の順序だが、PHPでは `function add(int $a, int $b): int` の順序になる。コロン `:` の位置を間違えやすい。

### 主な型宣言

| 型 | 説明 | 例 |
|----|------|----|
| `int` | 整数 | `42`, `-7` |
| `float` | 浮動小数点数 | `3.14`, `0.5` |
| `string` | 文字列 | `'hello'` |
| `bool` | 真偽値 | `true`, `false` |
| `array` | 配列 | `[1, 2, 3]` |
| `void` | 戻り値なし(関数のみ) | `return;` のみ可 |
| `mixed` | 何でも(PHP 8+) | あらゆる型 |
| `?int` | int または null | `42`, `null` |
| `int\|string` | int または string(union型、PHP 8+) | `42`, `'42'` |

### `declare(strict_types=1)` の役割

```php
<?php
declare(strict_types=1);   // ファイル先頭で必ず宣言
```

これがあるかないかで、PHPの型チェックの厳しさが変わる。

| モード | 動作 |
|--------|------|
| **strict 無効**(デフォルト) | 暗黙の型変換が行われる(`"3"` を `int` 引数に渡すと自動で `3` に変換) |
| **strict 有効**(`declare(strict_types=1)`) | 型が完全に一致しない場合は `TypeError` を投げる |

```php
// strict 無効の場合
function add(int $a, int $b): int {
    return $a + $b;
}
echo add("3", 5);   // 8(文字列 "3" が暗黙的に 3 に変換される)

// strict 有効の場合
declare(strict_types=1);
function add(int $a, int $b): int {
    return $a + $b;
}
echo add("3", 5);   // ❌ TypeError: must be of type int, string given
```

**現代の PHP/Laravel プロジェクトでは strict_types を有効化するのが標準**。Java と同じ感覚で「型が違ったらエラー」になり、バグの早期発見に繋がる。

### `declare` の重要なルール

```php
<?php
declare(strict_types=1);   // ⭕ <?php の直後、コードより前

// すべてのコード
```

- `declare(strict_types=1);` は**ファイルの先頭**(`<?php` の直後)に書く必要がある
- ファイル単位で有効。1つのファイルに strict 有効/無効を混在させることはできない
- このファイル内で**定義した関数**には strict が適用されるが、**呼び出した相手側の関数**にはそのファイルの設定が適用される

## 別解

### Nullable型(null許容)

```php
function findUser(?int $id): ?string {
    if ($id === null) {
        return null;
    }
    return "User#{$id}";
}

echo findUser(1) . PHP_EOL;     // User#1
echo findUser(null) . PHP_EOL;  // (空文字)
```

`?int` は「int または null」を意味する。Java の `Optional<Integer>` よりずっと簡潔。

### Union型(PHP 8.0+)

```php
function format(int|string $value): string {
    return "Value: {$value}";
}

echo format(42) . PHP_EOL;       // Value: 42
echo format("hello") . PHP_EOL;  // Value: hello
```

`int|string` で「int または string」を許容。Java にはない PHP 8 の強力な機能。

### void 戻り値

```php
function logMessage(string $message): void {
    echo $message . PHP_EOL;
    // return; は書いてもよいが、値は返せない
}

logMessage("Hello");   // Hello
```

「何も返さない」関数には `void` を指定する。Java の `void` と同じ。

## つまずきやすい点

### 1. 戻り値の型のコロンを忘れる

```php
function add(int $a, int $b) int {   // ❌ 構文エラー
function add(int $a, int $b): int {  // ⭕ コロンが必要
```

Java の `int add(...)` の感覚で書くと忘れがち。**`)` の後に `:` 戻り値型** と覚える。

### 2. `declare(strict_types=1)` の位置

```php
<?php

$x = 10;
declare(strict_types=1);   // ❌ コードの後では効かない

// ⭕ こちら
<?php
declare(strict_types=1);

$x = 10;
```

`declare` は必ず `<?php` の直後に書く。空行は挟んでも構わないが、他のコードを挟んではいけない。

### 3. 型が違う値を渡した時の挙動

```php
declare(strict_types=1);

function add(int $a, int $b): int {
    return $a + $b;
}

add(3, 5);          // ⭕ 8
add(3.0, 5);        // ❌ TypeError(float は int ではない)
add("3", 5);        // ❌ TypeError(string は int ではない)
add(true, 5);       // ❌ TypeError(bool は int ではない)
```

strict_types 有効下では、**型変換は一切行われない**。Java の感覚に近いが、PHP では `int` と `float` の互換性もないので注意。

### 4. 戻り値の型と return の値が違う

```php
declare(strict_types=1);

function add(int $a, int $b): int {
    return "結果: " . ($a + $b);   // ❌ TypeError(戻り値が string)
}
```

戻り値の型を宣言したら、**必ずその型の値を return する**。これも strict_types のおかげで早期発見できる。

## Java との対比

```java
// Java(常に静的型付け)
public class Main {
    public static int add(int a, int b) {
        return a + b;
    }
    
    public static void main(String[] args) {
        System.out.println(add(3, 5));
    }
}
```

```php
// PHP(strict_types で Java 同等の型チェック)
declare(strict_types=1);

function add(int $a, int $b): int {
    return $a + $b;
}

echo add(3, 5) . PHP_EOL;
```

| 観点 | Java | PHP |
|------|------|-----|
| 型システム | 常に静的型付け | デフォルトは動的、`declare(strict_types=1)` で静的に近づく |
| 引数の型の位置 | 変数名の前(`int a`) | 変数名の前(`int $a`)※同じ |
| 戻り値の型の位置 | メソッド名の前(`int add(...)`) | `)` の後にコロン(`: int`) |
| 型変換の許容 | 暗黙の型変換は限定的 | strict 無効ではかなり寛容 |
| Null 許容 | `Integer` は null 可、`int` は不可 | `?int` で明示 |
| Union 型 | なし(継承で代替) | `int\|string` で記述可能 |

**PHPの型宣言は「Javaに近づく道具」と考えるとわかりやすい**。strict_types を有効化すれば、Java の感覚で「型違いはコンパイルエラー(実行時エラー)」になり、IDEの補完も強力に効くようになる。

実務での重要ポイント:
- **新規ファイルを作る時は必ず `declare(strict_types=1);` を書く**
- **すべての関数・メソッドに型宣言を付ける**
- これらは Laravel の最新版や、PHPStan などの静的解析ツールでも推奨されている

「読めるけど書けない」状態を脱して「**型安全に書ける**」状態を目指すのが Phase 0 の狙い。Java Silver 保持者であれば、ここはむしろ得意分野になるはず。型をしっかり書いて、IDE のサポートを最大限に活用していこう。