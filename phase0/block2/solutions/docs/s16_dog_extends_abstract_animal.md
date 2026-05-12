# S16 解説: Dog クラスで AbstractAnimal を継承する

## ポイント

### 1. `extends` キーワード

```php
class Dog extends AbstractAnimal
```

Java と全く同じ書き方。継承元は1つだけ(単一継承)。

### 2. コンストラクタを省略できる

`Dog` 独自のプロパティがないので、コンストラクタを書く必要がない。
書かなければ親クラス `AbstractAnimal` のコンストラクタが自動的に使われる。

```php
$dog = new Dog('ポチ');
// → AbstractAnimal::__construct('ポチ') が呼ばれて name がセットされる
```

これは Java も同じ挙動。

### 3. 抽象メソッドの実装

抽象メソッドだった `speak()` を、子クラスで本体ありで書き直す。
ポイントは:
- `abstract` キーワードは **付けない**(もう抽象ではない)
- シグネチャは親と **完全一致** させる(`speak(): string`)

```php
public function speak(): string
{
    return "ワン!";
}
```

### 4. 親の具体メソッドが子クラスから使える

`Dog` クラスには `introduce()` を書いていないのに、`$dog->introduce()` を呼べる。
これが継承の力。親で実装されたメソッドは、子クラスでもそのまま使える。

しかも `introduce()` の中の `$this->speak()` は、**実行時に `Dog::speak()` が呼ばれる**。
これがポリモーフィズム。

```
introduce() の内部
    ↓
"私は {$this->name} です。" + $this->speak() の結果
    ↓
$this の実体は Dog なので、Dog::speak() が呼ばれる
    ↓
"ワン!" が返ってくる
    ↓
最終結果: "私は ポチ です。ワン!"
```

## Java との対比

Java の継承とほぼ同じ。

```java
// Java
public class Dog extends AbstractAnimal {
    public Dog(String name) {
        super(name);
    }

    @Override
    public String speak() {
        return "ワン!";
    }
}

Dog dog = new Dog("ポチ");
System.out.println(dog.speak());
System.out.println(dog.introduce());
```

```php
// PHP
class Dog extends AbstractAnimal
{
    public function speak(): string
    {
        return "ワン!";
    }
}

$dog = new Dog('ポチ');
echo $dog->speak() . PHP_EOL;
echo $dog->introduce() . PHP_EOL;
```

| 観点 | Java | PHP |
|------|------|-----|
| 継承キーワード | `extends` | `extends`(同じ) |
| 単一継承 | はい | はい |
| 親コンストラクタ呼び出し | `super(name);` | `parent::__construct($name);`(S10 で扱う予定) |
| オーバーライド宣言 | `@Override` | なし(`#[\Override]` 属性は PHP 8.3+ で使える) |
| 子のコンストラクタ省略時 | 親のデフォルトコンストラクタ呼び出し | 親のコンストラクタがそのまま使われる |

## つまずきやすい点

### A. 子クラスで `abstract` を付けたままにする

```php
// ✕ エラー
class Dog extends AbstractAnimal
{
    abstract public function speak(): string  // abstract を残してしまう
    {
        return "ワン!";
    }
}
```

エラー:
```
Fatal error: Abstract function Dog::speak() cannot contain body
```

`abstract` を付けたら本体を書けない、本体を書くなら `abstract` を外す。

### B. シグネチャが親と一致していない

```php
// ✕ エラー: 引数の型を変える
public function speak(int $volume): string  // 親は引数なし
{
    return "ワン!";
}

// ✕ エラー: 戻り値の型を変える
public function speak(): int  // 親は string
{
    return 1;
}
```

シグネチャは親と完全一致が原則。
ただし「より具体的な戻り値型」は OK(共変戻り値、PHP 7.4+)。

### C. 親のコンストラクタを忘れて独自のコンストラクタを書く

`Dog` でコンストラクタを追加したくなった時、親の処理を呼び忘れがち:

```php
class Dog extends AbstractAnimal
{
    public function __construct(
        string $name,
        private int $age,  // Dog 独自のプロパティ
    ) {
        // ✕ parent::__construct($name) を忘れている
        // → $this->name が初期化されない
    }
}
```

正しくは:

```php
class Dog extends AbstractAnimal
{
    public function __construct(
        string $name,
        private int $age,
    ) {
        parent::__construct($name);  // ◯ 親のコンストラクタを呼ぶ
    }
}
```

これは S10 で扱う `parent::` の話なので、今は「そういうものがある」と知っておけばOK。

## Intelephense が誤検知する場合

S16 を書いた時に Intelephense から以下のような警告が出ることがある:

```
Not enough arguments. Expected 2. Found 1.intelephense(P1005)
```

これは Intelephense がワークスペース内の **別ファイル** にある同名クラス(S15 の `AbstractAnimal` など)と混同しているために起きる誤検知。
`php` コマンドで実行すれば正常に動くので、無視してOK。

将来 Composer のオートロードと PSR-4 を使うようになれば、各クラスは1ファイル1クラスで配置し、名前空間で区別するため、この問題は起きなくなる(Phase 0 Block 4 で扱う)。