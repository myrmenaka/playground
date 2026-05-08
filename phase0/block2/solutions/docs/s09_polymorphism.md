# S09 解説: ポリモーフィズムを体験する

## ポイント

### 1. ポリモーフィズム(多態性)とは

> **同じインターフェース(同じメソッド名)で呼び出しても、実際の振る舞いがオブジェクトごとに変わる仕組み**

```php
foreach ($animals as $animal) {
    $animal->speak();  // ← 呼び出し方は1つ。でも振る舞いはオブジェクトごとに違う
}
```

呼び出し側のコードは「`Animal` が `speak()` を持っている」ことしか知らない。
実際にどの `speak()` が動くかは、**実行時にオブジェクトの実際の型に応じて自動で決まる**。

これを **動的ディスパッチ** と呼びます(Java と同じ仕組み)。

### 2. メリット: 変更の局所化

新しい動物クラスを追加した時、**呼び出し側のコードを変える必要がない**。

```php
// あとから追加しても…
class Bird extends Animal {
    public function speak(): string { return "{$this->name}: ピヨピヨ"; }
}

$animals[] = new Bird('ピーちゃん');

// このループは1文字も変えなくていい
foreach ($animals as $animal) {
    echo $animal->speak() . PHP_EOL;
}
```

これは「**開放閉鎖原則(Open-Closed Principle, OCP)**」の体現です:

- **拡張に対して開いている**: 新しいクラスを追加できる
- **修正に対して閉じている**: 既存のコードを変更しなくていい

SOLID 原則の「O」にあたる重要な原則で、Phase 4 の設計編で詳しく扱います。今は「あー、こういう書き方だと後から楽だな」と感じられれば十分です。

### 3. もしポリモーフィズムを使わなかったら

```php
// 悪い例: 型ごとに分岐
foreach ($animals as $animal) {
    if ($animal instanceof Dog) {
        echo $animal->bark();
    } elseif ($animal instanceof Cat) {
        echo $animal->meow();
    }
    // 新しい動物を追加するたびに、ここに elseif を増やす必要がある
}
```

このようなコードを「**型分岐(タイプスイッチ)アンチパターン**」と呼びます。
クラスが増えるたびに `if` が増え、修正の影響範囲が広がります。

ポリモーフィズムは、こうした分岐を **OOP の機構そのものに任せる** ためのテクニックです。

## Java との対比

```java
// Java
List<Animal> animals = List.of(
    new Dog("ポチ"),
    new Cat("タマ")
);

for (Animal animal : animals) {
    System.out.println(animal.speak());  // 動的ディスパッチで Dog/Cat の speak() が呼ばれる
}
```

```php
// PHP
$animals = [new Dog('ポチ'), new Cat('タマ')];

foreach ($animals as $animal) {
    echo $animal->speak() . PHP_EOL;
}
```

仕組みも考え方もほぼ同じ。違いは:

| 項目 | Java | PHP |
|------|------|-----|
| 配列の型 | `List<Animal>` で要素型を制約 | 制約なし(PHPDoc で表現可) |
| 動的ディスパッチ | あり(同じ仕組み) | あり(同じ仕組み) |

PHP は配列の型が緩い分、`Dog` と `Cat` 以外のものを混ぜても実行時まで気づかないことがあります。これは PHP の弱点でもあり、実務では PHPDoc や PHPStan(静的解析ツール)で補強します。

## 設計のヒント: 親型で受ける

ポリモーフィズムを活かすには、関数の引数も「親型」で受けると良いです。

```php
// 良い例: Animal で受ける
function makeNoise(Animal $animal): void {
    echo $animal->speak() . PHP_EOL;
}

makeNoise(new Dog('ポチ'));   // OK
makeNoise(new Cat('タマ'));   // OK
makeNoise(new Bird('ピー'));  // OK(後から Bird を追加しても動く)
```

```php
// 悪い例: 子型で固定
function makeDogNoise(Dog $dog): void { ... }
function makeCatNoise(Cat $cat): void { ... }
// → クラスが増えるたびに関数が増える
```

「**呼び出し側は親型で受ける、実装側は子クラスで個別の振る舞いを書く**」が基本パターン。
このパターンが極まったものが、次の S11〜 で扱う **インターフェース** です。

## つまずきやすい点

### 「PHPは型がないから何でも入る」のメリットとデメリット

PHP の配列には Dog でも Cat でも、なんなら `string` も `int` も混ぜて入れられます。

```php
$animals = [new Dog('ポチ'), new Cat('タマ'), 'これは文字列', 42];
foreach ($animals as $animal) {
    echo $animal->speak() . PHP_EOL;  // ← 文字列や int で実行時エラー
}
```

メリット: 柔軟、書きやすい
デメリット: 実行時まで型のミスに気づかない

実務では:
- 配列の中身を PHPDoc で示す: `/** @var Animal[] $animals */`
- PHPStan / Psalm で静的型チェック
- Laravel なら `Collection` 型を使う

これらを組み合わせて、PHP の柔軟さと型の安全性を両立させます(Phase 2〜4 で順次扱います)。