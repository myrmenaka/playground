# S18 解説: 例外処理

## ポイント

### 1. 例外を投げる構文

```php
throw new \Exception("メッセージ");
```

- `throw` キーワードに `new \Exception(...)` を続ける
- `\Exception` の `\` は名前空間のルートを指す
- 例外メッセージには **状況の具体的な値** を含めるのが業務的に望ましい

```php
// △ 情報が少ない
throw new \Exception("エラー");

// ◯ 状況が分かる
throw new \Exception("残高不足です(残高: {$balance}円、出金額: {$amount}円)");
```

### 2. `try-catch` の基本

```php
try {
    // 例外が飛ぶ可能性のある処理
} catch (\Exception $e) {
    // 例外を受け取って処理
    echo $e->getMessage();
}
```

Java とほぼ同じ。違いは:
- 例外クラスの前に `\` が必要(名前空間のルート参照)
- 変数の前に `$` が必要

### 3. PHP 標準の例外クラス

PHP には多くの標準例外クラスがある。よく使うもの:

| クラス | 用途 |
|--------|------|
| `\Exception` | 汎用。何にでも使えるが、できれば下記を使う |
| `\InvalidArgumentException` | 引数が不正な時(空文字、null、不正な型) |
| `\OutOfRangeException` | 範囲外の値(負の数、上限超えなど) |
| `\RuntimeException` | 実行時エラー(状態の問題) |
| `\LogicException` | プログラムのロジック自体が間違っている時 |
| `\TypeError` | 型エラー(自動で投げられる、自分では投げない) |

業務的には「**汎用 `\Exception` ではなく、意味のある具体的なクラスを使う**」のが推奨される。
理由:
- 呼び出し側が「何のエラーか」を catch 節で識別できる
- コードを読む人が「ここでは引数不正が想定されている」と分かる

### 4. カスタム例外クラスの作り方

```php
class EmailAlreadyExistsException extends \Exception
{
}
```

これだけ。`\Exception` を継承するだけでカスタム例外になる。
中身は空でOK。**クラス名そのものが「どんなエラーか」を表現する**。

これにより、呼び出し側で `catch (EmailAlreadyExistsException $e)` のように **エラーの種類で使い分ける** ことができる。

業務での命名規則:
- `〜Exception` で終わるのが PHP の慣習
- 何が起きたかが名前から分かるように(`UserNotFoundException`、`InvalidPasswordException` など)

### 5. 複数 `catch` 節は上から順にマッチする

```php
try {
    $registration->register($email, $age);
} catch (\InvalidArgumentException $e) {
    // ① まずこれにマッチするか確認
} catch (\OutOfRangeException $e) {
    // ② ①にマッチしなければこれを確認
} catch (EmailAlreadyExistsException $e) {
    // ③ ②にもマッチしなければこれを確認
}
```

Java の `try-catch` と全く同じ仕組み。

**広い例外を先に書くと、後ろの catch 節が機能しなくなる**:

```php
// ✕ 悪い例
catch (\Exception $e) {
    // すべての例外がここでマッチしてしまう
}
catch (\InvalidArgumentException $e) {
    // 永遠に呼ばれない
}
```

基本ルール: **「具体的な例外を上、汎用的な例外を下」**

### 6. `finally` 節

```php
try {
    // ...
} catch (\Exception $e) {
    // ...
} finally {
    // 例外が発生してもしなくても、必ず実行される
}
```

Java と同じ概念。主な用途:
- **リソースの後片付け**: ファイルクローズ、DB接続クローズ
- **共通の終了処理**: ログ出力など

Laravel ではトランザクション処理で `finally` をよく見かける。

### 7. PHP 8 の Multi-catch(知識として)

PHP 8 では、複数の例外を1つの catch 節でまとめて受けられる:

```php
try {
    // ...
} catch (\InvalidArgumentException | \OutOfRangeException $e) {
    echo "エラー(入力値の問題): " . $e->getMessage();
}
```

`|`(パイプ)で区切る。共通の処理をしたい時に便利。
Java 7+ の Multi-catch と同じ書き方。

### 8. `foreach ($testCases as [$email, $age])` の分割代入

写経コードで使った構文:

```php
foreach ($testCases as [$email, $age]) {
    // ...
}
```

これは PHP 7.1+ で使える **配列の分割代入**。
`$testCases` の各要素が `[email, age]` の形の配列なので、それを1行で `$email` と `$age` に分解している。

従来の書き方だと:

```php
foreach ($testCases as $testCase) {
    $email = $testCase[0];
    $age = $testCase[1];
    // ...
}
```

これと同じ。
JavaScript の `const [a, b] = array` と同じ感覚。

## 例外設計の本質: 責務分離

例外の最大の価値は、**クラス側と呼び出し側の責務を分けられる** こと。

クラス側(`UserRegistration`)は「異常を伝える」だけで、それをどう扱うかは知らない:
- 画面にエラー表示する? → 呼び出し側が決める
- ログに残す? → 呼び出し側が決める
- リトライする? → 呼び出し側が決める

呼び出し側が状況に応じて判断する。これにより、同じクラスを複数の文脈(コンソール / API / バッチ)で使い回せる。

## Java との対比

```java
// Java
public class EmailAlreadyExistsException extends Exception {
    public EmailAlreadyExistsException(String message) {
        super(message);
    }
}

public class UserRegistration {
    public void register(String email, int age)
            throws EmailAlreadyExistsException {  // 検査例外なので throws 必須
        if (email.isEmpty()) {
            throw new IllegalArgumentException("メールアドレスは必須です");
        }
        if (age < 0 || age > 150) {
            throw new IndexOutOfBoundsException("年齢は0〜150の範囲で指定してください");
        }
        if ("existing@example.com".equals(email)) {
            throw new EmailAlreadyExistsException(email + " は既に登録されています");
        }
        System.out.println("登録完了");
    }
}
```

| 観点 | Java | PHP |
|------|------|-----|
| 例外クラス継承 | `extends Exception` | `extends \Exception` |
| 検査例外 | あり(`throws` 必須) | なし(`throws` 不要) |
| 引数不正用クラス | `IllegalArgumentException` | `\InvalidArgumentException`(名前が違う!) |
| 範囲外用クラス | `IndexOutOfBoundsException` | `\OutOfRangeException`(名前が違う!) |
| `finally` | あり | あり(同じ) |
| Multi-catch | Java 7+ | PHP 8+ |
| メッセージ取得 | `e.getMessage()` | `$e->getMessage()` |

**PHP の例外クラス名は Java と微妙に違う** ので、業務で「Java の `IllegalArgument` 探そうとして見つからない」となりがち。PHP の名前を覚え直す必要がある。

また PHP には **検査例外がない** ので、`throws` 宣言を書かなくて良い。
ただし慣習として PHPDoc コメントで `@throws` を書く:

```php
/**
 * @throws \InvalidArgumentException メールアドレスが空の場合
 * @throws \OutOfRangeException 年齢が範囲外の場合
 * @throws EmailAlreadyExistsException メールアドレスが既に登録済みの場合
 */
public function register(string $email, int $age): void
{
    // ...
}
```

## つまずきやすい点

### A. `\Exception` の `\` を忘れる

```php
throw new Exception("...");  // ファイルの先頭で名前空間を宣言していると動かない
throw new \Exception("..."); // ◯ ルート名前空間から確実に参照
```

Phase 0 では名前空間を本格的に扱わないので、`\` を付ける癖を今のうちに。

### B. catch 節の順番ミス

```php
// ✕ 広い例外を先に書く
catch (\Exception $e) { ... }            // 全部ここでマッチ
catch (\InvalidArgumentException $e) { } // 呼ばれない
```

**具体的な例外を上、汎用的な例外を下**。

### C. クラス側で try-catch してしまう

例外の意味を取り違えると、クラス側で受け止めてしまう:

```php
// ✕ 悪い例
public function register(string $email, int $age): void
{
    try {
        if ($email === '') {
            throw new \InvalidArgumentException("...");
        }
    } catch (\Exception $e) {
        echo $e->getMessage();  // ここで処理してしまう
    }
}
```

これだと「異常を呼び出し側に伝える」という例外の目的が失われる。
呼び出し側からは「register が成功したのか失敗したのか分からない」状態になる。

### D. `finally` 内で `return` するとどうなる?

これは少し罠:

```php
function test(): string
{
    try {
        return "try";
    } catch (\Exception $e) {
        return "catch";
    } finally {
        return "finally";  // ← これが最終的に返る
    }
}

echo test();  // "finally"
```

`finally` 内の `return` は、`try` や `catch` の `return` を **上書きする**。
業務的には混乱の元なので、`finally` 内で `return` するのは避けるのが無難。

### E. すべての例外を catch するのは過剰

業務では「想定外の例外は上位に伝播させる」のが基本。
何でもかんでも `catch (\Exception $e)` で握りつぶすと、本当の問題を見逃す。

```php
// △ 過剰防御
try {
    // 何かの処理
} catch (\Exception $e) {
    // とりあえずログだけ
}

// ◯ 想定する例外だけキャッチ
try {
    // 何かの処理
} catch (\InvalidArgumentException $e) {
    // この例外は予期している
}
// それ以外の例外は上位に伝播する
```

## Laravel での例外の典型例

Phase 1 以降で出会う Laravel の例外:

- `Illuminate\Database\Eloquent\ModelNotFoundException`: `findOrFail()` で見つからない時
- `Illuminate\Validation\ValidationException`: バリデーション失敗時
- `Illuminate\Auth\AuthenticationException`: 認証失敗時
- `Illuminate\Auth\Access\AuthorizationException`: 認可失敗時

これらは Laravel が自動で適切な HTTP レスポンスに変換してくれる。
S18 で学んだ「カスタム例外を投げる、catch 節で使い分ける」が、フレームワークレベルで仕組み化されている。

## 次のStep への接続

S18 で例外の基本を押さえたので、次の S19 では **自力で例外を含むコードを書く** ことに挑戦する。
Block 2 完了テスト T1 で例外を統合的に使えるようになる手前の最後の練習ステップ。