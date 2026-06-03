<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;

$log = new Logger('my_app');

$log->pushHandler(new StreamHandler(__DIR__ . '/app.log', Level::Warning));

$log->warning('これは警告ログです');
$log->error('これはエラーログです');

$log->info('これは出力されません');

echo 'ログを app.log に書き込みました。中身を確認してください。' . PHP_EOL;