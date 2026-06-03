# 解説: PHP-B4-S3 composer require

## キーポイント

### `composer require` が裏でやっていること

`composer require monolog/monolog` 1コマンドで、Composer は次を自動でやる:

1. **Packagist で `monolog/monolog` を検索**し、依存も含めて適切なバージョンを解決
2. **`composer.json` の `require` に追記**（例: `"monolog/monolog": "^3.10"`）
3. **`vendor/` にダウンロード**（monolog 本体と、依存する `psr/log` も一緒に）
4. **`composer.lock` を生成／更新**（実際に入れた正確なバージョンを記録）
5. **オートローダを再生成**（`vendor/autoload.php` を更新）

つまり「どのライブラリを使うか」を宣言するだけで、ダウンロード・配置・読み込み準備まで全部やってくれる。これがモダン PHP の出発点。

### なぜ `require 'vendor/autoload.php'` の1行で使えるのか

自分では `Logger` クラスのファイルを `require` していないのに使える。
これは Composer が **オートローダ**（クラス名 → ファイルパスの対応表＋自動読み込みの仕組み）を `vendor/autoload.php` にまとめてくれているから。

`Monolog\Logger` を初めて使った瞬間、オートローダが「このクラスはこのファイルにある」と判断して裏で読み込む。
**この仕組みの正体が PSR-4**で、S5 で自分のクラスに対して設定する。今は「ライブラリ側がすでに PSR-4 で配置している」と思っておけばOK。

## ログレベルについて

Monolog は RFC 5424 の8段階のレベルを持つ:

`DEBUG < INFO < NOTICE < WARNING < ERROR < CRITICAL < ALERT < EMERGENCY`

ハンドラに `Level::Warning` を渡すと「Warning 以上だけ記録」になる。
だから解答コードの `info()` は記録されず、`warning()` と `error()` は記録される。

## つまずきやすい点（よくあるミス）

### 1. `vendor/autoload.php` の require を忘れる

```
Fatal error: Uncaught Error: Class "Monolog\Logger" not found
```

→ オートローダを読み込んでいないのが原因。`require __DIR__ . '/vendor/autoload.php';` を冒頭に書く。

### 2. 実行する場所を間違える

`vendor/` があるフォルダから離れた場所で実行すると `__DIR__ . '/vendor/autoload.php'` のパスがずれる。
`composer.json` と同じ階層に PHP ファイルを置いて、その場所で `php s03_logging_test.php` を実行する。

### 3. `vendor/` を Git にコミットしてしまう

`vendor/` は `composer install` でいつでも再生成できるので、Git 管理しない。
`.gitignore` に `/vendor/` を入れるのが定番（playground リポジトリでも忘れず設定する）。

### 4. レベルを大文字定数で書こうとする

monolog 2.x までは `Logger::WARNING` という定数だったが、**3.x からは `Level::Warning`（Enum）** に変わっている。
古い記事のコードをそのままコピペすると動かないことがあるので注意。

## 補足: .gitignore を後から作る

リポジトリを `.gitignore` 無しで作成した場合、このタイミングで追加する。

### 手順1: .gitignore を作る

リポジトリのルート（`.git` がある階層）に `.gitignore` を作成し、最低限これを書く:

```gitignore
# Composer
/vendor/

# 環境設定（今後 Laravel で使う）
.env

# OS / エディタが作るゴミ
.DS_Store
.idea/
*.log
```

ルートから1コマンドで作るなら:

```bash
cat > .gitignore << 'EOF'
/vendor/
.env
.DS_Store
.idea/
*.log
EOF
```

### 手順2: すでに vendor/ をコミットしてしまっていた場合

`.gitignore` は「これから追跡しないファイル」を指定するもので、
**すでに追跡（コミット）済みのファイルには効かない**。

`vendor/` を一度でも push していたら、追跡対象から外す必要がある:

```bash
# Git の追跡からだけ外す（ローカルのファイルは消えない）
git rm -r --cached vendor/

git add .gitignore
git commit -m "Add .gitignore and stop tracking vendor/"
git push
```

`--cached` を付けると **Git の管理対象から外すだけで、ディスク上の `vendor/` は残る**。
ここを間違えて `git rm -r vendor/`（`--cached` なし）にすると実ファイルごと消えるので注意。
消しても `composer install` で復元できるが、無用な操作は避ける。

### 手順3: 追跡されていないか確認

```bash
git status
```

`vendor/` 配下のファイルが一覧に出てこなければ成功。

## つまずきやすい点（.gitignore 編）

- **`*.log` を書くと S3 で作った `app.log` も無視される**。学習の確認用ファイルなので Git に残す必要はなく、無視で問題ない。逆に「ログを成果物として残したい」場合は除外しないこと。
- **`.env` は中身に DB パスワードや API キーが入る前提なので必ず無視する**。Laravel に入ると重要度が上がる。今のうちに習慣づけておく。