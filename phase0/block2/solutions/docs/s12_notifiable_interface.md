# S12 解説: Notifiable インターフェースの定義

## ポイント

### 1. `interface` キーワード

クラスは `class`、インターフェースは `interface` で宣言する。
これは Java と同じ。

### 2. メソッド本体は書かない

インターフェースは「契約」なので、中身は書かない。
波カッコ `{ ... }` ではなく、セミコロン `;` で終わる。

```php
// ✕ インターフェースで本体を書いてはいけない
public function notify(): string
{
    return "通知";
}

// ◯ 宣言のみ
public function notify(): string;
```

### 3. 戻り値の型宣言

`: string` の部分。
PHP 7 以降、戻り値の型を宣言できる。インターフェースでは特に推奨される。
これがあると、実装クラス側でも戻り値の型を守らないとエラーになる。

### 4. `public` は省略可だが、明示する

インターフェースのメソッドは必ず `public`(他は書けない)。
省略しても動くが、**明示的に `public` を書く** のが現代的な PHP のスタイル。

## Java との対比

| 観点 | Java | PHP |
|------|------|-----|
| キーワード | `interface` | `interface` |
| メソッド本体 | 書かない(`abstract` 相当) | 書かない |
| アクセス修飾子 | 暗黙的に public | 明示的に public(省略可) |
| 戻り値の型 | 必須(`String`) | PHP 7+ で書ける(`: string`) |
| 多重実装 | `implements A, B` | `implements A, B`(同じ) |
| デフォルトメソッド | Java 8+ で可能 | PHP では **不可**(抽象クラスを使う) |

PHP のインターフェースは Java 7 までの古典的なインターフェースに近い。
「デフォルト実装を持たせたい」場合は、PHP では抽象クラス(次の S15 で扱う)を使う。

## つまずきやすい点

### A. メソッド本体を書いてしまう

```php
Fatal error: Interface function Notifiable::notify() cannot contain body
```
このエラーが出たら、`{ ... }` を `;` に直す。

### B. プロパティを書いてしまう

インターフェースには **プロパティを宣言できない**(定数は可)。
Java と同じ感覚で書けるが、`private $name;` のような行を書くとエラーになる。

```php
// ✕ インターフェースにプロパティは書けない
interface Notifiable
{
    private string $name;
    public function notify(): string;
}

// ◯ 定数は書ける
interface Notifiable
{
    const VERSION = '1.0';
    public function notify(): string;
}
```

### C. インターフェースだけ書いても何も起きない

このファイル単体では `php` コマンドで実行しても何も出力されない。
それで正常。次の S13 で `implements` して実際に使う。

## 別解: 引数を持たせるパターン

仕様には書いていないが、現実のインターフェースは引数を取ることが多い。
```php
interface Notifiable
{
    public function notify(string $message): string;
}
```

S13・S14 では引数なしの版で進める。