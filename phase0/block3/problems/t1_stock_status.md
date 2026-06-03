# PHP-B3-T1: 商品の在庫状態管理（Block 3 完了テスト）

Block 2 で身につけたクラス設計と、Block 3 で身につけた PHP 独自要素
（Enum / nullable / strict_types / ===）を組み合わせる総合課題です。

## 状況

商品の在庫状態を「在庫あり」「残りわずか」「在庫切れ」の3つで管理したい。
在庫状態は Enum で表現し、商品クラスが在庫数に応じて状態を判定する。

## 制約

- ファイル冒頭に `declare(strict_types=1);` を書くこと。
- 在庫状態を表す Enum `StockStatus` を作る。
  - case は `InStock`, `LowStock`, `OutOfStock` の3つ。
- `Product` クラスを作る。
  - プロパティ: `name`（string）, `quantity`（int）, `status`（StockStatus 型で nullable・初期値 null）
  - コンストラクタで `name` と `quantity` を受け取る（`status` は受け取らず null のまま）。
  - メソッド `refreshStatus(): void` … `quantity` を見て `status` を更新する。
    - `quantity` が 0 ちょうど → OutOfStock
    - `quantity` が 1〜5 → LowStock
    - `quantity` が 6 以上 → InStock
  - メソッド `describe(): string` … 現在の `status` に応じた説明文を返す。
    - 状態判定は **`===`** で行うこと。
    - `status` が null（まだ refresh していない）→ `'状態未設定'`
    - OutOfStock → `'在庫切れ'`
    - LowStock → `'残りわずか（あとN個）'`（N は quantity）
    - InStock → `'在庫あり（N個）'`（N は quantity）

## 入力

なし（コード内で商品を直接作る）。

## 出力例

以下の4商品を作って `describe()` の結果を1行ずつ出力する。
- ノートPC: quantity 10 → refreshStatus する
- マウス: quantity 3 → refreshStatus する
- キーボード: quantity 0 → refreshStatus する
- ヘッドホン: quantity 8 → **refreshStatus しない**（status は null のまま）

```
ノートPC: 在庫あり（10個）
マウス: 残りわずか（あと3個）
キーボード: 在庫切れ
ヘッドホン: 状態未設定
```

## ファイル名

`my_answers/t1_stock_status.php`

## ヒント

<details>
<summary>使う要素の方向性（コードは出しません）</summary>

- nullable プロパティ: `?StockStatus $status = null`
- Enum の比較は `$this->status === StockStatus::InStock` のように書ける
- 出力は `echo` + `PHP_EOL` でOK
- `describe()` の中は `===` でガード節風に分岐していくと読みやすい

</details>