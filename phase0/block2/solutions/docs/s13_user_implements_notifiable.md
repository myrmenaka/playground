# S13 解説: User クラスで Notifiable を実装する

## ポイント

### 1. `implements` キーワード

```php
class User implements Notifiable
```

Java と全く同じ書き方。複数実装する場合も同じ:

```php
class User implements Notifiable, Serializable
```

### 2. シグネチャは完全に一致させる

インターフェースが宣言したメソッドと、実装側のシグネチャ(メソッド名・引数・戻り値の型)は **完全に一致** させる必要がある。

```php
// インターフェース側
public function notify(): string;

// 実装側 - これと完全に一致
public function notify(): string
{
    return "...";
}
```

戻り値の型を `: int` に変えるとエラーになる:

```
Fatal error: Declaration of User::notify(): int must be compatible with Notifiable::notify(): string
```

これがインターフェースの「契約」が機能している証拠。

### 3. コンストラクタプロモーション(PHP 8)

```php
public function __construct(
    private string $name,
) {
}
```

これは PHP 8 から追加された構文。**従来の書き方** だと:

```php
private string $name;

public function __construct(string $name)
{
    $this->name = $name;
}
```

これと全く同じ意味。プロパティ宣言・コンストラクタ引数・代入の3つが1行にまとまる。
Java の record や Kotlin のプライマリコンストラクタに近い感覚。

### 4. 文字列内の `{$this->name}`

PHP は二重引用符 `"..."` の中で変数を展開できる。
プロパティアクセスは `{` `}` で囲む必要がある。

```php
// ◯ 動く
"{$this->name} さん"

// △ 動くが推奨されない
"$this->name さん"
```

迷ったら `{$...}` で囲むのが安全。

## Java との対比

```java
// Java
public interface Notifiable {
    String notify();
}

public class User implements Notifiable {
    private final String name;

    public User(String name) {
        this.name = name;
    }

    @Override
    public String notify() {
        return name + " さんに通知を送りました";
    }
}

User user = new User("山田太郎");
System.out.println(user.notify());
```

```php
// PHP
interface Notifiable
{
    public function notify(): string;
}

class User implements Notifiable
{
    public function __construct(
        private string $name,
    ) {}

    public function notify(): string
    {
        return "{$this->name} さんに通知を送りました";
    }
}

$user = new User('山田太郎');
echo $user->notify() . PHP_EOL;
```

| 観点 | Java | PHP |
|------|------|-----|
| 実装宣言 | `implements Notifiable` | `implements Notifiable`(同じ) |
| `@Override` | あり(推奨) | なし(PHP には存在しない) |
| アクセス | `user.notify()` | `$user->notify()` |
| 出力 | `System.out.println()` | `echo ... . PHP_EOL` |

## つまずきやすい点

### A. 戻り値の型を書き忘れる

```php
public function notify()  // : string が抜けている
{
    return "...";
}
```

これはエラーになる:

```
Fatal error: Declaration of User::notify() must be compatible with Notifiable::notify(): string
```

### B. メソッドを実装し忘れる

`implements` したのに `notify()` を書かないと:

```
Fatal error: Class User contains 1 abstract method and must therefore be declared abstract or implement the remaining methods (Notifiable::notify)
```

これも契約が機能している証拠。

### C. シングルクォートで変数展開しようとする

```php
// ✕ 展開されない
return '{$this->name} さんに通知';
// 結果: "{$this->name} さんに通知"(文字通り出力される)

// ◯ 二重引用符なら展開される
return "{$this->name} さんに通知";
```

PHP の二重引用符 vs シングルクォートは Java の文字列にはない区別。シングルクォートは展開なし・少しだけ高速。