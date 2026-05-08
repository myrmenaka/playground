# S08 解説: Cat クラス

## ポイント

### 1. S07 と構造はほぼ同じ

`Cat` も `Animal` を継承して `speak()` をオーバーライドするだけ。
重要なのは、**継承元の `Animal` を変えていないのに、Dog も Cat もそれぞれ独自の振る舞いを持てる**こと。

```php
class Animal { /* 共通の枠組み */ }
class Dog extends Animal { /* 犬らしい speak() */ }
class Cat extends Animal { /* 猫らしい speak() */ }
```

これが S09 でやるポリモーフィズムの土台になります。

### 2. オーバーライドのシグネチャを揃える

```php
// 親
public function speak(): string { ... }

// 子(Cat)
public function speak(): string { ... }   // ✅ 一致
```

メソッド名・引数・戻り値の型をすべて一致させる。
**1文字でも違うと、オーバーライドではなく「別の新しいメソッド」になってしまう**点に注意。

PHP には Java の `@Override` のような明示マークがないので、IDE(PHP Intelephense)の警告に頼るのが実務的です。

## つまずきやすい点

### 親と子でメソッド名が一致していないとオーバーライドにならない

たとえば、こう書いてしまったとします:

```php
class Animal {
    public function getName(): string { ... }   // ← speak のつもりが getName
}

class Cat extends Animal {
    public function speak(): string { ... }     // ← 別の新しいメソッド扱い
}
```

この場合、`Cat` は **2つのメソッドを持つ別物** になります:
- `getName()`(親から継承)
- `speak()`(子で追加)

「オーバーライドしたつもりが、新しいメソッドが増えただけ」という典型的な罠です。
シグネチャの一致を意識する習慣が大事。

### 具体的な確認方法

意図通りオーバーライドできているか確かめたい時は、親型で受けて呼び出してみるのが手っ取り早いです:

```php
$animal = new Cat('タマ');     // Cat だが Animal 型として扱う
echo $animal->speak();         // "タマ: ニャー" が出ればオーバーライド成立
```

これが S09 でやるポリモーフィズムそのものです。

## Java との対比

```java
// Java(IDEが「オーバーライドの書き忘れ」を警告してくれる)
public class Cat extends Animal {
    @Override
    public String speak() {  // @Override があると、親に speak が無いとエラー
        return name + ": ニャー";
    }
}
```

```php
// PHP(IDE頼み)
class Cat extends Animal {
    public function speak(): string {
        return "{$this->name}: ニャー";
    }
}
```

PHP は柔軟な分、コードに目を光らせる責任が開発者側に多めにあります。