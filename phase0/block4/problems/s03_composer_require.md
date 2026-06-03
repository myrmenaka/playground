# PHP-B4-S3: composer require でライブラリを追加する

## 目的

外部ライブラリ（Monolog: ログ出力ライブラリ）を Composer 経由で追加し、
`composer require` が何を生成・変更するのかを自分の目で確認する。
そのうえで、追加したライブラリを `vendor/autoload.php` 経由で実際に呼び出す。

## やること

S2 で `composer init` を実行したフォルダ（`composer.json` がある場所）で作業する。

### 手順1: ライブラリを追加

```bash
composer require monolog/monolog
```

実行後、フォルダ内で次の3点がどう変化したか確認する:

1. `composer.json` … `require` セクションに `monolog/monolog` が追加されたか
2. `composer.lock` … 新しく生成されたか（中身は見るだけでOK）
3. `vendor/` … ディレクトリが生成され、中に `autoload.php` があるか

### 手順2: Monolog を実際に使う

`solutions/s03_logging_test.php` を参考に、ログを1〜2行出力するコードを書いて動かす。
ログがファイル（例: `app.log`）に書き込まれることを確認する。

## 確認ポイント

- `require` の前後で `composer.json` の差分が説明できるか
- なぜ `require __DIR__ . '/vendor/autoload.php';` の1行だけで `Monolog\Logger` が使えるのか
- `composer.lock` は何のために存在するのか（→ 次の S4 で深掘り）

## メモ

理解したことはノートアプリに記録しておく。