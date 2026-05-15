# S3 解説: `declare(strict_types=1)` の意味

## このStepのポイント

PHP には型チェックの2つのモードがあり、`declare(strict_types=1);` でモードを切り替えられる。

| モード | 書き方 | 挙動 |
|--------|--------|------|
| 強制モード | 何も書かない(デフォルト) | 型が違っても自動変換できればOK |
| 厳密モード | `declare(strict_types=1);` を冒頭に書く | 型が違ったらエラー |

## 実行結果の比較

### 強制モード (`s03a_no_strict.php`)

```
add(1, 2)         = int(3)             ← OK
add("3", "4")     = int(7)             ← 自動変換されて OK !
add("abc", "def") = TypeError          ← さすがにエラー
```

### 厳密モード (`s03b_with_strict.php`)

```
add(1, 2)         = int(3)             ← OK
add("3", "4")     = TypeError          ← 型が違うのでエラー
add("abc", "def") = TypeError          ← 当然エラー
```

注目すべきは2番目。**強制モードでは `"3"` が暗黙的に `3` に変換されて通ってしまう**。
これは便利に見えるが、バグを隠してしまう挙動でもある。

## なぜ業務では厳密モードを使うか

### 1. バグを早期発見できる

例:フォームから受け取った値(常に文字列)を、整数を期待する関数に渡してしまった場合。

- 強制モード: 自動変換されて動いてしまう → バグに気づかない
- 厳密モード: 即座にエラー → どこで型を間違えたか即座に分かる

### 2. 型シグネチャが「信用できる」ようになる

関数のシグネチャに `function add(int $a, int $b): int` と書いてあっても、強制モードでは「実際は string も渡せる」ということになる。これでは型宣言の意味が薄い。

厳密モードなら、**型宣言通りの値しか入ってこないことが保証される**。
Java で言えば `void add(int a, int b)` に文字列を渡せないのと同じ感覚。

### 3. モダンPHPの標準作法

Laravel、Symfony などの主要フレームワーク、PHPStan などの静的解析ツールを使う現場では、**すべての PHP ファイルの冒頭に `declare(strict_types=1);` を書く** のが標準。

Laravel 本体のソースコードでも、ほぼすべてのファイルで使われている。

## 書く位置の制約

`declare(strict_types=1);` は **ファイルの一番上に書く必要がある**。

```php
<?php

declare(strict_types=1);  // ← <?php の直後

// この後にコードが続く
```

`<?php` タグの直後、他のコードより前。コメントや空行はあってもよいが、有効なPHPコード(`use` 文を含む)があるとエラーになる。

また、`declare(strict_types=1);` は **書いたファイル内だけ** に効く。
別ファイルで定義された関数を呼ぶときは、**呼び出し側のファイルのモード** が適用される。

```php
// file_a.php (厳密モードなし)
function add(int $a, int $b): int { return $a + $b; }

// file_b.php (厳密モードあり)
declare(strict_types=1);
require 'file_a.php';
add("3", "4");  // TypeError! (呼び出し側が厳密モードだから)
```

これは直感に反するので、最初は混乱しやすいポイント。

## Java との対比

Java は最初から厳密な型システムを持っているので、PHP のような「モード切り替え」は存在しない。

| 言語 | 型チェック |
|------|-----------|
| Java | 常に厳密(コンパイル時にチェック) |
| PHP デフォルト | 強制モード(緩い、自動変換あり) |
| PHP `strict_types=1` | 厳密モード(Java に近い) |

つまり `declare(strict_types=1);` は **「PHP の型システムを Java 風に厳しくする」スイッチ** と理解しても、最初はそれで十分。

## つまずきやすい点

### 1. 戻り値の型にも効く

引数だけでなく、戻り値の型も厳密モードでチェックされる。

```php
declare(strict_types=1);

function getName(): string {
    return 123;  // TypeError! int を返している
}
```

### 2. nullable 型はそのまま使える

厳密モードでも `?string` や `int|null` は普通に動く。
nullable は「型の許容範囲を広げる」だけで、型変換とは別の話。

### 3. クラスのプロパティ型にも影響

PHP 7.4 から導入されたプロパティの型宣言にも、厳密モードのルールが適用される。

```php
declare(strict_types=1);

class User {
    public int $age;
}

$user = new User();
$user->age = "20";  // TypeError!
```

## ノートアプリへのメモ推奨内容

このStepの学びを、ノートアプリに以下のような形でメモしておく:

```
## strict_types とは

- `declare(strict_types=1);` をファイル冒頭に書くと「厳密モード」になる
- 書かないと「強制モード」(自動型変換あり)
- 業務では原則「常に書く」が作法

## なぜ書くか

- バグを早期発見できる("3" が黙って 3 に変換されない)
- 型宣言が信用できるようになる
- Laravel など主要フレームワークの標準作法

## 注意点

- `<?php` の直後、他のコードより前に書く
- 効くのは「書いたファイル内」だけ
- 戻り値・プロパティの型にも適用される
```

## 関連する公式マニュアル

- [declare](https://www.php.net/manual/ja/control-structures.declare.php)
- [型宣言 - 厳密な型付け](https://www.php.net/manual/ja/language.types.declarations.php#language.types.declarations.strict)