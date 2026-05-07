# S01 解説: User クラスの定義とインスタンス化

## ポイント

### クラス定義の基本構文

```php
class クラス名
{
    // プロパティ
    private 型 $プロパティ名;
    
    // コンストラクタ
    public function __construct(引数...) { ... }
    
    // メソッド
    public function メソッド名(引数...): 戻り値の型 { ... }
}
```

Java とほぼ同じ構造。違いは以下の3点。

| 観点 | Java | PHP |
|------|------|-----|
| コンストラクタ名 | クラス名と同じ(`User(...)`) | `__construct` 固定 |
| プロパティアクセス | `this.name` | `$this->name` |
| インスタンス化 | `new User("Alice", 25)` | `new User('Alice', 25)`(同じ) |

### `__construct` の意味

PHPのコンストラクタは**`__construct` という名前で固定**。Javaのように「クラス名と同じ名前のメソッド」ではない。これにより、クラス名を変えてもコンストラクタの修正が不要というメリットがある。

`__` で始まるメソッドは PHP の **マジックメソッド** と呼ばれる特殊なメソッド。他にも `__toString`、`__get`、`__set` などがある(後の Step で扱う可能性あり)。

### プロパティの可視性

```php
private string $name;    // クラス内からのみアクセス可
protected string $name;  // クラス内 + 子クラスからアクセス可
public string $name;     // どこからでもアクセス可
```

Java と全く同じ。**デフォルトで `private` を付ける**のが現代の流儀(カプセル化の原則)。

### `$this->` でメンバーアクセス

```php
$this->name        // ⭕ プロパティアクセス
$this->introduce() // ⭕ メソッド呼び出し
this->name         // ❌ $ が必要
$this.name         // ❌ . はPHPでは文字列連結の演算子
```

Java の `this.name` ではなく、**`$this->name`** と書く。`.` は PHP では文字列連結なので、ここで使うとエラーになる。

### 文字列内での `{$this->...}` 展開

```php
return "私の名前は{$this->name}、年齢は{$this->age}歳です。";
```

ダブルクォート文字列の中で `$this->name` のようなプロパティアクセスを展開する場合は、**`{}` で囲む必要がある**。`{}` がないと、PHP は `$this` だけを変数として認識し、`->name` を文字列リテラルとして解釈してしまう。

```php
"$this->name"     // △ 動くこともあるが、複雑なケースで挙動が不安定
"{$this->name}"   // ⭕ 確実に展開される(推奨)
```

### `new` でインスタンス化

```php
$user = new User('Alice', 25);
```

Java と全く同じ感覚。`new クラス名(引数...)` でインスタンスを生成し、変数に入れる。

## 別解

### コンストラクタプロモーション(PHP 8.0+)

```php
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
```

**PHP 8.0で導入された画期的な書き方**。コンストラクタの引数に可視性修飾子を付けると、自動的に同名のプロパティが定義され、引数の値がセットされる。プロパティ宣言と代入処理が不要になる。

S03 で詳しく扱うので、今は「こういう書き方もある」と知っておく程度でOK。Laravel のソースコードでは、これが圧倒的に多用されている。

### `readonly` プロパティ(PHP 8.1+)

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

### sprintf を使った書き方

```php
public function introduce(): string
{
    return sprintf('私の名前は%s、年齢は%d歳です。', $this->name, $this->age);
}
```

書式指定子(`%s` = 文字列、`%d` = 整数)を使う書き方。複雑な書式や、多言語対応で文字列を外部ファイル化する場合に便利。

## つまずきやすい点

### 1. `$this` の `$` を忘れる

```php
$this->name      // ⭕
this->name       // ❌ Parse error
```

`$this` は変数なので **`$` 必須**。Java の感覚で書くと忘れがち。

### 2. プロパティアクセスに `.` を使う

```php
$this->name      // ⭕
$this.name       // ❌ "1name" のような文字列連結になってしまう
```

`->` がプロパティ・メソッドアクセス、`.` は文字列連結。Java とは記号の使い方が全く違う。

### 3. プロパティ宣言で `$` を忘れる

```php
private string $name;    // ⭕ プロパティ名にも $ が必要
private string name;     // ❌ Parse error
```

PHP の変数(プロパティを含む)は**全て `$` 始まり**。例外なし。

### 4. コンストラクタ名を `User` にしてしまう

```php
public function User(string $name, int $age) { ... }   // ❌ ただの普通のメソッド扱い
public function __construct(string $name, int $age) { ... }   // ⭕
```

Java の感覚で「クラス名と同じメソッドはコンストラクタ」と思って書くと、**ただのメソッドとして定義され、`new` 時には呼ばれない**ので注意。

PHP 4 時代はクラス名と同じメソッドがコンストラクタとして扱われていたが、PHP 7 で廃止予定、PHP 8 で完全廃止された。

### 5. ダブルクォート内のプロパティ展開で `{}` を忘れる

```php
"私の名前は$this->name"        // △ 動くが推奨されない
"私の名前は{$this->name}"      // ⭕ 推奨
```

`{}` を付けるのが現代の慣習。チェーンが深くなった時(`$this->user->profile->name` など)は `{}` が必須になる。

### 6. プロパティの型宣言を書かない

```php
private $name;          // △ 動くが型が不明
private string $name;   // ⭕ 推奨(PHP 7.4+)
```

PHP 7.4 でプロパティの型宣言がサポートされた。**現代の PHP コードでは必ず型を付ける**のが標準。Laravel のソースコードもすべて型付き。

## Java との対比

```java
// Java
public class User {
    private String name;
    private int age;
    
    public User(String name, int age) {   // クラス名と同じ
        this.name = name;
        this.age = age;
    }
    
    public String introduce() {
        return "私の名前は" + this.name + "、年齢は" + this.age + "歳です。";
    }
}

User user = new User("Alice", 25);
System.out.println(user.introduce());
```

```php
// PHP
class User
{
    private string $name;
    private int $age;

    public function __construct(string $name, int $age)   // 名前固定
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function introduce(): string
    {
        return "私の名前は{$this->name}、年齢は{$this->age}歳です。";
    }
}

$user = new User('Alice', 25);
echo $user->introduce() . PHP_EOL;
```

| 観点 | Java | PHP |
|------|------|-----|
| クラス定義 | `public class User` | `class User`(`public` 不要) |
| コンストラクタ名 | クラス名 | `__construct` |
| プロパティ宣言 | `private String name;` | `private string $name;` |
| プロパティアクセス | `this.name` | `$this->name` |
| メソッド定義 | `public String introduce()` | `public function introduce(): string` |
| インスタンス化 | `new User("Alice", 25)` | `new User('Alice', 25)` |

**ロジック構造は完全に同じ**。記号の使い方(`->` と `.`、`$` の有無)が違うだけ。**Java の OOP の知識はそのまま PHP に転用できる**。

ただし PHP 8 以降は、Java にはない便利な機能(コンストラクタプロモーション、readonly、Enum など)が追加されているので、それらを使いこなせると Java よりも簡潔に書ける場面が増える。Block 2 ではこれらの新機能も順に体験していく。

実務での重要ポイント:
- **新規クラスは必ず型宣言を付ける**(プロパティ、引数、戻り値すべて)
- **デフォルトで `private` を付ける**(必要に応じて `protected`、`public` を選ぶ)
- **PHP 8 以降ではコンストラクタプロモーションを優先**(S03 で詳しく)

これらは Laravel の最新版や、PHPStan などの静的解析ツールでも推奨されている。