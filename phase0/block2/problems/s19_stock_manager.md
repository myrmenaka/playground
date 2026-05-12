# S19: 在庫管理クラスで例外を使う(自力)

## 目的

S18 で写経した例外の構文を、別のシナリオで **何も見ずに** 使えるかを試す。
業務でよくある「在庫管理」をテーマに、複数種類の例外を投げ分ける練習をする。

## 状況設定

ある EC サイトの在庫管理システムを開発しています。
商品には在庫数があり、入荷・出荷ができます。ただし以下のエラー条件があります:

- 入荷・出荷の数量が0以下の場合は不正な引数
- 出荷時に在庫が足りない場合は在庫不足
- 商品が「廃番(discontinued)」状態の場合は入荷・出荷ともに不可

これらを **異なる例外** で表現します。

## 制約

- カスタム例外: `OutOfStockException`(`\Exception` を継承、在庫不足を表す)
- カスタム例外: `ProductDiscontinuedException`(`\Exception` を継承、廃番を表す)
- クラス: `Product`
  - プロパティ:
    - `name`(string)
    - `stock`(int、初期値0で良い)
    - `discontinued`(bool、初期値 false)
  - コンストラクタで `name` を受け取る(`stock` と `discontinued` はコンストラクタ引数にしない、初期値で開始)
  - メソッド:
    - `receive(int $quantity): void` — 入荷する
      - `$quantity` が0以下なら `\InvalidArgumentException`
      - `discontinued` が true なら `ProductDiscontinuedException`
      - 上記をパスしたら `stock` に加算
    - `ship(int $quantity): void` — 出荷する
      - `$quantity` が0以下なら `\InvalidArgumentException`
      - `discontinued` が true なら `ProductDiscontinuedException`
      - `$quantity` が `stock` を超えるなら `OutOfStockException`(メッセージに在庫数と要求数を含める)
      - 上記をパスしたら `stock` から減算
    - `discontinue(): void` — 廃番にする(`discontinued` を true にする)
    - `getStock(): int` — 現在の在庫数を返す
- `declare(strict_types=1);` を冒頭に書く

## 動作確認(ファイル末尾に書く)

1. `Product` を `name="ノートPC"` で作成
2. 以下の操作を **順に** 実行し、各操作を `try-catch` で囲む(複数の catch 節と finally を使う)
   1. `receive(0)` — 不正な引数(例外発生)
   2. `receive(10)` — 正常(在庫10に)
   3. `ship(15)` — 在庫不足(例外発生、在庫10、要求15)
   4. `ship(5)` — 正常(在庫5に)
   5. `discontinue()` — 廃番にする(try-catch 不要、例外を投げないので直接呼ぶ)
   6. `receive(3)` — 廃番(例外発生)
3. catch 節は3種類(`\InvalidArgumentException` / `OutOfStockException` / `ProductDiscontinuedException`)を使い分ける
4. finally 節で「現在の在庫: X個」を毎回出力する

## 期待される出力

```
エラー(引数不正): 数量は1以上で指定してください
現在の在庫: 0個
---
入荷完了
現在の在庫: 10個
---
エラー(在庫不足): 在庫が不足しています(在庫: 10個、要求: 15個)
現在の在庫: 10個
---
出荷完了
現在の在庫: 5個
---
エラー(廃番): この商品は廃番です
現在の在庫: 5個
---
```

入荷・出荷の正常時のメッセージ(「入荷完了」「出荷完了」)はクラス内で `echo` してください。
finally の在庫表示は呼び出し側で `$product->getStock()` を使って表示します。

## ファイル名

`my_answers/s19_stock_manager.php`

## 進め方

1. この問題文を保存する
2. `my_answers/s19_stock_manager.php` を新規作成
3. **何も見ずに** 書く(S18 のファイルを開かない)
4. `php my_answers/s19_stock_manager.php` で実行確認
5. 出力例と diff を取る(細かい文字列まで一致するか)

## ヒント(本当に詰まったら開く)

<details>
<summary>ヒント1: カスタム例外2つの定義</summary>

S18 と同じ書き方。中身は空でOK。

```
class OutOfStockException extends \Exception { }
class ProductDiscontinuedException extends \Exception { }
```

</details>

<details>
<summary>ヒント2: 例外メッセージに値を含める</summary>

S18 の `"{$email} は既に登録されています"` と同じテクニック。

```
throw new OutOfStockException("在庫が不足しています(在庫: {$this->stock}個、要求: {$quantity}個)");
```

</details>

<details>
<summary>ヒント3: 複数の操作を try-catch で繰り返す</summary>

S18 の `foreach ($testCases as [$email, $age])` のような書き方もできるが、
今回は **操作の種類が違う** ので、それぞれ個別に try-catch を書くほうが分かりやすい。

ただし、同じ catch 節と finally を6回書くのは冗長なので、**クロージャ**(無名関数)に閉じ込めると DRY に書ける:

```
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

$tryAction(fn() => $product->receive(0));
$tryAction(fn() => $product->receive(10));
// ...
```

ただしクロージャは Block 2 の範囲外(無名関数は Block 1 でも扱っていない)。
**もし難しければ、各操作で try-catch を個別に6回書いてもOK**。学習目的は例外の使い分けなので、DRY 化は気にしなくて良い。

</details>

## 確認ポイント

- カスタム例外を **2つ** 何も見ずに定義できたか
- `discontinue()` のような **状態を変えるメソッド** を書けたか(setter 的な役割)
- 各メソッド内で **複数の検証を順に書ける** か(早期 return ならぬ早期 throw)
- 複数 catch 節を使い分けて書けたか
- finally 節で共通処理を書けたか
- 出力例と完全に一致するか