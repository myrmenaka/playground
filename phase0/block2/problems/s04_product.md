# S04: Product クラス(getter/setter)

商品を表す `Product` クラスを定義してください。

## 制約

- クラス名: `Product`
- プロパティ:
  - `$name` (string)
  - `$price` (int)
- プロパティはすべて `private` にすること
- コンストラクタで `$name` と `$price` を受け取って初期化する
- 以下のメソッドを定義する:
  - `getName(): string` — 商品名を返す
  - `getPrice(): int` — 価格を返す
  - `setPrice(int $price): void` — 価格を更新する
- `declare(strict_types=1);` を先頭に書くこと

## 動作確認(`main` 部分)

クラス定義の下に、以下を行うコードを書いてください。

1. `Product` を1つ作る(例: 名前 `"りんご"`、価格 `150`)
2. `getName()` と `getPrice()` を使って、商品名と価格を表示
3. `setPrice(200)` で価格を更新
4. 再度 `getPrice()` で価格を表示

## 出力例

```
商品名: りんご
価格: 150円
価格を更新しました
新しい価格: 200円
```

## ファイル名

`my_answers/s04_product.php`

## ヒント

<details>
<summary>クリックして表示</summary>

- Java の getter/setter とほぼ同じ感覚で書けます
- PHPでは型宣言を使えます: `private string $name;` のように書ける(PHP 7.4+)
- 戻り値の型がない場合は `: void` を付ける

</details>

---

書けたら「終わりました」と添えてコードを見せてください。レビューします。
詰まったら「ヒントください」または「答え見せて」と言ってください。