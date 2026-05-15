# S1: `==` と `===` の違いを体感する

## 目的

PHP の緩い比較(`==`)と厳密な比較(`===`)の違いを、5つのパターンで確認する。
特に「直感に反する結果」が出るケースを身体で覚える。

## ファイル名

`my_answers/s01_loose_strict_equality.php`

## 取り組み方

このStepは **写経 → 再現** 方式です。

1. `solutions/s01_loose_strict_equality.php` を見ながら `my_answers/s01_loose_strict_equality.php` に書き写す
2. 実行して結果を確認する: `php my_answers/s01_loose_strict_equality.php`
3. ファイルを閉じて、何も見ずに再現する
4. 各 `var_dump` の結果がなぜそうなるのか、自分の言葉でコメントを書き直す

## 確認するパターン

以下の5パターンを比較する:

1. `"0" == false`(文字列ゼロと false)
2. `"abc" == 0`(数字ではない文字列とゼロ)※ PHP 8 で挙動が変わった有名な罠
3. `null == false`(null と false)
4. `[] == false`(空配列と false)
5. `"10" == 10`(数値文字列と整数)

各パターンで `==` と `===` の両方を試し、結果の違いを目で見る。

## 出力例

```
=== Pattern 1: "0" == false ===
"0" == false  : bool(true)
"0" === false : bool(false)

=== Pattern 2: "abc" == 0 ===
"abc" == 0  : bool(false)   ← PHP 8 から false。PHP 7 までは true だった
"abc" === 0 : bool(false)

(以下同様)
```

## ゴール

このStepが終わった時に、以下を口頭で説明できる:

- `==` は何をしているか(型変換してから比較)
- `===` は何をしているか(型も値も一致を要求)
- 業務では基本的にどちらを使うべきか