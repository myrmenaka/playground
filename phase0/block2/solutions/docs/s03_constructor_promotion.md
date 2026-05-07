# S03 解説: コンストラクタプロモーションでUserクラスを書き直す

## ポイント

### コンストラクタプロモーションとは

PHP 8.0 で導入された機能。**コンストラクタの引数に可視性修飾子(`private`、`protected`、`public`)を付けると、自動的に同名のプロパティが定義され、引数の値がセットされる**。

```php
// 従来の書き方(S01)
class User
{
    private string $name;       // ← プロパティ宣言
    private int $age;

    public function __construct(string $name, int $age)
    {
        $this->name = $name;    // ← 代入処理
        $this->age = $age;
    }
}

// コンストラクタプロモーション(S03)
class User
{
    public function __construct(
        private string $name,   // 「private 型 引数名」と書くだけで
        private int $age,       // プロパティ宣言+代入が同時に行われる
    ) {}
}
```

**コードが約半分になる**。Laravel のソースコード、Symfony、PHPStan など、PHP 8 以降の主要なライブラリではこの書き方が標準。

### 何が「プロモーション」(昇格)されているのか

「**引数がプロパティに昇格する**」という意味。

```php
public function __construct(
    private string $name,    // 引数 $name が、プロパティ $this->name に昇格
    private int $age,        // 引数 $age が、プロパティ $this->age に昇格
)
```

引数として受け取った値が、自動的に同名の `private` プロパティとして保存される。プロパティ名の変更や、別の処理を挟みたい場合は従来の書き方を使う。

### 可視性修飾子は必須

```php
public function __construct(
    string $name,            // ❌ 修飾子なし → ただの引数(プロパティにならない)
    private string $age,     // ⭕ プロパティになる
)
```

`private`、`protected`、`public` のいずれかを付けないとプロモーションされない。**修飾子を付けるかどうかで挙動が完全に変わる**ので注意。

### `readonly` も組み合わせられる(PHP 8.1+)

```php
class User
{
    public function __construct(
        private readonly string $name,
        private readonly int $age,
    ) {}
}
```

`readonly` を付けると、コンストラクタ内でしか値をセットできなくなる(イミュータブル)。Java の `final` フィールドに相当。

```php
$user = new User('Alice', 25);
// $user->name = 'Bob';   // ❌ Cannot modify readonly property
```

**実務では readonly を積極的に使うことが推奨されている**。値オブジェクト(Value Object)や DTO(Data Transfer Object)との相性が抜群。Phase 5 の DDD で頻出する。

### 引数の最後にトレイリングカンマ

```php
public function __construct(
    private string $name,
    private int $age,        // ← 末尾にもカンマ
) {}
```

PHP 8.0 から関数・メソッドの引数のトレイリングカンマがサポートされた。コンストラクタプロモーションの引数は縦に並べることが多いので、末尾カンマを付ける習慣がより重要になる。

### 空のメソッド本体 `{}`

```php
public function __construct(
    private string $name,
    private int $age,
) {
}
```

または

```php
public function __construct(
    private string $name,
    private int $age,
) {}
```

**コンストラクタの中で何もする必要がない**ので、本体を空にする。改行するか1行にまとめるかは好み。Laravel では1行 `{}` も改行 `{\n}` も両方見かける。

## 別解

### コンストラクタ内で追加処理を行う

```php
class User
{
    public function __construct(
        private string $name,
        private int $age,
    ) {
        if ($age < 0) {
            throw new InvalidArgumentException('Age must be non-negative.');
        }
    }
}
```

プロモーションでも、コンストラクタの中に追加の処理(バリデーション、ログ、初期化など)は書ける。**プロモーションは「プロパティ宣言と代入を省略する」だけ**で、それ以外の処理は普通に書ける。

### 一部だけプロモーション、一部は従来式

```php
class User
{
    private string $email;   // 従来式

    public function __construct(
        private string $name,    // プロモーション
        private int $age,        // プロモーション
        string $email,           // 普通の引数
    ) {
        $this->email = strtolower($email);   // 加工してから代入
    }
}
```

プロモーションと従来式は混在可能。**「引数を加工してからプロパティに代入したい」場合は従来式**が向いている。

### `public` プロパティとして公開する場合

```php
class Point
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
    ) {}
}

$p = new Point(3, 4);
echo $p->x;   // 3
echo $p->y;   // 4
```

座標、色、寸法などの**値オブジェクト**では、`public readonly` で外部からの読み取りを許可しつつ、変更は禁止するパターンが定石。

## つまずきやすい点

### 1. 修飾子を付け忘れる

```php
public function __construct(
    string $name,         // ❌ プロモーションされない(ただの引数)
    int $age,
) {}

// インスタンス化はできるが…
$user = new User('Alice', 25);
echo $user->name;         // ❌ Property does not exist
```

**修飾子(`private` / `protected` / `public`)を付けないとプロパティにならない**。引数として受け取るだけで終わってしまう。

### 2. 従来の書き方と混乱して、プロパティを二重定義

```php
class User
{
    private string $name;   // ❌ プロパティを宣言

    public function __construct(
        private string $name,   // ❌ プロモーションでも宣言 → 重複エラー
    ) {}
}
```

プロモーションを使うなら、**プロパティ宣言は書かない**。書いてしまうと「プロパティが2回定義されている」エラーになる。

### 3. プロモーションされた引数を `$this->` なしで参照しようとする

```php
class User
{
    public function __construct(
        private string $name,
    ) {}

    public function getName(): string
    {
        return $name;          // ❌ ローカル変数 $name は存在しない
        return $this->name;    // ⭕ プロパティとしてアクセス
    }
}
```

**プロモーションされた引数は、コンストラクタの内側でしか「引数」として存在しない**。他のメソッドから使う時は、必ず `$this->name` の形でプロパティとしてアクセスする。

### 4. PHP 7.x では使えない

コンストラクタプロモーションは **PHP 8.0 以降の機能**。PHP 7.x のプロジェクトでは使えないので注意。Maru さんの環境は PHP 8.4 系なので問題なく使える。

### 5. 引数の順序とプロパティアクセス順は無関係

```php
public function __construct(
    private string $name,
    private int $age,
) {}

$user = new User('Alice', 25);   // 引数の順序通りに渡す
echo $user->age;                  // ⭕ いつでもアクセス可
echo $user->name;                 // ⭕ 順序は関係ない
```

「先に書かれたプロパティから順番に」のような制約はない。コンストラクタが完了した時点で、すべてのプロパティが使える。

## Java との対比

```java
// Java (record、Java 14+)
public record User(String name, int age) {
    public String introduce() {
        return "私の名前は" + name + "、年齢は" + age + "歳です。";
    }
}

User user = new User("Alice", 25);
System.out.println(user.introduce());
```

```java
// Java(record以前)
public class User {
    private final String name;
    private final int age;
    
    public User(String name, int age) {
        this.name = name;
        this.age = age;
    }
    
    public String introduce() {
        return "私の名前は" + name + "、年齢は" + age + "歳です。";
    }
}
```

```php
// PHP 8 コンストラクタプロモーション
class User
{
    public function __construct(
        private string $name,
        private int $age,
    ) {}

    public function introduce(): string
    {
        return "私の名前は{$this->name}、年齢は{$this->age}歳です。";
    }
}

$user = new User('Alice', 25);
echo $user->introduce() . PHP_EOL;
```

| 観点 | Java(record) | Java(従来) | PHP 8(プロモーション) |
|------|--------------|------------|---------------------|
| プロパティ宣言 | 不要(自動生成) | 必要(`private final`) | 不要(コンストラクタ引数で兼ねる) |
| コンストラクタ | 不要(自動生成) | 必要 | 必要(プロモーションで代入処理は省略) |
| イミュータブル | 標準で final | `final` を付ける | `readonly` を付ける(PHP 8.1+) |
| getter | 自動生成(`name()`、`age()`) | 手動定義 | 手動定義(`getName()` など) |

**コンストラクタプロモーションは Java の `record` に近い概念**。「プロパティとコンストラクタを同時に宣言する」という発想が共通している。ただし record ほど自動化されていない:
- record は getter まで自動生成するが、PHP のプロモーションは getter は手動
- record はクラス全体がイミュータブルになるが、PHP は `readonly` を明示的に付ける必要がある

それでも、**「同じことを書く量がほぼ半分になる」**という点で、コンストラクタプロモーションは現代 PHP の最も重要な機能の1つ。

## 実務での使い分け

| 場面 | 推奨スタイル |
|------|------------|
| シンプルな値の保持(ほとんどのケース) | コンストラクタプロモーション |
| イミュータブルなデータクラス(値オブジェクト、DTO) | プロモーション + readonly |
| 引数を加工してから代入する必要がある | 従来式(または混在) |
| PHP 7.x プロジェクト | 従来式(プロモーション使用不可) |

**Laravel(PHP 8 以降)のソースコード**では、コンストラクタの90%以上がプロモーション形式。新しく書くクラスは原則プロモーションを使う、という方針で問題ない。

「**シンプル・短い・読みやすい**」を実現するための機能なので、積極的に使っていこう。