# S07 解説: Dog クラス(継承とオーバーライド)

## ポイント

### 1. 継承の構文

```php
class Dog extends Animal { ... }
```

Java の `class Dog extends Animal` と完全に同じ構文・同じ意味です。
PHP は **単一継承** のみ(複数の親は持てない)で、これも Java と同じ。

### 2. オーバーライドのルール

子クラスで親と「同じ名前・同じシグネチャ」のメソッドを定義すると、自動的に上書きされます。

```php
// 親
public function speak(): string { ... }

// 子(オーバーライド)
public function speak(): string { ... }   // ✅ シグネチャ一致
```

PHP では Java の `@Override` アノテーションのような明示マークはありません。
ただし、戻り値の型を変えると(共変戻り値の例外を除き)エラーになります。

### 3. コンストラクタを書かない選択

```php
class Dog extends Animal
{
    // コンストラクタを書かない → 親の __construct がそのまま使われる
}

$dog = new Dog('ポチ');  // Animal::__construct が呼ばれる
```

子クラスでコンストラクタを書かなければ、親のコンストラクタがそのまま継承されます。
今回のように「子クラスで追加のプロパティが不要」なら、書かないのが正解。

S10 で「子クラス独自のプロパティを追加するために `parent::__construct` を呼ぶ」パターンを扱います。

### 4. 子クラスから親のプロパティへのアクセス

```php
class Dog extends Animal
{
    public function speak(): string
    {
        return "{$this->name}: ワンワン!";  // ← 親の protected $name にアクセス
    }
}
```

S06 で `protected` にしたから、ここで `$this->name` が使えます。
もし `private` にしていたら、ここでエラーになっていました。
**「protected にする理由」が体感できたはず**です。

## Java との対比

```java
// Java
public class Animal {
    protected String name;
    public Animal(String name) { this.name = name; }
    public String speak() { return this.name + "が鳴きます"; }
}

public class Dog extends Animal {
    public Dog(String name) { super(name); }

    @Override
    public String speak() { return this.name + ": ワンワン!"; }
}
```

```php
// PHP
class Animal {
    public function __construct(protected string $name) {}
    public function speak(): string { return "{$this->name}が鳴きます"; }
}

class Dog extends Animal {
    public function speak(): string { return "{$this->name}: ワンワン!"; }
}
```

| 項目 | Java | PHP |
|------|------|-----|
| 継承 | `extends` | `extends` (同じ) |
| オーバーライドの明示 | `@Override`(任意) | なし(同名で自動) |
| 親コンストラクタ呼び出し | `super(name)` | `parent::__construct($name)` |
| コンストラクタを書かない | 暗黙のデフォルトコンストラクタが必要 | 親のコンストラクタがそのまま継承される |

PHP のほうが少しゆるい(書かなくても動く)分、慣れていないと「親のコンストラクタはどう呼ばれている?」が見えにくい面があります。

## つまずきやすい点

### オーバーライドのつもりが、新しいメソッドになっている

```php
// 親
public function speak(): string { ... }

// 子(タイポ)
public function spek(): string { ... }   // ❌ 別のメソッド扱い
```

PHP には Java の `@Override` のような「親に同名メソッドが存在することを保証する」仕組みがありません。
タイポしてもエラーにならず、新しいメソッドとして追加されてしまいます。

実務では IDE(PHP Intelephense や PhpStorm)が「親に同名メソッドあり」を表示してくれるので、それで確認するのが普通です。

### 戻り値の型を変えるとエラー

```php
class Animal {
    public function speak(): string { ... }
}

class Dog extends Animal {
    public function speak(): int { ... }  // ❌ Fatal error
}
```

LSP(リスコフの置換原則)違反になるため、PHP もエラーで止めてくれます。