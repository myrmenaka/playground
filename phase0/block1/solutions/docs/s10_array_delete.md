# PHP-B1-S10: 解説

## 解答コード

```php
<?php

$arr = [10, 20, 30, 40, 50];

// パターン1: unset(指定したキーを削除、キーは詰められない)
unset($arr[1]);
print_r($arr);

// パターン2: array_pop(末尾を削除、戻り値で削除した値を取得)
$removed = array_pop($arr);
print_r($arr);

echo "削除した値: {$removed}" . PHP_EOL;
```

## 解説

### パターン1: `unset` 関数

```php
$arr = [10, 20, 30];
unset($arr[1]);
print_r($arr);
// Array ( [0] => 10 [2] => 30 )
//                   ^^^
//             キー 1 がスキップされる
```

**特徴**:
- 指定したキー(任意の位置)の要素を削除できる
- **キーは詰められない**(飛び石になる)
- 戻り値はない(`void`)

#### `unset` のキーが詰められない問題

これは PHP 初学者がほぼ必ずハマる罠。

```php
$arr = [10, 20, 30];
unset($arr[1]);
// $arr = [0 => 10, 2 => 30]  ← キーが 0, 2 で 1 が抜ける

// JSON 化すると…
echo json_encode($arr);
// {"0":10,"2":30}  ← オブジェクトとしてエンコードされる!
```

**正しい配列(JSON で言う Array)に戻すには `array_values`**:

```php
$arr = array_values($arr);
// $arr = [0 => 10, 1 => 30]  ← キーが 0 から振り直された

echo json_encode($arr);
// [10,30]  ← Array としてエンコードされる
```

### パターン2: `array_pop` 関数

```php
$arr = [10, 20, 30];
$removed = array_pop($arr);
print_r($arr);     // Array ( [0] => 10 [1] => 20 )
echo $removed;     // 30
```

**特徴**:
- **末尾の要素**を削除する
- 削除した値を **戻り値として返す**
- 元の配列を直接変更する(参照渡し)
- 空の配列に対して呼ぶと `null` を返す

#### スタック操作との対比

`array_pop` はスタック(LIFO)的な使い方ができる:

```php
$stack = [];
array_push($stack, 'a');  // ['a']
array_push($stack, 'b');  // ['a', 'b']
$top = array_pop($stack); // ['a'], $top = 'b'
```

S9 で学んだ `array_push` とペアで使うと、スタック構造を実現できる。

## 別解(削除の他の方法)

### `array_shift`(先頭から削除)

```php
$arr = [10, 20, 30];
$first = array_shift($arr);
// $arr = [0 => 20, 1 => 30](キーは振り直される!)
// $first = 10
```

`array_pop` と対をなす関数。**こちらはキーが振り直される** 点に注意。
キュー(FIFO)的な使い方:

```php
$queue = [];
array_push($queue, 'task1');
array_push($queue, 'task2');
$next = array_shift($queue);  // 'task1' を取り出す
```

### `array_splice`(指定位置から削除、置換も可能)

```php
$arr = [10, 20, 30, 40, 50];
array_splice($arr, 1, 2);  // インデックス1から2個削除
// $arr = [10, 40, 50](キーも詰められる)
```

`unset` と違い、**キーが詰められる** のが特徴。
削除しつつ置換もできる:

```php
$arr = [10, 20, 30];
array_splice($arr, 1, 1, [200, 250]);
// $arr = [10, 200, 250, 30](20 を 200, 250 に置き換え)
```

### `array_filter`(条件で絞り込み)

S20 で学ぶが、参考まで:

```php
$arr = [10, 20, 30, 40, 50];
$arr = array_filter($arr, fn($v) => $v !== 30);  // 30 を除外
// $arr = [0 => 10, 1 => 20, 3 => 40, 4 => 50](キーは詰められない)
```

### `array_diff`(配列の差分)

```php
$arr = [10, 20, 30, 40, 50];
$arr = array_diff($arr, [20, 40]);  // 20 と 40 を除外
// $arr = [0 => 10, 2 => 30, 4 => 50](キーは詰められない)
```

## 削除方法の比較表

| 方法 | 削除位置 | キーが詰められるか | 戻り値 | 用途 |
|------|---------|----------------|-------|------|
| `unset` | 任意のキー | ❌ | なし | 特定のキーをピンポイント削除 |
| `array_pop` | 末尾 | ✅(末尾なので関係ない) | 削除した値 | スタック操作 |
| `array_shift` | 先頭 | ✅ | 削除した値 | キュー操作 |
| `array_splice` | 任意の位置・複数 | ✅ | 削除した部分 | 範囲削除・置換 |
| `array_filter` | 条件にマッチする要素 | ❌ | 新しい配列 | 条件絞り込み |
| `array_diff` | 値で指定 | ❌ | 新しい配列 | 配列同士の差分 |

## Java との対比

| Java(`ArrayList`) | PHP |
|------------------|-----|
| `list.remove(index)` | `unset($arr[$index])`(キー残る)/ `array_splice` |
| `list.remove(value)` | `array_diff`、`array_filter` |
| `list.clear()` | `$arr = [];` |
| (スタックの pop) | `array_pop($arr)` |
| (キューの poll) | `array_shift($arr)` |

Java の `list.remove(index)` は自動でキーが詰められるが、PHP の `unset` は詰められない。
**「unset するとキーが飛び石になる」は PHP 特有の重要な挙動**。

## つまずきやすいポイント

### 1. `unset` 後のキーが詰められないことに気づかない

業務で起きがちなバグ:

```php
$users = User::all()->toArray();  // [0, 1, 2, 3, 4] のキー
unset($users[2]);                  // 退会ユーザーを除外

// この後の処理…
foreach ($users as $i => $user) {
    echo "ユーザー{$i}: {$user['name']}\n";
}
// 出力: ユーザー0、ユーザー1、ユーザー3、ユーザー4
//                              ^^
//                       2 がスキップされて違和感

// JSON でフロントに返すと…
return response()->json($users);
// → {"0":...,"1":...,"3":...,"4":...} オブジェクト形式!
//   フロントが配列として処理しようとして壊れる
```

**対策**: `unset` の後は必要に応じて `array_values` を呼ぶ。

```php
unset($users[2]);
$users = array_values($users);  // キーを 0 から振り直す
```

### 2. `array_pop` の戻り値を捨てる

```php
array_pop($arr);  // 削除はされるが、削除した値を捨てている
```

これは間違いではないが、**戻り値を活かす書き方** ができないか考えると良い:

```php
$last = array_pop($arr);  // 末尾を取り出して使う
```

### 3. 連想配列で `array_pop` を使うと?

```php
$user = ['name' => 'Maru', 'age' => 28, 'email' => 'maru@example.com'];
$last = array_pop($user);
// $user = ['name' => 'Maru', 'age' => 28]
// $last = 'maru@example.com'
```

連想配列でも **最後に追加された要素** を削除する。
ただし業務では連想配列に `array_pop` を使うことはほぼない。

### 4. `unset` で変数自体を消そうとして配列要素が消えない

```php
unset($arr[1]);   // $arr の中の要素を削除
unset($arr);      // $arr 変数自体を削除(配列が消える)
```

`unset` は変数も削除できる。**配列のキー指定を忘れると、配列ごと消える** ので注意。

## 実務での使用頻度

業務コード(Laravel等)で見る頻度:

```
unset($arr[$key]);          ★★★★☆ 連想配列のキー削除でよく使う
array_filter / array_diff   ★★★★☆ 条件絞り込みで頻出
array_pop / array_shift     ★★☆☆☆ スタック・キュー処理で稀に
array_splice                ★☆☆☆☆ ほぼ見ない
```

**Laravel では Collection を使うことが多い**ので、生の配列を触る場面が減ります。
ただし「読めれば良い」レベルでこれらを覚えておくと、レガシーコードや低レイヤで困らない。

## 学習者の実装例

実際にこの問題を解いた際のコード例:

```php
<?php

$array = [10, 20, 30, 40, 50];

unset($array[2]);
print_r($array);

array_pop($array);
print_r($array);
```

→ `unset` でインデックス 2(値 30)を削除、`array_pop` で末尾(値 50)を削除。
2パターンを正しく使い分け、各削除直後に `print_r` で確認できている。
シンプルで意図が明確なコード。

なお、`unset($array[2])` 後の出力をよく見ると、キーが `[0, 1, 3, 4]` となっており、**キー 2 がスキップされている** ことが観察できる。