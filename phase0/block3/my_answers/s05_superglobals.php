<?php

declare(strict_types=1);

// セッション開始
session_start();

$method = $_SERVER['REQUEST_METHOD'];

// クリア用
if ($method === 'GET' && isset($_GET['clear'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// GET 処理
if ($method === 'GET') {
    // セッションに値が無ければ初期化
    if (!isset($_SESSION['visit_count'])) {
        $_SESSION['visit_count'] = 0;
    }
    $_SESSION['visit_count']++;
}

$errorMessage = '';
$greeting = '';

// POST 処理
if ($method === 'POST') {
    // $_POST から name を取得(無ければ空文字)
    $name = $_POST['name'] ?? '';
    // 前後の空白を除去
    $name = trim($name);
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

$visitCount = $_SESSION['visit_count'] ?? 0;
$lastName = $_SESSION['last_name'] ?? null;
$lastAge = $_SESSION['last_age'] ?? null;

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
        <input type="text" name="name" placeholder="お名前"><br>
        <input type="text" name="age" placeholder="年齢">
        <button type="submit">送信</button>
    </form>
    <button onclick="location.href='?clear=1'">セッションをクリア</button>

    <hr>

    <h3>デバッグ情報</h3>
    <p>HTTP メソッド: <?= htmlspecialchars($method, ENT_QUOTES, 'UTF-8') ?></p>
    <p>$_GET の中身:</p>
    <pre><?= htmlspecialchars(print_r($_GET, true), ENT_QUOTES, 'UTF-8') ?></pre>
    <p>$_POST の中身:</p>
    <pre><?= htmlspecialchars(print_r($_POST, true), ENT_QUOTES, 'UTF-8') ?></pre>
    <p>$_SESSION の中身:</p>
    <pre><?= htmlspecialchars(print_r($_SESSION, true), ENT_QUOTES, 'UTF-8') ?></pre>
</body>
</html> 