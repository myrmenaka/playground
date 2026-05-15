# S5 自力Step 解答解説(完全版)

写経パートを終えた後の自力Step(年齢追加 + クリア機能)の模範解答とその解説。

## 写経版からの変更点

### 1. クリア処理を最上部に移動した(ガード節)

写経版にはクリア機能が無かった。自力Stepで追加する際、**処理の順序が重要**。

```php
// ❌ よくない書き方: GET 処理の中に書く
if ($method === 'GET') {
    $_SESSION['visit_count']++;       // クリア前に1回カウントしてしまう

    if (isset($_GET['clear'])) {
        $_SESSION = [];
        // ...
    }
}

// ✅ 良い書き方: ガード節として最上部に
if ($method === 'GET' && isset($_GET['clear'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

if ($method === 'GET') {
    $_SESSION['visit_count']++;
}
```

#### ガード節とは

「**不要な処理は早期にreturn/exitで弾く**」という設計パターン。

```php
// ガード節を使わない書き方
function process($user) {
    if ($user !== null) {
        if ($user->isActive()) {
            if ($user->hasPermission()) {
                // 本体処理
                doSomething();
            }
        }
    }
}

// ガード節を使った書き方
function process($user) {
    if ($user === null) return;
    if (!$user->isActive()) return;
    if (!$user->hasPermission()) return;

    // 本体処理
    doSomething();
}
```

**メリット**:
- ネストが浅くなり読みやすい
- 「本体処理」が条件分岐の奥に埋もれない
- 例外的なケースが先頭に並ぶので、コードの「主役」が明確

業務でもよく出てくる考え方なので、覚えておくと良い。

### 2. 年齢のバリデーション

#### なぜ `ctype_digit` を選んだか

「数字だけの文字列か」をチェックする関数はいくつかある:

| 関数 | "20" | "-5" | "3.14" | "abc" | "" |
|------|------|------|--------|-------|-----|
| `is_numeric()` | ✅ | ✅ | ✅ | ❌ | ❌ |
| `ctype_digit()` | ✅ | ❌ | ❌ | ❌ | ❌ |
| `is_int()` | ❌ | ❌ | ❌ | ❌ | ❌ |

`$_POST` の値は **必ず文字列** なので、`is_int()` は常に false になる(罠)。
年齢は「自然数(0以上の整数)」を期待しているので、`ctype_digit` が最適。

ちなみに `ctype_digit("0")` は true なので、`(int)$ageInput < 0` のチェックは
実は不要(`ctype_digit` を通った時点で 0 以上)。ただし防御的に書いておくのは悪くない。

#### バリデーションのまとめ方

複数のチェックを `||` で繋いでまとめている:

```php
if ($ageInput === '' || !ctype_digit($ageInput) || (int)$ageInput < 0) {
    $errorMessage = '年齢が不正です';
}
```

これは「**どれか1つでも引っかかったらエラー**」という意味。
順序は重要で、`ctype_digit` の前に空文字チェックをしているのは、
空文字に対する `ctype_digit` の挙動を意識しなくて済むようにするため。

### 3. `??=` (null 合体代入演算子)を使った初期化

PHP 7.4 以降で使えるモダンな書き方:

```php
// 従来の書き方
if (!isset($_SESSION['visit_count'])) {
    $_SESSION['visit_count'] = 0;
}

// モダンな書き方
$_SESSION['visit_count'] ??= 0;
```

「`$_SESSION['visit_count']` が未設定 or null なら、0 を代入する」という意味。
3行が1行になる。

### 4. 年齢の表示は `htmlspecialchars` 不要

```php
<?php if ($lastAge !== null): ?>
    <p>前回の年齢: <?= $lastAge ?> 歳</p>
<?php endif; ?>
```

`$lastAge` は `(int)` キャスト済みなので、型は確実に int。
int は `<script>` のような文字列を含めないため、XSS 攻撃に使えない。
よってエスケープ不要。

「**型が安全なものはエスケープ不要**」という感覚は、Laravel の Blade を読むときにも役立つ。
Blade の `{{ }}` は自動エスケープしてくれるが、内部的には文字列に対してのみ動く。

### 5. クリア処理を `<a>` タグに変更

写経時のレビューで `<button onclick="...">` を使っていた箇所を `<a>` タグに変更:

```php
// 元のコード
<button onclick="location.href='?clear=1'">セッションをクリア</button>

// 改善版
<a href="?clear=1">セッションをクリア</a>
```

**理由**:
- JavaScript に頼らずに動く(JavaScript 無効環境でも動作)
- 「リンク」というセマンティクス(意味)が HTML として正しい
- アクセシビリティ的にも自然(スクリーンリーダーが「リンク」と読み上げる)
- ブラウザの「リンクを新しいタブで開く」などの機能が使える

`<button onclick>` は「ボタンの見た目だけ欲しい」「JavaScript で動的に何かする」場合に使う。
今回は「別のページへ移動するだけ」なので、`<a>` のほうが適切。

ただし、**本来の REST 設計では「状態を変更する操作」(クリア、削除など)は POST/DELETE を使うべき**。
GET でクリアできるようにすると、検索エンジンのクローラーやプリフェッチで意図せず実行されるリスクがある。
今回は学習目的なのでこのままだが、Laravel に入ったらフォーム + POST + CSRF トークンの組み合わせを使うことになる。

## 完成形で何が学べたか

このStepを通して、以下を体感した:

1. **`$_GET` / `$_POST` / `$_SESSION` の使い分け**
   - `$_GET`: URL から取得(検索条件、状態変更しない操作)
   - `$_POST`: フォーム送信(状態変更を伴う操作)
   - `$_SESSION`: リクエスト間で保持したい状態

2. **HTML フォームの値は必ず文字列で来る**
   - 数値を期待していても、`$_POST['age']` は `"20"` のような文字列
   - 型変換とバリデーションを自分で書く必要がある

3. **セキュリティの基本(XSS 対策)**
   - 出力時の `htmlspecialchars()` を欠かさない習慣
   - 型が安全なものはエスケープ不要、という見極め

4. **ガード節という設計パターン**
   - 「不要な処理は早期に弾く」考え方
   - ネストを浅く保ち、コードの主役を明確にする

5. **これらすべてを Laravel が隠蔽してくれている、ということ**
   - Phase 1 で Laravel に入ると、上の作業が `$request->validate([...])` の一行で済む
   - 「便利」だけでなく「何を肩代わりしてくれているか」が分かる

## Phase 1 への布石

Phase 1 で Laravel の `Request` クラスを学ぶときに、ぜひ以下を意識して読んでほしい:

| 素のPHPで書いたこと | Laravel での対応 |
|--------------------|------------------|
| `$_POST['name'] ?? ''` | `$request->input('name')` |
| `trim($_POST['name'])` | `$request->input('name')` + 自動trim middleware |
| `ctype_digit` などの手動バリデーション | `$request->validate(['age' => 'required\|integer\|min:0'])` |
| `htmlspecialchars` の手動呼び出し | Blade の `{{ }}` で自動エスケープ |
| `session_start()` + `$_SESSION` | `session()->put()` / `session('key')` |
| `header('Location: ...')` + `exit` | `return redirect('/path')` |
| CSRF 対策(今回は実装せず) | `@csrf` ディレクティブで自動化 |

「**Laravel は魔法ではなく、面倒な手作業を整理してくれているだけ**」という感覚を持って Phase 1 に入ると、フレームワークへの理解が一段深くなる。