<?php

declare(strict_types=1);

// セッションを開始する
// この一行で $_SESSION が使えるようになる
// 内部的には、Cookie からセッションIDを取得 → ファイルから状態を読み込みをしている
session_start();

// === GET / POST の判別 ===
// $_SERVER['REQUEST_METHOD'] には "GET" や "POST" が入っている
$method = $_SERVER['REQUEST_METHOD'];

// === 訪問回数のカウント(GET のときだけ増やす) ===
if ($method === 'GET') {
    // セッションに値がなければ初期化
    if (!isset($_SESSION['visit_count'])) {
        $_SESSION['visit_count'] = 0;
    }
    $_SESSION['visit_count']++;
}

// === POST 処理 ===
$errorMessage = '';
$greeting = '';

if ($method === 'POST') {
    // $_POST から name を取得(無ければ空文字)
    $name = $_POST['name'] ?? '';

    // 前後の空白を除去
    $name = trim($name);

    if ($name === '') {
        $errorMessage = '名前は必須です';
    } else {
        // セッションに最後の名前を保存
        $_SESSION['last_name'] = $name;
        // htmlspecialchars で XSS 対策(これ重要)
        $greeting = 'こんにちは、' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' さん!';
    }
}

// テンプレート用に変数を準備
$visitCount = $_SESSION['visit_count'] ?? 0;
$lastName = $_SESSION['last_name'] ?? null;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>S5: スーパーグローバル変数の演習</title>
</head>
<body>
    <h1>スーパーグローバル変数の演習</h1>

    <p>訪問回数: <?= $visitCount ?> 回目</p>

    <?php if ($greeting !== ''): ?>
        <p style="color: green;"><?= $greeting ?></p>
    <?php endif; ?>

    <?php if ($errorMessage !== ''): ?>
        <p style="color: red;"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <?php if ($lastName !== null): ?>
        <p>前回の名前: <?= htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <h2>名前を入力してください</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="お名前">
        <button type="submit">送信</button>
    </form>

    <hr>

    <h3>デバッグ情報(中で何が起きているかを見るため)</h3>
    <p>HTTPメソッド: <?= htmlspecialchars($method, ENT_QUOTES, 'UTF-8') ?></p>
    <p>$_GET の中身:</p>
    <pre><?= htmlspecialchars(print_r($_GET, true), ENT_QUOTES, 'UTF-8') ?></pre>
    <p>$_POST の中身:</p>
    <pre><?= htmlspecialchars(print_r($_POST, true), ENT_QUOTES, 'UTF-8') ?></pre>
    <p>$_SESSION の中身:</p>
    <pre><?= htmlspecialchars(print_r($_SESSION, true), ENT_QUOTES, 'UTF-8') ?></pre>
</body>
</html>