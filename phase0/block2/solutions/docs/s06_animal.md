# S06 解説: Animal クラス

## ポイント

### 1. なぜ `protected` なのか

| 可視性 | 自クラス | 子クラス | 外部 |
|--------|---------|---------|------|
| `public` | ✅ | ✅ | ✅ |
| `protected` | ✅ | ✅ | ❌ |
| `private` | ✅ | ❌ | ❌ |

次の S07 で `Dog extends Animal` を作り、`speak()` をオーバーライドする時に「子クラスから `$this->name` を参照したい」という場面が出てきます。
`private` だと子クラスから直接アクセスできないので、`protected` にしておくのが定石です。

ただし「無条件に protected にすればよい」というわけではなく、

- 子クラスからアクセスする必要があるなら `protected`
- そうでなければ `private`

が原則です。「迷ったら private、必要になったら protected に上げる」と考えると安全です。

### 2. コンストラクタプロモーションと可視性

```php
public function __construct(
    protected string $name,
) {}
```

プロモーションでは、引数に `public` / `protected` / `private` を書くことで、その引数が自動的にプロパティとして宣言されます。
従来の書き方なら以下と等価です:

```php
class Animal
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
```

## Java との対比

```java
// Java
public class Animal {
    protected String name;

    public Animal(String name) {
        this.name = name;
    }

    public String speak() {
        return this.name + "が鳴きます";
    }
}
```

```php
// PHP
class Animal
{
    public function __construct(
        protected string $name,
    ) {}

    public function speak(): string
    {
        return "{$this->name}が鳴きます";
    }
}
```

| 項目 | Java | PHP |
|------|------|-----|
| 可視性 `protected` | 同じ意味 | 同じ意味 |
| プロパティ宣言 | クラス直下に書く | プロモーションで引数に書ける |
| 文字列連結 | `+` | `.` または `"{$var}"` |

## つまずきやすい点

### `protected` と `private` の使い分け

最初は「全部 protected にしておけば安全」と思いがちですが、**過剰に protected にすると、子クラスとの結合度が上がります**。

- 親クラスのプロパティを変えたい時、子クラスも壊れる可能性がある
- 「どこから参照されているか」が広くなり、把握が難しくなる

OOP の原則「カプセル化」に従い、**必要最小限の可視性** にするのが良い設計です。

### 次の Step(S07)へのつなぎ

`speak()` を「動物が鳴きます」のような汎用的な実装にしておくと、子クラスでオーバーライドした時の差分が分かりやすくなります。
- `Animal::speak()` → 「動物が鳴きます」
- `Dog::speak()` → 「ワンワン!」(オーバーライド)
- `Cat::speak()` → 「ニャー」(オーバーライド)