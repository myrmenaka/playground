<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

// 'my_app' はチャンネル名（どのログか識別するためのラベル）
$log = new Logger('my_app');

// app.log に Warning 以上のレベルを書き込むハンドラを追加
$log->pushHandler(new StreamHandler(__DIR__ . '/app.log', Level::Warning));

// 実際にログを出してみる
$log->warning('これは警告ログです');
$log->error('これはエラーログです');

// info は Warning より低いレベルなので、上記の設定では書き込まれない
$log->info('これは出力されません');

echo 'ログを app.log に書き込みました。中身を確認してください。' . PHP_EOL;