# S19 解説: 在庫管理クラスで例外を使う

## このStep の位置づけ

S18 で写経した例外の構文を、別シナリオで **何も見ずに** 使えるかを試す自力Step。
Block 2 完了テストの直前の練習として、業務でよくある「在庫管理」をテーマにした。

## 学習者がよくやるミスとレビュー観点

### ミスA: `extends Exception` で `\` を忘れる

`throw new \InvalidArgumentException` には `\` を付けても、`extends Exception` には付け忘れる現象は超頻出。
動作上は問題ないが、Phase 0 Block 4 で名前空間を学んだ後に痛い目を見るので、今のうちに癖を付けておく。

```php
// ✕ 名前空間を導入した瞬間に壊れる
class OutOfStockException extends Exception { }

// ◯ どんな名前空間にいても安全
class OutOfStockException extends \Exception { }
```

### ミスB: 例外メッセージを呼び出し側で書く

```php
// ✕ 呼び出し側で文字列を直接書いてしまう
try {
    $product->receive(0);
} catch (\InvalidArgumentException $e) {
    echo "エラー(引数不正): 数量は1以上で指定してください" . PHP_EOL;
}
```

これは S18 の写経経験を、独自シナリオに移し替えた時に起きやすい。
動作はするが、業務観点では:
- DRY 違反(同じ文言を複数箇所で書く羽目になる)
- ログに残るメッセージが空になる(`$e->getMessage()` が空文字を返す)
- 例外を投げる場所と文言が分離して整合性が崩れやすい

正しくは:

```php
// クラス側で文言を持つ
throw new \InvalidArgumentException("数量は1以上で指定してください");

// 呼び出し側はそれを表示するだけ
catch (\InvalidArgumentException $e) {
    echo "エラー(引数不正): " . $e->getMessage() . PHP_EOL;
}
```

### ミスC: catch 節を網羅しない

写経の S18 では各 try ブロックの中で **1種類の例外しか発生しない** ことが分かっていたので、catch 節が1つでも問題なかった。
しかし `receive()` や `ship()` のように **複数種類の例外を投げる可能性があるメソッド** を呼ぶ時は、catch 節も網羅的に書くのが業務的に正しい。

```php
// 不完全(ProductDiscontinuedException が飛んできたらどうなる?)
try {
    $product->receive(10);
} catch (\InvalidArgumentException $e) {
    // ...
}

// 網羅的(両方の例外をカバー)
try {
    $product->receive(10);
} catch (\InvalidArgumentException $e) {
    // ...
} catch (ProductDiscontinuedException $e) {
    // ...
}
```

ただし学習段階では「飛んでこないことが分かっている例外は catch しない」も一つの判断としてアリ。
業務的には IDE が `@throws` から推測して警告を出すケースも多い。

## ポイント

### 1. 早期 throw による検証

```php
public function receive(int $quantity): void
{
    if ($quantity <= 0) {
        throw new \InvalidArgumentException("...");
    }

    if ($this->discontinued) {
        throw new ProductDiscontinuedException("...");
    }

    $this->stock += $quantity;
    echo "入荷完了" . PHP_EOL;
}
```

これは「**早期 throw**」というパターン。早期 return と同じ考え方で、検証を先に通して、正常系のコードを最後にスッキリ書く。

ネストして書くとこうなる(悪い例):

```php
// ✕ ネストが深くなる
public function receive(int $quantity): void
{
    if ($quantity > 0) {
        if (!$this->discontinued) {
            $this->stock += $quantity;
            echo "入荷完了" . PHP_EOL;
        } else {
            throw new ProductDiscontinuedException("...");
        }
    } else {
        throw new \InvalidArgumentException("...");
    }
}
```

早期 throw のほうが圧倒的に読みやすい。

### 2. 検証順序の意味

このコードでは検証順を:

1. 引数の妥当性(`$quantity <= 0`)
2. 状態の妥当性(`discontinued`)
3. 業務ルール(`stock < $quantity` — ship のみ)

の順にしている。これは **「呼び出し側の責任」→「オブジェクトの状態」→「業務ルール」** の順で、一般的に推奨される順序。

理由:
- 引数不正は「呼び出し側のバグ」なので、最も早く知らせるべき
- 状態は「そもそもこのオブジェクトに対する操作が許されるか」なので2番目
- 業務ルールは個別の状況依存なので最後

### 3. `private string $name` を使っていない

写経・自力ステップで「使っていないプロパティ」が残ることがある。
今回 `$name` は受け取るだけで使っていないが、現実の業務では:
- 例外メッセージに含める(`"商品「{$this->name}」は廃番です"`)
- ログ出力に使う
- 表示用に使う

など、使い道は多い。例外メッセージに含めると、複数商品を扱う時にどの商品でエラーが起きたか分かりやすくなる:

```php
throw new ProductDiscontinuedException("商品「{$this->name}」は廃番です");
```

### 4. `=== true` の冗長さ

```php
if ($this->discontinued === true) {  // 冗長
if ($this->discontinued) {           // 簡潔
```

`discontinued` は `bool` 型なので、そのまま `if` の条件に書ける。
Java の `if (this.discontinued == true)` と同じで、冗長だが間違いではない。実務的には省略するのが一般的。

ただし `=== true` を明示することで「これは厳密に true の場合だけ」という意図を伝える、というスタイルもある。プロジェクトのコーディング規約に従えばよい。

## Java との対比

```java
// Java
public class Product {
    private final String name;
    private int stock = 0;
    private boolean discontinued = false;

    public Product(String name) {
        this.name = name;
    }

    public void receive(int quantity) {
        if (quantity <= 0) {
            throw new IllegalArgumentException("数量は1以上で指定してください");
        }
        if (this.discontinued) {
            throw new ProductDiscontinuedException("この商品は廃番です");
        }
        this.stock += quantity;
        System.out.println("入荷完了");
    }
    // ...
}
```

| 観点 | Java | PHP |
|------|------|-----|
| 引数不正 | `IllegalArgumentException` | `\InvalidArgumentException` |
| 範囲外/状態異常 | `IllegalStateException` | カスタム例外(PHPに `IllegalStateException` 相当の標準クラスはない) |
| 検査例外宣言 | `throws` で明示 | PHPDoc コメントの `@throws` |
| `final` キーワード | プロパティに付けられる | `readonly`(PHP 8.1+)で類似 |

PHP には Java の `IllegalStateException` に正確に対応する標準クラスがない。
このため「**オブジェクトの状態が操作に適していない**」エラーは、カスタム例外で表現することが多い(今回の `ProductDiscontinuedException` がまさにそれ)。

## Block 2 完了テストへの接続

S19 まで完了した時点で、Block 2 のすべての要素が揃った:
- クラス、プロパティ、コンストラクタ(S1〜S5)
- 継承、`parent::`、ポリモーフィズム(S6〜S10)
- インターフェース、抽象クラス(S11〜S17)
- 例外、カスタム例外(S18〜S19)

完了テスト T1(BankAccount)、T2(Discountable インターフェース)では、これらを統合的に使う。