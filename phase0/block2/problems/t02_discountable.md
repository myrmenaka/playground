# B2-T2: Discountable インターフェース

## 状況

ある EC サイトで、会員ランクごとに割引率を適用したい。
将来的に会員ランクが追加される可能性があるため、**インターフェース** を使って割引の仕組みを抽象化する。

## 制約

- インターフェース: `Discountable`
  - メソッド: `applyDiscount(int $price): int`
    - 元の価格を受け取り、割引適用後の価格を返す
- クラス: `Member implements Discountable`
  - `applyDiscount`: 10% 引きの価格を返す(例: 1000円 → 900円)
- クラス: `VIP implements Discountable`
  - `applyDiscount`: 20% 引きの価格を返す(例: 1000円 → 800円)
- `declare(strict_types=1);` を冒頭に書く

## 動作確認(ファイル末尾に書く)

1. `Member` と `VIP` のインスタンスを作成
2. `applyDiscount(1000)` をそれぞれ呼び出し、結果を表示
3. **(ボーナス)** 配列に `Member` と `VIP` を入れて、`foreach` で全員の割引価格を表示
   - これがインターフェースを使うメリット(**ポリモーフィズム**)を体感する部分

## 期待される出力例

```
Member: 1000円 → 900円
VIP: 1000円 → 800円

【全会員の割引価格】
Member: 900円
VIP: 800円
```

## ファイル名

`my_answers/t02_discountable.php`

---

## 進め方

S20 で身につけた **5ステップの分解手順** を踏んでから書き始めること。

### Step 1: 登場人物を洗い出す

```php
<?php
// === 登場人物 ===
// インターフェース: Xxxxx
// クラス: Xxxxx, Yyyyy
```

### Step 2: メソッド一覧表

```php
// === Discountable インターフェース ===
// applyDiscount(int $price): int

// === Member クラス ===
// applyDiscount(int $price): int  ← 10%引き

// === VIP クラス ===
// applyDiscount(int $price): int  ← 20%引き
```

### Step 3: 骨組みを空のまま書く

インターフェース宣言 → 各クラスの骨組み(`implements Discountable`、メソッドは空)

### Step 4: メソッド中身を1つずつ埋める

Member の `applyDiscount` → VIP の `applyDiscount` の順

### Step 5: 動作確認コードを書く

正常系のみ(このTestには異常系はない)

---

## ヒント

<details>
<summary>詰まったら開く: 計算式について</summary>

- 10% 引き: `$price * 0.9` だと float になるので、`(int)` キャストするか `intval()` で int に戻す
- もしくは `$price - intdiv($price * 10, 100)` のように整数演算で書く方法もある
- 戻り値の型が `int` なので、float のままだと型エラーになる
</details>

<details>
<summary>詰まったら開く: インターフェース構文の確認</summary>

```php
interface Xxx
{
    public function method(int $arg): int;  // メソッドの宣言のみ、本体なし
}

class Yyy implements Xxx
{
    public function method(int $arg): int
    {
        // 必ず実装する
        return $arg * 2;
    }
}
```
</details>

<details>
<summary>詰まったら開く: ボーナス(配列で foreach)のヒント</summary>

```php
$members = [new Member(), new VIP()];

foreach ($members as $member) {
    // どちらも Discountable なので applyDiscount が呼べる
    echo $member->applyDiscount(1000);
}
```

これが **ポリモーフィズム** の威力。配列の中身が何であろうと、Discountable を実装していれば同じように扱える。
</details>

---

## チェックリスト(自己採点用)

書き終わった後、自分でチェックしてみる:

- [ ] Step 1〜2 のコメントが先に書けている
- [ ] `interface Discountable` の宣言ができている
- [ ] `Member implements Discountable` の構文が書けている
- [ ] `VIP implements Discountable` の構文が書けている
- [ ] `applyDiscount` の戻り値が `int` 型になっている(`float` のままになっていない)
- [ ] 動作確認で期待通りの出力が出る
- [ ] ボーナスの foreach 部分も書けている

## 想定時間

30〜45分程度