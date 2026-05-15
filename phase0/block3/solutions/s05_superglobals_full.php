<?php

declare(strict_types=1);

// セッションを開始する
session_start();

$method = $_SERVER['REQUEST_METHOD'];

// === クリア処理(ガード節として最初に処理) ===
// ?clear=1 でアクセスされた場合は、セッションをリセットして即リダイレクト
// 訪問回数カウントを通らずに済むので、不要な処理が走らない
if ($method === 'GET' && isset($_GET['clear'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// === GET 処理(訪問回数カウント) ===
if ($method === 'GET') {
    // null 合体代入演算子(??=)で未設定なら 0 を代入
    $_SESSION['visit_count'] ??= 0;
    $_SESSION['visit_count']++;
}

// === POST 処理 ===
$errorMessage = '';
$greeting = '';

if ($method === 'POST') {
    // $_POST から取得(無ければ空文字)
    $name = trim($_POST['name'] ?? '');
    $ageInput = $_POST['age'] ?? '';

    // バリデーション
    if ($name === '') {
        $errorMessage = '名前は必須です';
    } elseif ($ageInput === '' || !ctype_digit($ageInput) || (int)$ageInput < 0) {
        // ctype_digit は「数字だけの文字列か」をチェック
        // "20" -> true, "abc" -> false, "-5" -> false, "3.14" -> false
        $errorMessage = '年齢が不正です';
    } else {
        // セッションに保存
        $_SESSION['last_name'] = $name;
        $_SESSION['last_age'] = (int)$ageInput;
        $greeting = 'こんにちは、' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ' さん!';
    }
}

// テンプレート用に変数を準備
$visitCount = $_SESSION['visit_count'] ?? 0;
$lastName = $_SESSION['last_name'] ?? null;
$lastAge = $_SESSION['last_age'] ?? null;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>S5: スーパーグローバル変数の演習(完全版)</title>
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

    <?php if ($lastAge !== null): ?>
        <p>前回の年齢: <?= $lastAge ?> 歳</p>
    <?php endif; ?>

    <h2>情報を入力してください</h2>
    <form method="POST" action="">
        <p>
            <label>名前: <input type="text" name="name" placeholder="お名前"></label>
        </p>
        <p>
            <label>年齢: <input type="text" name="age" placeholder="20"></label>
        </p>
        <button type="submit">送信</button>
    </form>

    <p>
        <a href="?clear=1">セッションをクリア</a>
    </p>

    <hr>

    <h3>デバッグ情報(中で何が起きているかを見るため)</h3>
    <p>HTTP メソッド: <?= htmlspecialchars($method, ENT_QUOTES, 'UTF-8') ?></p>
    <p>$_GET の中身:</p>
    <pre><?= htmlspecialchars(print_r($_GET, true), ENT_QUOTES, 'UTF-8') ?></pre>
    <p>$_POST の中身:</p>
    <pre><?= htmlspecialchars(print_r($_POST, true), ENT_QUOTES, 'UTF-8') ?></pre>
    <p>$_SESSION の中身:</p>
    <pre><?= htmlspecialchars(print_r($_SESSION, true), ENT_QUOTES, 'UTF-8') ?></pre>
</body>
</html>