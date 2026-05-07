# S12: 配列の全要素を foreach で出力

果物の名前が入った配列を `foreach` で順に出力するプログラムを書いてください。

## 制約

- `foreach` 文を使うこと(`for` や `while` は不可)
- 出力には `echo` を使うこと
- 各要素の後に改行を入れること
- 配列は以下の通り定義すること

```php
$fruits = ['apple', 'banana', 'cherry', 'durian', 'elderberry'];
```

## 入力

なし(配列は上記をそのまま使う)

## 出力例

```
apple
banana
cherry
durian
elderberry
```

## ファイル名

`my_answers/s12_foreach.php`

## ヒント

<details>
<summary>クリックで表示</summary>

- `foreach` の基本構文は `foreach ($配列 as $変数) { ... }`
- ループの中で `$変数` を使うと、配列の各要素にアクセスできる
- Java の拡張for文 `for (String fruit : fruits)` と同じ感覚で書ける

</details>