# S04 解説: Product クラス(getter/setter)

## ポイント

### 1. プロパティの型宣言(PHP 7.4+)

```php
private string $name;
private int $price;
```

PHP 7.4 以降、プロパティに型宣言ができるようになりました。
これを書くことで、間違った型を代入しようとしたら実行時エラーになります。

### 2. getter/setter の役割

- `getName()`, `getPrice()` — プロパティを「読む」だけ
- `setPrice()` — プロパティを「書き換える」

`$name` には setter を作っていません。「商品名は変えない」というルールをコードで表現できます。
これを **カプセル化** と呼びます(Java と同じ概念)。

## 別解: コンストラクタプロモーション(PHP 8.0+)

S03 で習ったコンストラクタプロモーションを使うと、もっと短く書けます:

```php
class Product
{
    public function __construct(
        private string $name,
        private int $price,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}
```

実務(Laravel プロジェクト)では、この書き方のほうが主流です。

## Java との対比

| 項目 | Java | PHP |
|------|------|-----|
| プロパティ宣言 | `private String name;` | `private string $name;` |
| 初期化 | `this.name = name;` | `$this->name = $name;` |
| getter | `public String getName()` | `public function getName(): string` |
| setter | `public void setPrice(int price)` | `public function setPrice(int $price): void` |
| 戻り値なし | `void` | `: void` |

ほぼ同じ構造です。違いは:
- メソッド宣言に `function` キーワードが必要
- 型は **戻り値の後ろ** に書く(Java は前)
- プロパティアクセスは `$this->` (Java は `this.`)

## つまずきやすい点

### 文字列内のメソッド呼び出し

```php
echo "価格: {$product->getPrice()}円\n";  // ✅ 波括弧で囲む
echo "価格: $product->getPrice()円\n";    // ❌ メソッドは展開されない
```

文字列内でメソッド呼び出しの結果を展開したい時は、**必ず `{}` で囲む**こと。
連結 (`.`) で書く方法もあります:

```php
echo "価格: " . $product->getPrice() . "円\n";
```

### `void` を忘れる

setter のように何も返さないメソッドでも、戻り値の型を `: void` と明示すると意図が伝わりやすくなります。