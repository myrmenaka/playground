# S2 解説: nullable 型と Union 型

## このStepのポイント

PHP の型宣言のうち、業務で頻繁に使う2つを実際に書いて確認した:

- nullable 型(`?T`): 型 `T` または `null` を許容
- Union 型(`T1|T2`): 複数の型を許容

## nullable 型の2つの書き方

実は `?string` と `string|null` は **完全に同じ意味** で、表記が違うだけ。

```php
// この2つは同じ
function foo(): ?string { ... }
function foo(): string|null { ... }
```

慣例的に、null 許容を表したい時は `?string` のほうがよく使われる。
Union 型として明示的に書きたい時は `string|null` を使う、という使い分けが多い。

## nullable 型が活躍する場面

業務でよく使うのは以下の3つ:

1. **検索結果が見つからないケース**
```php
   function findUserById(int $id): ?User { ... }
```
   見つからなければ null を返す。Laravel の Eloquent でも `User::find($id)` は `?User` を返す(見つからなければ null)。

2. **オプショナルな引数**
```php
   function sendEmail(string $to, ?string $cc = null): void { ... }
```
   CC は任意なので nullable にしておく。

3. **未設定の値**
```php
   class User {
       private ?string $middleName = null;  // ミドルネームは無い人もいる
   }
```

## Union 型が活躍する場面

PHP 8.0 で導入された。Java の世界には存在しない型(Java は単一型のみ)。
業務でよく見るのは:

1. **ID として int か string のどちらでも受け取りたいケース**
```php
   function findById(int|string $id): ?Model { ... }
```
   UUID(文字列)も連番ID(整数)も両方受け取れる API でよく使う。

2. **複数の型を返す可能性のあるケース**
```php
   function parseValue(string $input): int|float|string { ... }
```

3. **設定値の型が複数あり得るケース**
```php
   function setConfig(string $key, int|string|bool $value): void { ... }
```

## Java との対比

| Java | PHP |
|------|-----|
| `Optional<String>` | `?string` |
| `@Nullable String` | `?string` |
| (なし、Java は単一型のみ) | `int|string` |

Java では null 安全を `Optional` でラップして表現するが、PHP は型宣言レベルで簡潔に表現できる。

## strict_types との関係

`declare(strict_types=1);` を冒頭に書いていると、型は **厳密にチェック** される。

```php
declare(strict_types=1);

function add(int $a, int $b): int {
    return $a + $b;
}

add(1, 2);     // OK
add("1", "2"); // TypeError! (strict_types なしなら自動変換されていた)
```

これは次の S3 で扱う。

## つまずきやすい点

### 1. nullable と「省略可能」は別の概念

- `?string $name` = 「null を渡してもOK」だが、**省略はできない**
- `?string $name = null` = 「null を渡してもOK」かつ「省略時は null になる」

```php
function foo(?string $name) { ... }
foo();      // エラー! 引数が足りない
foo(null);  // OK

function bar(?string $name = null) { ... }
bar();      // OK ($name は null になる)
bar(null);  // OK
```

### 2. Union 型の戻り値は使う側で型分岐が必要

```php
function getValue(): int|string { ... }

$v = getValue();
// この時点で $v は int か string か分からない
if (is_int($v)) {
    // ここでは int として扱える
}
```

型分岐用の関数:
- `is_int($v)`
- `is_string($v)`
- `is_array($v)`
- `is_null($v)`
- `is_bool($v)`
- `is_float($v)`

### 3. nullable 型と null チェックを忘れがち

```php
function findUserName(?int $id): ?string {
    return $users[$id]; // $id が null だとエラー!
}
```

nullable を受け取ったら、必ず最初に null チェックする習慣を。

## 関連する公式マニュアル

- [型宣言](https://www.php.net/manual/ja/language.types.declarations.php)
- [Union 型](https://www.php.net/manual/ja/language.types.declarations.php#language.types.declarations.composite.union)