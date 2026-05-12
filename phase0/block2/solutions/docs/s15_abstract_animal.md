# S15 解説: AbstractAnimal 抽象クラスの定義

## ポイント

### 1. `abstract class` キーワード

通常のクラス宣言の前に `abstract` を付けるだけ。
これだけで「直接インスタンス化できないクラス」になる。

```php
abstract class AbstractAnimal
{
    // ...
}
```

### 2. 抽象メソッドの書き方

メソッドにも `abstract` を付けると、本体を書かずに **宣言だけ** できる。
インターフェースのメソッド宣言と全く同じ書き方:

```php
abstract public function speak(): string;
```

- `abstract` を付けたメソッドは、本体 `{ ... }` を書かない
- セミコロン `;` で終わる
- 子クラスで必ず実装する必要がある

### 3. 具体メソッドとの混在

抽象クラスは **抽象メソッドと具体メソッドを混在できる** のが特徴:

```php
// 抽象メソッド: 子クラスに任せる
abstract public function speak(): string;

// 具体メソッド: 親で実装、子クラスはそのまま使える
public function introduce(): string
{
    return "私は {$this->name} です。{$this->speak()}";
}
```

`introduce()` は親で実装しているが、その中で `$this->speak()` を呼んでいる。
`speak()` の中身は子クラスで決まるので、**親の `introduce()` の挙動は子クラスごとに変わる**。
これがテンプレートメソッドパターンの原型。

### 4. プロパティのアクセス修飾子: `protected`

```php
public function __construct(
    protected string $name,
) {}
```

`private` ではなく `protected` にしているのがポイント。
- `private` → 子クラスからアクセスできない
- `protected` → 子クラスからアクセスできる
- `public` → どこからでもアクセスできる

抽象クラスのプロパティは、子クラスでも使う可能性が高いので `protected` にすることが多い。

## Java との対比

Java の抽象クラスとほぼ同じ。

```java
// Java
public abstract class AbstractAnimal {
    protected final String name;

    public AbstractAnimal(String name) {
        this.name = name;
    }

    public abstract String speak();

    public String introduce() {
        return "私は " + name + " です。" + speak();
    }
}
```

```php
// PHP
abstract class AbstractAnimal
{
    public function __construct(
        protected string $name,
    ) {}

    abstract public function speak(): string;

    public function introduce(): string
    {
        return "私は {$this->name} です。{$this->speak()}";
    }
}
```

| 観点 | Java | PHP |
|------|------|-----|
| キーワード | `abstract class` | `abstract class`(同じ) |
| 抽象メソッド | `public abstract String speak();` | `abstract public function speak(): string;` |
| 修飾子の順 | `public abstract` | `abstract public`(PHPは `abstract` が先) |
| プロパティ可視性 | `protected` | `protected`(同じ) |
| インスタンス化 | 不可 | 不可(同じ) |

## 抽象クラス vs インターフェース(S17 で詳しくメモを書く予定)

ここで一旦、ざっくり違いを掴んでおく:

| 観点 | インターフェース | 抽象クラス |
|------|----------------|----------|
| 実装を持てるか | 持てない(宣言のみ) | 持てる(混在可) |
| プロパティを持てるか | 持てない(定数のみ) | 持てる |
| コンストラクタ | 持てない | 持てる |
| 多重実装 | できる(`implements A, B`) | できない(`extends` は1つだけ) |
| いつ使う | 「○○できる」という能力 | 「○○である」という分類 |

ざっくり言うと:
- 「**この能力を持っている**」を示したい → インターフェース(`Notifiable`「通知できる」)
- 「**これの一種である**」と共通実装も持たせたい → 抽象クラス(`AbstractAnimal`「動物の一種」)

## つまずきやすい点

### A. `abstract` の位置

```php
// ◯ PHP の正しい順番
abstract public function speak(): string;

// ✕ Java の感覚で書くとエラー
public abstract function speak(): string;
```

実は PHP 8 では両方の順序が許容されるが、**`abstract` を先に書く** のが伝統的かつ推奨される書き方。

### B. 抽象メソッドに本体を書いてしまう

```php
// ✕ エラー
abstract public function speak(): string
{
    return "...";
}
```

エラーメッセージ:
```
Fatal error: Abstract function AbstractAnimal::speak() cannot contain body
```

S12 のインターフェースで本体を書いた時と同じエラー。

### C. 抽象メソッドのない抽象クラスもアリ

「抽象クラス = 抽象メソッドが必要」と思いがちだが、実は **抽象メソッドが1つもなくても、`abstract class` と宣言すれば抽象クラス**。
こうすると「インスタンス化はできないが、継承して使ってね」というクラスになる。
基底クラスのテンプレートとして使うパターン。

### D. 子クラスのプロパティ可視性を狭めようとする

```php
abstract class AbstractAnimal
{
    public function __construct(
        protected string $name,
    ) {}
}

class Dog extends AbstractAnimal
{
    // 子クラスで private にしようとするとエラー
}
```

これは S16 でやらないので今は気にしなくてOK。「子クラスでプロパティ可視性を狭められない」とだけ覚えておけば十分。

## このStep単体では何も出力されない理由

抽象クラスは直接インスタンス化できないので、このファイルだけでは動かしようがない。
S16 で `Dog extends AbstractAnimal` を作って、ようやく動かせるようになる。