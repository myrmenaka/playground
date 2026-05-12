# S19 参考: 在庫管理クラスをクロージャで DRY 化する

通常版(`s19_stock_manager.php`)では try-catch-finally を6回(または5回)書いていた。
クロージャを使うと、共通の例外処理を1箇所にまとめて、操作の呼び出し部分が極めてスッキリする。

**注意: クロージャは Block 2 の範囲外。S19 の正解はあくまで通常版で、このファイルは「次のレベルの書き方」を見せるための参考実装。**

## クロージャとは何か

クロージャ(Closure、無名関数)は **「変数に入れられる関数」**。
PHP では2種類の書き方がある。

### 書き方1: `function () { ... }` 形式

```php
$greet = function (string $name): string {
    return "Hello, {$name}!";
};

echo $greet("世界");  // Hello, 世界!
```

### 書き方2: アロー関数 `fn () => ...`(PHP 7.4+)

```php
$greet = fn(string $name): string => "Hello, {$name}!";

echo $greet("世界");  // Hello, 世界!
```

アロー関数は **式が1つだけ** の時に使える簡略記法。
`return` キーワードを書かない(値が自動的に返る)。
JavaScript のアロー関数 `(name) => "Hello, " + name` と同じ感覚。

## このコードの核心: `tryAction` クロージャ

```php
$tryAction = function (callable $action) use ($product) {
    try {
        $action();
    } catch (\InvalidArgumentException $e) {
        echo "エラー(引数不正): " . $e->getMessage() . PHP_EOL;
    } catch (OutOfStockException $e) {
        echo "エラー(在庫不足): " . $e->getMessage() . PHP_EOL;
    } catch (ProductDiscontinuedException $e) {
        echo "エラー(廃番): " . $e->getMessage() . PHP_EOL;
    } finally {
        echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
        echo "---" . PHP_EOL;
    }
};
```

### ポイント1: `callable $action`

`callable` は「**呼び出し可能なもの**(関数・メソッド・クロージャ)」を受け取れる型。
ここでは「実際に試したい操作」を関数として受け取り、try ブロックの中で `$action()` として実行する。

### ポイント2: `use ($product)`

クロージャの **外側のスコープ** にある `$product` 変数を、クロージャの **内側で使う** ためのキーワード。

これがないと、クロージャ内で `$product->getStock()` を書こうとしても、
`$product` がクロージャから見えないのでエラーになる。

```php
// ✕ use なし
$tryAction = function (callable $action) {
    // $product が見えない
    echo "現在の在庫: {$product->getStock()}個";  // エラー
};

// ◯ use ($product) あり
$tryAction = function (callable $action) use ($product) {
    // $product が見える
    echo "現在の在庫: {$product->getStock()}個";  // OK
};
```

Java のラムダ式が外側の変数を **実質 final** として参照できるのと似ているが、PHP では `use` で **明示的に** 渡す必要がある(Java よりも安全寄り)。

### ポイント3: アロー関数は `use` 不要

呼び出し側のアロー関数:

```php
$tryAction(fn() => $product->receive(0));
```

実はアロー関数 `fn() => ...` は、外側の変数を **自動的に** 取り込む。
`use` を書かなくても `$product` がそのまま使える。

これは `function () { ... }` 形式と違う点。
書き方によって使い勝手が違うので注意。

## なぜ DRY 化が嬉しいか

通常版のコードと比較してみる。

### 通常版(5回 or 6回 try-catch-finally を書く)

```php
try {
    $product->receive(0);
} catch (\InvalidArgumentException $e) {
    echo "エラー(引数不正): " . $e->getMessage() . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}

try {
    $product->receive(10);
} catch (\InvalidArgumentException $e) {
    echo "エラー(引数不正): " . $e->getMessage() . PHP_EOL;
} catch (ProductDiscontinuedException $e) {
    echo "エラー(廃番): " . $e->getMessage() . PHP_EOL;
} finally {
    echo "現在の在庫: {$product->getStock()}個" . PHP_EOL;
    echo "---" . PHP_EOL;
}

// ... 以下繰り返し
```

合計 60行近く。

### クロージャ版

```php
$tryAction(fn() => $product->receive(0));
$tryAction(fn() => $product->receive(10));
$tryAction(fn() => $product->ship(15));
$tryAction(fn() => $product->ship(5));
$tryAction(fn() => $product->receive(3));  // discontinue() の後
```

操作部分は **5行**。

**変更しやすさ** も全然違う。例えば「すべての操作の前に日付を出力したい」という変更があった場合:

- 通常版: 5箇所すべてを直す
- クロージャ版: `tryAction` の中身1箇所を直すだけ

これが DRY(Don't Repeat Yourself)の威力。

## Java で同じことをやるなら

Java 8+ のラムダ式と関数型インターフェースで同じパターンが書ける:

```java
import java.util.function.Consumer;

Consumer<Runnable> tryAction = (Runnable action) -> {
    try {
        action.run();
    } catch (IllegalArgumentException e) {
        System.out.println("エラー(引数不正): " + e.getMessage());
    } catch (OutOfStockException e) {
        System.out.println("エラー(在庫不足): " + e.getMessage());
    } catch (ProductDiscontinuedException e) {
        System.out.println("エラー(廃番): " + e.getMessage());
    } finally {
        System.out.println("現在の在庫: " + product.getStock() + "個");
        System.out.println("---");
    }
};

tryAction.accept(() -> product.receive(0));
tryAction.accept(() -> product.receive(10));
```

考え方は完全に同じ。
PHP のクロージャは Java のラムダ式と「異なる場所に定義された関数を変数に入れて持ち運ぶ」という発想で一致している。

## クロージャを使うべきか?

「使えると便利」だが、状況による。

### クロージャが向いている場面

- **共通処理が3回以上繰り返される**(DRY の効果が出る)
- **try-catch、リトライ処理、ログ出力、トランザクションなど横断的関心事**
- **コールバックを渡したい場面**(`array_map`、`usort` など)

### クロージャを使わない方がいい場面

- **1〜2回しか繰り返さない**(かえって読みづらい)
- **チームメンバーがクロージャに不慣れ**(可読性が下がる)
- **シンプルなスクリプト**(過剰な抽象化)

業務的には:
- Laravel のルーティング(`Route::get('/', function () { ... })`)
- コレクション操作(`$users->map(fn($u) => $u->name)`)
- イベントリスナー、ジョブのリトライ処理

などで頻出する。

## このコードの教育的価値

S19 の自力ステップでは通常版で書ければ完成だが、業務ではクロージャ版に近い書き方を見ることが多い。

「**繰り返しを見つけたら抽象化する**」という発想を持つこと自体が、コードを書ける人の特徴。
Block 4 で Composer を扱った後、関数型プログラミングの要素(`array_map`、`array_filter`、`array_reduce`)を学ぶ場面で本格的に登場する。

今は「こういう書き方もある」と知って、Block 4 以降で再会した時に思い出せれば十分。