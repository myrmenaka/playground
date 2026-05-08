# S10 解説: parent::__construct() で親のコンストラクタを呼ぶ

## ポイント

### 1. 子クラスでコンストラクタを書く理由

子クラスで **独自のプロパティを追加したい** ときに、子クラスのコンストラクタを書きます。

```php
class Dog extends Animal
{
    public function __construct(
        string $name,
        protected string $breed,  // ← 子クラス独自のプロパティ
    ) {
        parent::__construct($name);
    }
}
```

`Animal` には `$name` しかないけど、`Dog` には `$breed`(犬種)も持たせたい。こういう時に子クラスでコンストラクタを書きます。

### 2. `parent::__construct()` の役割

```php
public function __construct(
    string $name,                 // 親に渡すための引数
    protected string $breed,      // 子クラスのプロパティ
) {
    parent::__construct($name);   // ← 親の __construct を明示的に呼ぶ
    // → 親の $this->name が初期化される
    
    // この時点で $this->breed もプロモーションで自動的に初期化されている
}
```

PHP は親のコンストラクタを **暗黙的には呼ばない** ので、明示的に書く必要があります(Java は暗黙の `super()` あり)。

### 3. 引数の構造

```php
public function __construct(
    string $name,                 // ← プロモーションなし(親に渡すだけ)
    protected string $breed,      // ← プロモーションあり(子のプロパティになる)
)
```

`$name` には可視性(`public`/`protected`/`private`)を **書かない**:
- 書くと子クラスにも `$name` プロパティが作られて、親の `$name` と衝突する
- ここでは「親に渡すための引数」として受け取るだけ

`$breed` には `protected` を書く:
- これでプロモーションが効いて、自動的に子クラスのプロパティとして宣言・初期化される

## Java との対比

```java
// Java
public class Animal {
    protected String name;
    public Animal(String name) { this.name = name; }
}

public class Dog extends Animal {
    protected String breed;
    public Dog(String name, String breed) {
        super(name);            // ← 親のコンストラクタを呼ぶ
        this.breed = breed;
    }
}
```

```php
// PHP
class Animal {
    public function __construct(protected string $name) {}
}

class Dog extends Animal {
    public function __construct(
        string $name,
        protected string $breed,
    ) {
        parent::__construct($name);  // ← 親のコンストラクタを呼ぶ
    }
}
```

| 項目 | Java | PHP |
|------|------|-----|
| 親コンストラクタの自動呼び出し | ある(暗黙の `super()`) | なし(明示が必要) |
| 構文 | `super(name)` | `parent::__construct($name)` |
| 呼び出し位置の制約 | コンストラクタの先頭のみ | どこでも可(慣習的に先頭) |

## つまずきやすい点

### `parent::__construct()` を忘れる

```php
class Dog extends Animal {
    public function __construct(
        string $name,
        protected string $breed,
    ) {
        // parent::__construct($name); を書き忘れた
    }
}

$dog = new Dog('ポチ', '柴犬');
echo $dog->speak();  // ❌ Error: $name が初期化されていない
```

PHP は警告も出さずスルーするので、実行時エラーで気づくことになります。**子でコンストラクタを書くなら parent も書く** をデフォルトの習慣に。

### プロパティの可視性を二重に書いてしまう

```php
class Dog extends Animal {
    public function __construct(
        protected string $name,    // ❌ 親と同名のプロパティをまた宣言してしまう
        protected string $breed,
    ) {
        parent::__construct($name);
    }
}
```

これをやると、親の `$name` と子の `$name` が **別物** になる場合があります(PHP のバージョンや状況による)。
**親に既にあるプロパティに対しては、子では可視性を書かない** が安全です。

### 呼び出し順序の慣習

```php
public function __construct(string $name, string $breed) {
    parent::__construct($name);  // ← 先に親を呼ぶのが慣習
    $this->breed = $breed;       // ← その後に子の処理
}
```

技術的にはどちらが先でもOKですが、**親の初期化を先に終わらせてから子の処理に入る** のが慣習です。
Java では「コンストラクタの先頭でしか `super()` を呼べない」という制約があり、その流儀が PHP にも自然に持ち込まれています。

## 補足: コンストラクタ以外の `parent::` の使い方

`parent::` はコンストラクタ以外でも使えます。
親のメソッドを呼び出して、その結果を活かすパターンです:

```php
class Cat extends Animal {
    public function speak(): string {
        $base = parent::speak();        // 親のメソッドを呼ぶ
        return $base . "(ニャー!)";   // 結果を加工する
    }
}

$cat = new Cat('タマ');
echo $cat->speak();  // "タマが鳴きます(ニャー!)"
```

「親の振る舞いをベースに、子で少しだけ拡張する」時に便利です。
今は「こういう書き方もある」とだけ知っておけばOKです。