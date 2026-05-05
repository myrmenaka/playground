# PHP-B1-S2: 解説

## 解答コード

```php
<?php

$name = "Maru";
$age = 28;
$isStudent = true;

var_dump($name);
var_dump($age);
var_dump($isStudent);
```

## 実行結果

```
string(4) "Maru"
int(28)
bool(true)
```

`string(4)` の `4` はバイト数。日本語(マルチバイト文字)を入れるとバイト数が増える点に注意:

```php
$name = "丸山";  // string(6) "丸山"  ※UTF-8で1文字3バイト
```

## 解説

### 変数の宣言

PHP の変数は **必ず `$` で始める**。これは Java や C 系言語との大きな違い。

```php
$name = "Maru";  // 宣言と代入を同時に行う
```

宣言だけ(代入なし)はできない。値を代入することで初めて変数として存在する。

### 動的型付け

PHP は動的型付け言語。型を明示しなくても、代入された値から型が自動で決まる。

```php
$x = 10;       // int
$x = "hello";  // 同じ変数に文字列を入れても、再代入できる(型が string に変わる)
```

ただし、業務コードでは「変数の型が途中で変わる」のは可読性を下げるので、基本的には1つの変数には同じ型の値を入れるのが慣習。

### `var_dump`

値と型をセットで表示するデバッグ関数。学習・デバッグで多用する。
Java の `System.out.println()` よりも詳細な情報が出るのが利点。

```php
var_dump($age);
// → int(28)
```

### 変数名の命名規則

PHP の慣習(PSR-12 ベース):
- 変数名は **camelCase**(例: `$userName`、`$isStudent`)
- 定数は **UPPER_SNAKE_CASE**(例: `PHP_EOL`、`MAX_SIZE`)
- クラス名は **PascalCase**(例: `UserRepository`)

Java と概ね同じ感覚でOK。

## 別解

### 1行で複数変数を宣言する書き方

PHP には Java の `int a, b, c;` のような複数変数同時宣言の構文はない。1行ずつ書くか、`list()` を使う:

```php
[$name, $age, $isStudent] = ["Maru", 28, true];
```

### `print_r` を使う書き方

`var_dump` の代わりに `print_r` も使える。違いは:

| 関数 | 表示内容 | 用途 |
|------|---------|------|
| `var_dump` | 型 + 値 + サイズ | デバッグ向き(詳細) |
| `print_r` | 値のみ(人間が読みやすい形式) | 配列・オブジェクトの構造確認向き |

```php
print_r($name);     // Maru
print_r($age);      // 28
print_r($isStudent); // 1  ※boolは見えにくい
```

→ **学習中は `var_dump` を推奨**。型が分かるので動作確認に最適。

## Java との対比

| 観点 | Java | PHP |
|------|------|-----|
| 変数宣言 | `String name = "Maru";` | `$name = "Maru";` |
| 型宣言 | 必須 | 不要(動的型付け) |
| 変数のプレフィックス | なし | 必ず `$` |
| デバッグ出力 | `System.out.println(name)` | `var_dump($name)` |
| `boolean` 型 | `boolean` | `bool` |
| `integer` 型 | `int` | `int` |
| 文字列型 | `String`(クラス) | `string`(プリミティブ的) |

## つまずきやすいポイント

- **`$` を忘れる**: `name = "Maru"` と書いてしまい、`Parse error` になる
- **変数名のタイポに気付きにくい**: 動的型付けゆえ、`$nmae` と書いても宣言したことになり、エラーが出るのは使う時。Java の IDE のような厳密な警告は出ない
- **`var_dump` の括弧の中で `echo` を使ってしまう**: `var_dump(echo $name)` のような書き方はできない。`var_dump` は値を引数に取る関数

## 補足: 静的解析ツールについて

PHP には Java の IDE のような厳密な型チェックがないため、以下のツールで補強するのが現場の主流:

- **PHPStan** / **Psalm**: 静的解析ツール(後の Phase で扱う可能性あり)
- **PHP Intelephense**: VSCode拡張(既にインストール済み)

今は気にしなくてOK。