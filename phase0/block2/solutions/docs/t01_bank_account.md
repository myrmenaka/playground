# T1 解説: BankAccount クラス

## このテストの位置づけ

Block 2 完了テストの1問目。クラスの基本(プロパティ、コンストラクタ、メソッド)に加えて、**例外を投げる** という Block 2 では未習の要素を組み合わせる。
例外は Java の経験から類推して書ける学習者が多いため、テストとして含めている。詰まった場合は S18・S19 で補強する設計。

## ポイント

### 1. コンストラクタプロモーションの自然な使用

```php
public function __construct(
    private int $balance,
) {
}
```

S3 で学んだ書き方が定着していれば自然に出てくる。プロパティ宣言・引数受け取り・代入の3役を兼ねる。

### 2. 例外を投げる構文

```php
throw new \Exception("残高不足です(残高: {$this->balance}円、出金額: {$amount}円)");
```

- `\Exception` の `\` は名前空間のルートを指す
- 例外メッセージには **状況の具体的な値**(残高と出金額)を含める
- これにより、呼び出し側が原因を即座に把握できる

### 3. 責務分離: クラス側と呼び出し側

クラス側は「事実」だけ伝える:

```
"残高不足です(残高: 700円、出金額: 1000円)"
```

呼び出し側が「装飾」を加える:

```
"エラー: " + メッセージ
```

これにより、同じクラスを使う複数の呼び出し側(コンソール / ログ / API)が、それぞれ違う装飾で扱える。

### 4. try-catch の使い分け

例外が **飛ばないことが分かっている操作** は try-catch で囲まない:

```php
$account->deposit(500);  // 入金は例外を投げない
$account->withdraw(800); // 残高足りるので例外を投げない
```

例外が **飛ぶ可能性がある操作** だけ try-catch で囲む:

```php
try {
    $account->withdraw(1000);  // 残高不足で例外
} catch (\Exception $e) {
    // ...
}
```

全部 try-catch で囲むのは過剰防御。例外が飛ぶ場所を意識して囲むのが業務的なスタイル。

## Java との対比

```java
// Java
public class BankAccount {
    private int balance;

    public BankAccount(int balance) {
        this.balance = balance;
    }

    public void deposit(int amount) {
        this.balance += amount;
    }

    public void withdraw(int amount) {
        if (amount > this.balance) {
            throw new RuntimeException(
                String.format("残高不足です(残高: %d円、出金額: %d円)", this.balance, amount)
            );
        }
        this.balance -= amount;
    }

    public int getBalance() {
        return this.balance;
    }
}

// 呼び出し側
BankAccount account = new BankAccount(1000);
account.deposit(500);
System.out.println("入金後の残高: " + account.getBalance() + "円");

account.withdraw(800);
System.out.println("出金後の残高: " + account.getBalance() + "円");

try {
    account.withdraw(1000);
} catch (RuntimeException e) {
    System.out.println("エラー: " + e.getMessage());
}
```

| 観点 | Java | PHP |
|------|------|-----|
| 例外クラス | `RuntimeException` など | `\Exception`(検査例外なし) |
| `throws` 宣言 | 検査例外で必要 | 不要 |
| 文字列フォーマット | `String.format()` | 文字列展開 `"{$var}"` |
| 例外キャッチ | `catch (Exception e)` | `catch (\Exception $e)` |

PHP には **検査例外がない** ので、`throws` 宣言を書かなくて良い。すべての例外が Java の `RuntimeException` のように扱える。
業務的にはこれが楽な反面、「**どの例外が投げられるかコードから読み取りづらい**」というデメリットもある。PHPDoc コメントで `@throws` を書くのが慣習。

```php
/**
 * @throws \Exception 残高不足の場合
 */
public function withdraw(int $amount): void
{
    // ...
}
```

## つまずきやすい点

### A. クラス側で try-catch してしまう

例外の意味を取り違えると、クラス側で受け止めてしまう:

```php
// ✕ 悪い例
public function withdraw(int $amount): void
{
    try {
        if ($amount > $this->balance) {
            throw new \Exception("残高不足");
        }
        $this->balance -= $amount;
    } catch (\Exception $e) {
        echo $e->getMessage();  // ここで処理してしまう
    }
}
```

これだと「異常を呼び出し側に伝える」という例外の目的が失われる。
呼び出し側からは「withdraw が成功したのか失敗したのか分からない」状態になる。

### B. `\Exception` の `\` を忘れる

```php
throw new Exception("...");  // ファイルの先頭で名前空間を宣言していると動かない
throw new \Exception("..."); // ◯ ルート名前空間から確実に参照
```

Phase 0 では名前空間を本格的に扱わないので、`\` を付ける癖を今のうちにつけておくと安心。

### C. 入金にマイナス値を渡された場合

業務観点で言うと、`deposit(-500)` は実質出金になってしまう。
ここまで検証するのは過剰だが、業務コードなら以下のような検証を追加することが多い:

```php
public function deposit(int $amount): void
{
    if ($amount <= 0) {
        throw new \InvalidArgumentException("入金額は正の整数である必要があります");
    }
    $this->balance += $amount;
}
```

「AI が出したコードを評価する」観点で言うと、**マイナス値・ゼロ・極端に大きい値などの異常入力** に対する検証があるかをチェックする目を持つと良い。

## 「AIに書かせる時の評価軸」としての本問題

このクラスを将来 AI に書かせた場合、以下の観点で評価できると業務的に強い:

1. 残高不足の検証はあるか?
2. 例外を投げているか? それとも `false` を返している?
3. 例外メッセージは具体的か?(状況の値が含まれているか)
4. 入金・出金にマイナス値が渡された場合の検証は?
5. 残高プロパティは外から書き換えられないように `private` になっているか?
6. メソッドの戻り値の型宣言があるか?

これらの観点を持つこと自体が、ロードマップのゴール「コードレベルで評価できる」の核心。