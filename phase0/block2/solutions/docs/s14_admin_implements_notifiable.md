# S14 解説: Admin クラスで Notifiable を実装する

## ポイント

### 1. 同じインターフェースで、違う実装

`User` も `Admin` も `Notifiable` を `implements` しているが、`notify()` の中身は全く違う。

```php
// User の notify()
return "{$this->name} さんに通知を送りました";

// Admin の notify()
return "【管理者通知】{$this->department} の {$this->name} さんへ通知を送信しました";
```

これがポリモーフィズムの核心。
「`Notifiable` を実装したオブジェクトなら何でも `notify()` を呼べる」という契約だけ守られていれば、中身は自由。

### 2. コンストラクタプロモーションでプロパティを複数持つ

```php
public function __construct(
    private string $name,
    private string $department,
) {
}
```

S13 では1つだったプロパティが2つになった。
書き方は同じで、カンマ区切りで並べるだけ。プロパティ宣言・引数受け取り・代入の3役を兼ねている。

### 3. なぜインターフェースに価値があるのか

ここで「`User` と `Admin` で別々に `notify()` 書くなら、インターフェースいらなくない?」と疑問に思うかもしれない。
答えは **「両方を同じように扱えるコードが書ける」** から。

例えば次のような使い方ができる:

```php
$notifiables = [
    new User('山田太郎'),
    new Admin('鈴木花子', '営業部'),
];

foreach ($notifiables as $n) {
    echo $n->notify() . PHP_EOL;
}
```

`User` と `Admin` は型が違うのに、同じループで扱える。
これは「`Notifiable` を実装している」という契約があるおかげ。
これがインターフェースの **本当の価値**。

(これは次の S9 の `speak()` と同じパターンを、Block 2 では実際に体験している)

## Java との対比

Java で書くとほぼ同じ構造になる:

```java
public interface Notifiable {
    String notify();
}

public class Admin implements Notifiable {
    private final String name;
    private final String department;

    public Admin(String name, String department) {
        this.name = name;
        this.department = department;
    }

    @Override
    public String notify() {
        return "【管理者通知】" + department + " の " + name + " さんへ通知を送信しました";
    }
}
```

| 観点 | Java | PHP |
|------|------|-----|
| 文字列結合 | `+` 演算子 | `.` 演算子 または `"{$var}"` |
| 不変プロパティ | `private final` | `private`(readonly でさらに不変化可) |
| コンストラクタ簡略化 | record(Java 14+) | コンストラクタプロモーション(PHP 8+) |

PHP の `readonly` プロパティ(PHP 8.1+)を使うとさらに Java の `final` に近づく:

```php
public function __construct(
    private readonly string $name,
    private readonly string $department,
) {}
```

これは後で扱うので、今は知識として置いておくだけでOK。

## つまずきやすい点

### A. インターフェースを書き忘れる / S13 から持ってこない

S14 では `Notifiable` の定義も同じファイルに書く必要がある。
S13 のファイルを参照したくなるが、自力Stepなので **記憶から書き起こす** のがトレーニングになる。

### B. 出力例との差分

「動けばOK」ではなく、**出力例と1文字単位で一致しているか** を確認する習慣を。
本番業務のレビューでも「期待出力と実際の出力が違います」は頻出の指摘事項。

### C. プロパティの順番

コンストラクタ引数の順番と、インスタンス化時の引数の順番は一致させる必要がある:

```php
// 定義: name, department の順
public function __construct(
    private string $name,
    private string $department,
) {}

// 使用: 同じ順番で渡す
$admin = new Admin('鈴木花子', '営業部');  // ◯
$admin = new Admin('営業部', '鈴木花子');  // ✕ 名前と部署が入れ替わる
```

PHP 8 からは名前付き引数も使える:

```php
$admin = new Admin(name: '鈴木花子', department: '営業部');
```

これだと順番を間違えないので、コンストラクタの引数が増えてきたら有効。