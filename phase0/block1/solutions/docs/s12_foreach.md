# S12 解説: 配列の全要素を foreach で出力

## ポイント

### `foreach` の基本構文

```php
foreach ($配列 as $要素変数) {
    // 各要素に対する処理
}
```

- `$配列` は対象の配列
- `$要素変数` はループの各反復で「現在の要素」を受け取る変数(名前は自由)
- 配列の先頭から末尾まで自動的に走査する

`for` のようにインデックスを管理する必要がないため、配列の全要素を扱う場合は `foreach` が圧倒的に楽。

### 命名規則の慣習

```php
foreach ($fruits as $fruit)    // 複数形 → 単数形
foreach ($users as $user)      // 同上
foreach ($items as $item)      // 同上
```

配列名を複数形、要素変数を単数形にするのは多くの言語で共通する慣習。コードを読む人が「これは1要素」と即座に理解できる。

## 別解

### キーも一緒に取得する(次の S13 で扱う)

```php
foreach ($fruits as $key => $fruit) {
    echo $key . ': ' . $fruit . PHP_EOL;
}
```

出力:
```
0: apple
1: banana
...
```

### 参照渡しで要素を変更する

```php
foreach ($fruits as &$fruit) {
    $fruit = strtoupper($fruit);
}
unset($fruit);  // 参照を解除する(重要)
```

`&` をつけると配列の要素そのものを書き換えられる。ただし、ループ後に `unset()` で参照を解除しないと、後の処理で意図しない値の上書きが起きることがある。Java にはない概念なので注意。

### `for` で書いた場合(比較用)

```php
$fruits = ['apple', 'banana', 'cherry', 'durian', 'elderberry'];

for ($i = 0; $i < count($fruits); $i++) {
    echo $fruits[$i] . PHP_EOL;
}
```

動くが、`foreach` のほうが意図が明確で短い。インデックスが必要ない場面で `for` を使うのは PHP では避けたい書き方。

## つまずきやすい点

### 1. `as` の左右を間違える

```php
foreach ($fruits as $fruit)    // ⭕ 配列 as 要素変数
foreach ($fruit as $fruits)    // ❌ 配列名と変数名が逆
```

「`$fruits` を取り出して `$fruit` として使う」と覚える。Java の `for (String fruit : fruits)` と語順が逆なので最初は戸惑うかもしれない。

### 2. ループ内で配列自体を変更する

```php
foreach ($fruits as $fruit) {
    $fruits[] = 'fig';  // ❌ 無限ループにはならないが予測不能な挙動
}
```

`foreach` はループ開始時に配列のコピーで走査する仕様だが、混乱の元になるので避ける。要素を追加・削除したい場合は別の配列を用意するか、`for` を使う。

### 3. 参照渡し後の `unset` 忘れ

```php
foreach ($fruits as &$fruit) {
    $fruit = strtoupper($fruit);
}
// unset($fruit); を忘れると...

foreach ($fruits as $fruit) {
    // ここで $fruit が直前の参照を持ち続けるため、最後の要素が壊れる
}
```

参照渡しを使ったら必ず `unset($fruit)` をセットで書く、と覚えておく。

## Java との対比

```java
// Java の拡張for文
String[] fruits = {"apple", "banana", "cherry", "durian", "elderberry"};

for (String fruit : fruits) {
    System.out.println(fruit);
}
```

```php
// PHP の foreach
$fruits = ['apple', 'banana', 'cherry', 'durian', 'elderberry'];

foreach ($fruits as $fruit) {
    echo $fruit . PHP_EOL;
}
```

| 観点 | Java | PHP |
|------|------|-----|
| キーワード | `for` | `foreach` |
| 構文 | `for (型 変数 : 配列)` | `foreach ($配列 as $変数)` |
| 順序 | 変数 ← 配列(右から左) | 配列 → 変数(左から右) |
| 型宣言 | 必要 | 不要 |
| キー取得 | `Map.Entry` を使う必要あり | `as $key => $value` で簡単 |
| 参照変更 | できない(プリミティブ) | `&` で可能 |

`foreach` は PHP の最も使われる制御構文の1つ。配列を扱う場面では8割方これを使う。Java の拡張for文に対応する機能だが、**キーも値も一発で取れる**点と**参照渡しで書き換えられる**点が PHP 独自の利点。