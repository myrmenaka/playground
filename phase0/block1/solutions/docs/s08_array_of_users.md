# PHP-B1-S8: 解説

## 解答コード

```php
<?php

$users = [
    ['name' => 'Maru', 'age' => 28],
    ['name' => 'Akane', 'age' => 33],
    ['name' => 'Taro', 'age' => 25],
];

echo $users[0]['name'] . PHP_EOL;
echo $users[1]['name'] . PHP_EOL;
echo $users[2]['name'] . PHP_EOL;
```

## 解説

### 多次元配列とは

PHP の配列は **入れ子にできる**。配列の要素として配列を持つ構造を「多次元配列」と呼ぶ。

```php
$users = [
    ['name' => 'Maru', 'age' => 28],   // ← 1つ目の要素は連想配列
    ['name' => 'Akane', 'age' => 33],  // ← 2つ目の要素も連想配列
    ['name' => 'Taro', 'age' => 25],
];
```

この場合の構造:

| 階層 | 種類 | 例 |
|------|------|-----|
| 外側 | インデックス配列 | `$users[0]`、`$users[1]`、`$users[2]` |
| 内側 | 連想配列 | `['name' => 'Maru', 'age' => 28]` |

### ネストしたアクセス

外側のインデックスでユーザーを選び、内側のキーで値を取り出す:

```php
$users[0]['name']  // 'Maru'   ← 0番目のユーザーの name
$users[1]['age']   // 33       ← 1番目のユーザーの age
$users[2]['name']  // 'Taro'
```

**Java の対比で考えると分かりやすい**:

```java
// Java
List<Map<String, Object>> users = ...;
users.get(0).get("name");
```

```php
// PHP
$users[0]['name'];
```

### この構造はどこで使う?

業務で **超頻出**。例:

#### 1. DBから取得したレコード一覧

```php
// Eloquent で全ユーザーを取得して配列化
$users = User::all()->toArray();
// 結果: [['name' => '...', 'email' => '...'], ['name' => '...', 'email' => '...'], ...]
```

#### 2. JSON のデコード結果

```php
$json = '[{"name":"Maru","age":28},{"name":"Akane","age":33}]';
$users = json_decode($json, true);  // 第2引数 true で連想配列にする
// $users[0]['name'] でアクセス可能
```

#### 3. CSV や Excel の読み込み結果

```php
$rows = [
    ['name' => 'Maru', 'age' => 28],
    ['name' => 'Akane', 'age' => 33],
];
```

つまり、この S8 で扱った構造は **業務でほぼ毎日触る形**。しっかり馴染ませておくと後が楽。

## 別解

### 末尾カンマ(trailing comma)

PHP では配列リテラルの最後の要素の後ろにカンマを付けてOK:

```php
$users = [
    ['name' => 'Maru', 'age' => 28],
    ['name' => 'Akane', 'age' => 33],
    ['name' => 'Taro', 'age' => 25],   // ← 末尾カンマ(推奨)
];
```

**業務コードでは付けるのが推奨**。理由:
- 後から要素を追加する時、前の行を変更しなくて済む(diff が綺麗になる)
- Laravel の標準フォーマッタも末尾カンマを付ける

### より整列した書き方(可読性重視)

```php
$users = [
    ['name' => 'Maru',  'age' => 28],
    ['name' => 'Akane', 'age' => 33],
    ['name' => 'Taro',  'age' => 25],
];
```

スペースで揃えると視覚的に見やすい。ただしフォーマッタで自動整形すると消されることもあるので、好みとチームのルール次第。

### `foreach` を使った汎用的な書き方(S12〜S13 で学ぶ)

```php
foreach ($users as $user) {
    echo $user['name'] . PHP_EOL;
}
```

`foreach` を使えば、ユーザーが100人いても同じコード。**実務ではほぼこの形** を使う。
S8 では「個別アクセスで構造を体感する」のが目的なので、あえてループを使わない。

## Java との対比

### 構造の比較

| 観点 | Java | PHP |
|------|------|-----|
| 配列の配列 | `int[][] arr = {{1,2}, {3,4}};` | `$arr = [[1,2], [3,4]];` |
| List + Map | `List<Map<String, Object>>` | `$users = [['name' => '...'], ...]` |
| ネストアクセス | `users.get(0).get("name")` | `$users[0]['name']` |
| 型安全性 | あり(ジェネリクス) | なし(混在可能) |

### 型の制約がない

PHP では同じ配列に異なる構造の要素を入れることができてしまう:

```php
$mixed = [
    ['name' => 'Maru', 'age' => 28],
    ['title' => 'PHP本', 'price' => 2000],  // 異なる構造
    'string',                                 // 文字列まで混在可能
];
```

**できるが、業務では絶対にやらない**。同じ配列の要素は同じ構造に揃えるのが基本。

## つまずきやすいポイント

### 1. インデックスとキーの順序を間違える

```php
$users[0]['name']  // 正しい(外側インデックス → 内側キー)
$users['name'][0]  // 間違い(警告 + 空出力)
```

外側がインデックス配列、内側が連想配列の場合、**外側→内側** の順でアクセスする。

### 2. 「連想配列の連想配列」と混同する

```php
// インデックス配列 of 連想配列(S8 の形)
$users = [
    ['name' => 'Maru'],
    ['name' => 'Akane'],
];
$users[0]['name'];  // インデックスでアクセス

// 連想配列 of 連想配列
$usersByKey = [
    'maru' => ['name' => 'Maru'],
    'akane' => ['name' => 'Akane'],
];
$usersByKey['maru']['name'];  // キーでアクセス
```

**業務でどちらの形式を使うかは要件次第**:
- IDで引きたい時は連想配列 of 連想配列(`$users[42]['name']` で ID 42 のユーザー)
- 順番に処理する時はインデックス配列 of 連想配列(S8 の形)

### 3. ネストが深くなりすぎる

```php
$data['users'][0]['posts'][2]['comments'][1]['author']['name'];
```

PHPでは構文的に書けるが、こうなるとコードの可読性が下がる。
業務では3階層以上ネストさせないように設計する。深くなりそうならクラスやオブジェクトに切り出す。

### 4. 存在しないインデックス・キーへのアクセス

```php
$users = [['name' => 'Maru']];
echo $users[5]['name'];        // Warning: Undefined array key 5
                                // さらに、null['name'] でもう1つ警告
```

PHP 8 から警告が厳しくなったが、依然として例外にはならない。
**ループや存在チェックと組み合わせて安全に書く** のが基本。

```php
if (isset($users[5])) {
    echo $users[5]['name'];
}
```

### 5. `var_dump` で確認する習慣

ネストが深くなると、目視だけでは構造が分かりにくい。困ったら `var_dump` で構造を確認:

```php
var_dump($users);
// array(3) {
//   [0]=> array(2) {
//     ["name"]=> string(4) "Maru"
//     ["age"]=> int(28)
//   }
//   [1]=> array(2) {...}
//   [2]=> array(2) {...}
// }
```

## まとめ

この Step で学んだ「インデックス配列 of 連想配列」は、PHP/Laravel の業務で **最も触る配列構造**。
今は `$users[0]['name']` のような個別アクセスで構造を体感し、S12〜S13 で `foreach` を学んだら自然と:

```php
foreach ($users as $user) {
    echo $user['name'] . PHP_EOL;
}
```

と書けるようになる。