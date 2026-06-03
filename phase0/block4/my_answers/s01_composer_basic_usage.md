このページはComposer（PHPの依存管理ツール）の基本的な使い方を解説したもので、`composer.json`でのパッケージ指定 → `install`/`update`によるインストール → オートローディングの流れが中心です。要点を以下にまとめます。

## composer.json：プロジェクトの設定

Composerを使い始めるのに必要なのは`composer.json`ファイルだけで、プロジェクトの依存関係を記述します。通常はプロジェクト/VCSリポジトリの最上位ディレクトリに置きます。

最初に指定するのが`require`キーで、どのパッケージに依存するかをComposerに伝えます。`require`は「パッケージ名」と「バージョン制約」を対応づけるオブジェクトです。

```json
{
    "require": {
        "monolog/monolog": "2.0.*"
    }
}
```

**パッケージ名**は「ベンダー名/プロジェクト名」の形式です。ベンダー名は名前の衝突を防ぐために存在します（例：`igorw/json` と `seldaek/json`）。

**バージョン制約**について、上の例の`2.0.*`は「2.0系の任意のバージョン、つまり `>=2.0 <2.1`」を意味します。指定したパッケージは、`repositories`キーで登録したリポジトリか、デフォルトの[Packagist.org](https://packagist.org)から探されます。

なお、dev/alpha/beta/RCなど安定版以外を要求するとstabilityエラーが出ることがあります。デフォルトでは安定版のみが対象になるためです（`minimum-stability`キーで調整可能）。

## 依存関係のインストール

最初に依存関係をインストールするには`update`コマンドを実行します。

```bash
php composer.phar update
```

これは2つのことを行います。一つは`composer.json`の依存をすべて解決し、正確なバージョンを`composer.lock`に書き出してロックすること。もう一つは暗黙的に`install`を実行し、ファイルを`vendor`ディレクトリにダウンロードすることです。

> Gitを使っている場合、`vendor`は`.gitignore`に追加するのが一般的です（サードパーティのコードをリポジトリに含めたくないため）。

## composer.lockをバージョン管理にコミットする

`composer.lock`をコミットすることは重要です。これにより、CIサーバー・本番環境・チームの他の開発者など、全員がまったく同じバージョンの依存関係を使うようになり、一部の環境だけでバグが起きるリスクを減らせます。一人で開発していても、半年後に再インストールした際に依存が同じバージョンで動くという安心感が得られます。

（ライブラリの場合はlockファイルのコミットは不要です。）

## composer.lockからのインストール

プロジェクトフォルダに既に`composer.lock`がある場合、`install`を実行すると`composer.lock`に記載された正確なバージョンがインストールされます。これにより全員が一貫したバージョンを使えますが、最新版になるとは限りません（これは意図的な設計で、予期しない依存変更でプロジェクトが壊れるのを防ぎます）。

```bash
php composer.phar install
```

VCSから変更を取り込んだ後は、`install`を実行してvendorディレクトリを`composer.lock`と同期させることが推奨されます。Composerはデフォルトで再現可能なビルド（reproducible builds）を提供し、同じコマンドを複数回実行すると（タイムスタンプを除き）同一の`vendor/`が生成されます。

## 最新バージョンへの更新

`composer.lock`は自動的な最新化を防ぐので、最新版に上げたいときは`update`を使います。`composer.json`に合致する最新バージョンを取得し、lockファイルを更新します。

```bash
php composer.phar update
```

特定の依存だけを更新・追加・削除したい場合は引数で明示できます。

```bash
php composer.phar update monolog/monolog [...]
```

## Packagist

[Packagist.org](https://packagist.org/)はComposerのメインリポジトリ（パッケージの取得元）です。ここで公開されているパッケージは、場所を指定せずに`require`できます。オープンソースプロジェクトはPackagistへの公開が推奨されますが、Composerで使うために必須ではありません。

## プラットフォームパッケージ

PHP本体や拡張機能など、システムにインストール済みでComposerでは実際にはインストールできないものを表す「仮想パッケージ」です。バージョン制約をかけられます。

- `php` … PHPのバージョン（例：`^7.1`、64bit版は`php-64bit`）
- `hhvm` … HHVMランタイムのバージョン
- `ext-<name>` … PHP拡張（例：`ext-gd`）。バージョンが不揃いなことが多いため制約は`*`が無難
- `lib-<name>` … PHPが使うライブラリ（`curl`, `openssl`, `pcre` など）

`composer show --platform`でローカルで利用可能なプラットフォームパッケージを確認できます。

## オートローディング

オートロード情報を持つライブラリのために、Composerは`vendor/autoload.php`を生成します。これを読み込めば追加作業なしでクラスを使えます。

```php
require __DIR__ . '/vendor/autoload.php';

$log = new Monolog\Logger('name');
$log->pushHandler(new Monolog\Handler\StreamHandler('app.log', Monolog\Logger::WARNING));
$log->warning('Foo');
```

`composer.json`に`autoload`フィールドを追加すれば、自分のコードもオートローダーに登録できます。

```json
{
    "autoload": {
        "psr-4": {"Acme\\": "src/"}
    }
}
```

これは`Acme`名前空間にPSR-4オートローダーを登録します（`src/Foo.php`に`Acme\Foo`クラスを置くイメージ）。`autoload`を追加・変更したら再生成が必要です。

```bash
php composer.phar dump-autoload
```

PSR-4のほか、PSR-0・classmap・filesのオートローディングにも対応しています。

---

`composer install`（lockに従う／本番・CI向け）と`composer update`（最新化／意図的に実行）の使い分けと、`composer.lock`を必ずコミットするという点が、特に押さえておきたいポイント