# S02 解説: 複数のUserインスタンスを扱う

## ポイント

### オブジェクトの配列

PHP では配列に**任意の型**(プリミティブ、連想配列、オブジェクト、混在も可能)を入れられる。クラスのインスタンスも例外なく配列の要素にできる。

```php
$users = [
    new User('Alice', 25),
    new User('Bob', 30),
    new User('Charlie', 28),
];
```

T2 で扱った「**連想配列の配列**」と構造的には同じ。違いは個別の要素が連想配列ではなく `User` インスタンスである点。

| 観点 | T2(連想配列の配列) | S02(オブジェクトの配列) |
|------|------------------|------------------------|
| 個別要素の型 | 連想配列 | クラスのインスタンス |
| フィールドアクセス | `$user['name']` | `$user->name`(public の場合) |
| メソッド呼び出し | できない(関数を別途呼ぶ) | `$user->introduce()` |
| 型の保護 | キーのタイポは実行時まで気付けない | プロパティ・メソッドが補完される |

### 「リスト → 個別オブジェクト → フィールド」の3階層

T2 で扱ったこの構造が、**実際のクラスを使った形**で再登場している。

```php
foreach ($users as $user) {                // リスト → 個別オブジェクト
    echo $user->introduce() . PHP_EOL;     // 個別オブジェクトのメソッドを呼ぶ
}
```

T2 と全く同じパターン。**実務のデータ処理は、ほぼ全てこの3階層構造で書かれる**。

### トレイリングカンマ(現代のPHP標準)

```php
$users = [
    new User('Alice', 25),
    new User('Bob', 30),
    new User('Charlie', 28),    // ← 末尾にもカンマ
];
```

- 配列のトレイリングカンマは PHP 7.3+ でサポート
- 関数引数のトレイリングカンマは PHP 8.0+ でサポート
- Git の diff が綺麗になる、要素の追加・削除がしやすい、というメリット
- Laravel のソースコードでもほぼ全て付いている

## 別解

### ユーザー情報を別の配列で持って、ループでインスタンス化

```php
$userData = [
    ['name' => 'Alice',   'age' => 25],
    ['name' => 'Bob',     'age' => 30],
    ['name' => 'Charlie', 'age' => 28],
];

$users = [];
foreach ($userData as $data) {
    $users[] = new User($data['name'], $data['age']);
}
```

データソース(JSON、DB、CSV など)から取得した連想配列をオブジェクトに変換するパターン。実務では非常によく使う。

### `array_map` で変換(関数型スタイル)

```php
$userData = [
    ['name' => 'Alice',   'age' => 25],
    ['name' => 'Bob',     'age' => 30],
    ['name' => 'Charlie', 'age' => 28],
];

$users = array_map(
    fn($data) => new User($data['name'], $data['age']),
    $userData
);
```

`array_map` で「変換」を表現する。`foreach` 版より短いが、慣れていないと読みづらい。Laravel の Collection では `map` メソッドが多用される。

### 名前付き引数(PHP 8.0+)

```php
$users = [
    new User(name: 'Alice',   age: 25),
    new User(name: 'Bob',     age: 30),
    new User(name: 'Charlie', age: 28),
];
```

引数名を明示することで、何を渡しているかが一目で分かる。引数の数が多いコンストラクタで特に有効。

## つまずきやすい点

### 1. クラス定義をループ内に書いてしまう

```php
foreach ($userData as $data) {
    class User { ... }   // ❌ ループの中でクラス定義はできない
    $users[] = new User(...);
}
```

PHP では同じクラスを2回定義できない。ループ内に `class User` を書くと2回目以降でエラー。**クラス定義はファイルのトップレベル(またはオートローダ経由)に1度だけ**。

### 2. インスタンス化の `new` を忘れる

```php
$users = [
    User('Alice', 25),       // ❌ ただの関数呼び出し扱い → エラー
    new User('Alice', 25),   // ⭕
];
```

クラスのインスタンスを作る時は **必ず `new` を付ける**。Java と同じ感覚。

### 3. `$user->introduce` と書いてしまう(`()` 忘れ)

```php
echo $user->introduce . PHP_EOL;     // ❌ プロパティとして探そうとして警告
echo $user->introduce() . PHP_EOL;   // ⭕ メソッド呼び出し
```

メソッドは必ず `()` 付き。プロパティは `()` なし。Java と同じ。

### 4. プロパティが `private` だと外からアクセスできない

```php
foreach ($users as $user) {
    echo $user->name;   // ❌ Fatal error: Cannot access private property
}
```

`private` プロパティは**クラスの外**(つまりクラスの定義範囲外、`foreach` の中も含む)からはアクセスできない。

外から値が必要な場合の選択肢:
- **getter メソッドを定義**(`getName()`)→ S04 で扱う
- **public プロパティにする**(カプセル化を諦める)
- **メソッド経由で出力**(今回の `introduce()` のように)

### 5. インスタンス変数とプロパティを混同する

```php
class User {
    private string $name;
    
    public function __construct(string $name) {
        $name = $name;          // ❌ 引数 $name に引数 $name を代入(無意味)
        $this->name = $name;    // ⭕ プロパティ $this->name に引数 $name を代入
    }
}
```

`$name` だけだとローカル変数(引数)、`$this->name` だとプロパティ。**`$this->` の有無で全く別物**。Java の `this.name = name` と同じ感覚で、`$this->` を必ず付ける。

## Java との対比

```java
// Java
import java.util.Arrays;
import java.util.List;

public class Main {
    public static void main(String[] args) {
        List<User> users = Arrays.asList(
            new User("Alice", 25),
            new User("Bob", 30),
            new User("Charlie", 28)
        );
        
        for (User user : users) {
            System.out.println(user.introduce());
        }
    }
}
```

```php
// PHP
$users = [
    new User('Alice', 25),
    new User('Bob', 30),
    new User('Charlie', 28),
];

foreach ($users as $user) {
    echo $user->introduce() . PHP_EOL;
}
```

| 観点 | Java | PHP |
|------|------|-----|
| コレクションの型 | `List<User>`(型パラメータあり) | `array`(中身の型は指定不可) |
| リテラル | `Arrays.asList(...)` | `[...]` |
| ループ | `for (User user : users)` | `foreach ($users as $user)` |
| メソッド呼び出し | `user.introduce()` | `$user->introduce()` |
| 中身の型保証 | `List<User>` で保証(コンパイル時) | PHPDoc + 静的解析で補強 |

**ロジック構造は完全に同じ**。Java の `List<User>` のような「中身の型まで指定できる」型システムは PHP にはないため、コメントや静的解析ツール(PHPStan)で補う。

```php
/** @var User[] $users */
$users = [...];
```

このコメントを書くと、IDE(VSCode + Intelephense)がループ内の `$user` を `User` 型として認識し、メソッド補完が効くようになる。**実務のコードでは PHPDoc を書く習慣を付ける**と、IDE のサポートが格段に強力になる。

## Phase 2 への伏線

実務では、配列の代わりに **Laravel の Collection** が圧倒的に多用される。

```php
$users = collect([
    new User('Alice', 25),
    new User('Bob', 30),
    new User('Charlie', 28),
]);

$users->each(fn($user) => print($user->introduce() . PHP_EOL));
```

Collection には `filter`, `map`, `each`, `pluck` など便利なメソッドが豊富にある。Phase 2 で詳しく扱う。

データベースから取得する場合は、もっとシンプルになる:

```php
$users = User::all();   // Eloquent なら全ユーザー取得が1行

foreach ($users as $user) {
    echo $user->introduce() . PHP_EOL;
}
```

`User::all()` の戻り値は **`User` インスタンスの Collection**。S02 で書いた構造と全く同じものが、データベースから自動的に作られる。これが Eloquent の威力。

今は手動でインスタンス化しているが、**Phase 2 でデータベース連携を学ぶと「データベースのテーブル ↔ クラスのインスタンス」という対応関係**が見えてきて、Web アプリケーション開発の核心が掴める。今回 S02 で書いたコードは、その**前哨戦**になる。