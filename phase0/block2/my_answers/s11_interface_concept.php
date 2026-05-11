<?php

// =====================================================
// インターフェース練習：ロガーの差し替え可能性を体感する
// =====================================================

// 【規格書】Loggerインターフェース
// 「log()メソッドを持つこと」だけが契約
interface Logger
{
    public function log(string $message): void;
}

// =====================================================
// 【実装①】ファイルに書き出すLogger
// =====================================================
class FileLogger implements Logger
{
    public function log(string $message): void
    {
        // 実際にファイルに書く代わりに、画面に「ファイルに書きました」と表示
        echo "[FileLogger] file.logに書き込み: {$message}" . PHP_EOL;
    }
}

// =====================================================
// 【実装②】DBに書き出すLogger（ふりだけ）
// =====================================================
class DatabaseLogger implements Logger
{
    public function log(string $message): void
    {
        echo "[DatabaseLogger] DBにINSERT: {$message}" . PHP_EOL;
    }
}

// =====================================================
// 【実装③】何もしないLogger（テスト用）
// =====================================================
class NullLogger implements Logger
{
    public function log(string $message): void
    {
        // 何もしない（テスト時にログでテスト結果が汚れないようにする想定）
    }
}

// =====================================================
// 【使う側】OrderController
// ★ ポイント：型はLogger（インターフェース）で受け取る
// ★ 中身が何であっても、このクラスは一切変更不要！
// =====================================================
class OrderController
{
    public function __construct(private Logger $logger)
    {
        // コンストラクタで「Loggerという規格を満たす何か」を受け取る
    }

    public function store(string $orderName): void
    {
        echo "▶ 注文処理を開始: {$orderName}" . PHP_EOL;
        
        // 注文を保存する処理（今回は省略）
        // ...
        
        // ロガーを呼び出す。中身は知らない！
        $this->logger->log("注文が作成されました: {$orderName}");
        
        echo "✓ 注文処理が完了" . PHP_EOL;
        echo "---" . PHP_EOL;
    }
}

// =====================================================
// 【ここから実行】
// 同じOrderControllerに、違うLoggerを差し替えて動かす
// =====================================================

echo "===== ① FileLoggerを使う場合 =====" . PHP_EOL;
$controller1 = new OrderController(new FileLogger());
$controller1->store("ノートPC");

echo "===== ② DatabaseLoggerに差し替え =====" . PHP_EOL;
$controller2 = new OrderController(new DatabaseLogger());
$controller2->store("コーヒー豆");

echo "===== ③ NullLoggerに差し替え（テスト時想定） =====" . PHP_EOL;
$controller3 = new OrderController(new NullLogger());
$controller3->store("ペン");
echo "（↑ NullLoggerは何も出力しないので、log行は表示されない）" . PHP_EOL;

echo PHP_EOL;
echo "===== 👀 ここに注目！ =====" . PHP_EOL;
echo "OrderControllerのコードは1行も変えていないのに、" . PHP_EOL;
echo "ログの出力先を3パターン切り替えられた。" . PHP_EOL;
echo "これがインターフェースの『差し替え可能性』！" . PHP_EOL;