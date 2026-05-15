# S5 解説: スーパーグローバル変数

## このStepのポイント

PHP には、リクエスト情報やセッションに **どこからでもアクセスできる特殊な変数** がある。
これを「スーパーグローバル変数」と呼ぶ。

Laravel はこれらを `$request` や `session()` で隠蔽しているが、裏では同じものを使っている。

## 主なスーパーグローバル変数

| 変数 | 内容 | Laravel での対応 |
|------|------|------------------|
| `$_GET` | URL クエリパラメータ | `$request->query()` |
| `$_POST` | POST されたフォームデータ | `$request->post()` |
| `$_REQUEST` | GET + POST + COOKIE をまとめたもの | `$request->input()`(近い) |
| `$_SESSION` | サーバー側のセッションデータ | `session()` |
| `$_COOKIE` | クライアントが送信した Cookie | `$request->cookie()` |
| `$_SERVER` | サーバー情報、HTTPヘッダーなど | `$request->server()` / `$request->header()` |
| `$_FILES` | アップロードファイル | `$request->file()` |
| `$_ENV` | 環境変数 | `env()` |
| `$GLOBALS` | グローバルスコープの全変数 | あまり使わない |

「スーパー」と呼ばれるのは、**関数の中でも `global` 宣言なしで使える** ため。
PHP では通常、関数内からは外の変数が見えないが、スーパーグローバルだけは例外的にどこからでも見える。

```php
$normalVar = 'hello';

function showNormalVar() {
    echo $normalVar;  // Warning: 関数の外の変数は見えない
}

function showSuperGlobal() {
    echo $_GET['name'];  // OK: スーパーグローバルはどこでも見える
}
```

## `session_start()` の正体

`session_start();` を呼ぶと、PHP は内部で以下のことをやっている:

1. **Cookie からセッションIDを取得**(なければ新規発行して Cookie にセット)
2. **保存先(デフォルトはファイル)からセッションデータを読み込み**
   - Linux なら通常 `/tmp/sess_<セッションID>` というファイル
3. **読み込んだデータを `$_SESSION` 配列に展開**
4. **スクリプト終了時に、`$_SESSION` の内容を保存先に書き戻す**

これが S4 で整理した「外部に状態を逃がす」の **具体的な実装** です。
スクリプト実行中だけ `$_SESSION` という配列に見えていて、開始時と終了時にファイルとやり取りしている。

```
[リクエスト到着]
       ↓
session_start()
       ↓
Cookie の PHPSESSID="abc123" を見る
       ↓
/tmp/sess_abc123 を読む
       ↓
$_SESSION = ['visit_count' => 3, 'last_name' => 'Alice']
       ↓
ユーザーのコードが実行される
$_SESSION['visit_count']++  ← メモリ上の配列を更新
       ↓
スクリプト終了
       ↓
$_SESSION の内容を /tmp/sess_abc123 に書き戻す
       ↓
[レスポンス送信]
```

## なぜ Laravel は `$request` で隠蔽するのか

スーパーグローバルには大きな問題がある:

### 1. グローバル変数なのでテストしにくい

```php
function processUser() {
    // $_POST に依存している
    return $_POST['name'];
}
```

これをテストするには、テストコード側で `$_POST` を書き換える必要がある。
グローバル状態に依存するコードはテスト時に副作用が起きやすい。

```php
// Laravel の場合
public function processUser(Request $request) {
    return $request->input('name');
}

// テストでは Request を差し替えればよい
$request = Request::create('/test', 'POST', ['name' => 'Alice']);
$controller->processUser($request);
```

### 2. 型が不安定

`$_POST` の値は常に文字列(配列の場合は配列)。
直接使うと、型を意識せずに使ってしまうリスクがある。

```php
// 危険: $_POST['age'] は文字列 "20"
if ($_POST['age'] === 20) { ... }  // 通らない! 型が違う

// Laravel なら
$age = $request->integer('age');  // 自動で int に変換
```

### 3. バリデーションが書きにくい

素の PHP では、空欄チェック・型チェック・形式チェックを全部自前で書く必要がある。
Laravel はバリデーションの仕組みを提供している(S2-Block4 で扱う)。

### 4. CSRF などのセキュリティが面倒

素の PHP で CSRF 対策を入れようとすると、トークン生成・検証を自分で書く必要がある。
Laravel は `@csrf` ディレクティブで自動化されている(Phase 1 で扱う)。

## Java(Servlet)との対比

| 項目 | PHP | Java Servlet |
|------|-----|--------------|
| リクエストパラメータ | `$_GET` / `$_POST` | `request.getParameter()` |
| セッション | `$_SESSION` | `request.getSession().setAttribute()` |
| Cookie | `$_COOKIE` | `request.getCookies()` |
| アクセス方法 | グローバル(どこからでも) | リクエストオブジェクト経由 |

Java Servlet の場合、HttpServletRequest を引数として受け取る形になっているので、
PHP のように「どこからでもグローバルにアクセス可能」とはなっていない。
これはテスト容易性の観点では Java のほうが優れている。

**Laravel が `Request` クラスを引き渡す方式を採用しているのは、Servlet の設計に近い**。
グローバル変数の問題を、リクエストオブジェクトの注入に置き換えることで解決している。

## つまずきやすい点

### 1. `session_start()` を呼び忘れる

`$_SESSION` を使う前に `session_start()` を呼ばないと、`$_SESSION` は空のまま。
**ヘッダー出力より前** に呼ぶ必要がある(echo の前など)。

### 2. `?? ''` を使わずに警告が出る

存在しないキーへアクセスすると Notice/Warning が出る。
`$_POST['name'] ?? ''` のように null 合体演算子で防御するのが定石。

```php
// 危険
$name = $_POST['name'];  // 'name' が無いと警告

// 安全
$name = $_POST['name'] ?? '';
```

### 3. XSS 対策を忘れる

ユーザー入力をそのまま画面に出すと、`<script>` などが実行されてしまう。
出力時は必ず `htmlspecialchars()` でエスケープする。

```php
// 危険
echo $_POST['name'];  // <script>alert('XSS')</script> が実行される

// 安全
echo htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
```

Laravel の Blade では `{{ $name }}` で自動的にエスケープされる。これは Blade の重要な仕事の一つ。

### 4. セッションの保存先

開発環境ではデフォルトで `/tmp` 配下のファイルに保存されるが、本番ではこれは使わない。
S4 で整理した通り、複数サーバー対応のため Redis やDB に保存することが多い。
Laravel では `config/session.php` の `driver` で切り替える。

## 自力Stepの解答例(参考)

写経の後の自力Step(年齢追加 + クリア機能)の解答例:

```php
// === GET でクリアリクエストが来たら ===
if ($method === 'GET' && isset($_GET['clear'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// === POST 処理に年齢バリデーション追加 ===
if ($method === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $ageInput = $_POST['age'] ?? '';

    if ($name === '') {
        $errorMessage = '名前は必須です';
    } elseif ($ageInput === '' || !ctype_digit($ageInput) || (int)$ageInput < 0) {
        $errorMessage = '年齢が不正です';
    } else {
        $_SESSION['last_name'] = $name;
        $_SESSION['last_age'] = (int)$ageInput;
        $greeting = 'こんにちは、' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' さん!';
    }
}
```

### 記述場所

```
<?php
session_start();

// ① メソッド判別
$method = $_SERVER['REQUEST_METHOD'];

// ② GETの処理(訪問回数カウント)
if ($method === 'GET') { ... }     ← ★クリア機能はここに追加

// ③ POSTの処理(バリデーション・保存)
$errorMessage = '';
$greeting = '';
if ($method === 'POST') { ... }    ← ★年齢のバリデーションをここに追加

// ④ テンプレート用の変数準備
$visitCount = ...;
$lastName = ...;

?>
<!DOCTYPE html>
...
    <form method="POST" action="">
        <input type="text" name="name" ...>    ← ★年齢の入力欄をここに追加
        <button type="submit">送信</button>
    </form>

    ★クリアリンクをこのあたりに追加
...
```


ポイント:
- `ctype_digit()` で「数字だけの文字列か」をチェック(これだと負数や小数も弾ける)
- `header('Location: ...')` でリダイレクト
- `exit` でその後の処理を止める

## 関連する公式マニュアル

- [事前定義済みの変数(スーパーグローバル)](https://www.php.net/manual/ja/language.variables.superglobals.php)
- [セッション処理](https://www.php.net/manual/ja/book.session.php)