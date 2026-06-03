# Block 4: Composer と PSR-4(モダンPHPの基礎)

PHP/Laravel 業務の土台となる **依存管理ツール Composer** と、クラスを自動読み込みする規約 **PSR-4** を、手を動かして身につける。
Block 3 までで「PHP のコードを書く力」が一定レベルに達したことを前提に、**Laravel に入る前に押さえておくと Phase 1 がラクになる要素** に絞る。

Java で言えば **Composer ≒ Maven / Gradle**、`composer.json` ≒ `pom.xml`、`vendor/` ≒ ローカルリポジトリ、PSR-4 ≒ パッケージとディレクトリ構造の対応規約。

## このBlockのゴール

- Composer の役割(依存管理・オートロード)を説明できる
- `composer init` / `composer require` でプロジェクトを構築できる
- `composer.json` と `composer.lock` の違いを説明できる
- PSR-4 オートロードを設定し、`namespace` でクラスを読み込める
- `composer dump-autoload` の意味とタイミングを説明できる
- Block完了テスト(B4-T1)に合格する

## Step 一覧

### Composer の基本

| Step | 種類 | 内容 |
|------|------|------|
| S1 | ドキュメント読み | Composer 公式「Basic Usage」を読む(15分以内) |
| S2 | 操作 | `composer init` で `composer.json` を作る |
| S3 | 操作 | `composer require monolog/monolog` でログライブラリを追加 |
| S4 | 概念 | `composer.json` と `composer.lock` の違いを AI に質問してノートに整理 |

### PSR-4 オートロード

| Step | 種類 | 内容 |
|------|------|------|
| S5 | 写経+再現 | `composer.json` に PSR-4 の autoload 設定を書く |
| S6 | 写経+自力 | `App\Models\User` を作り、別ファイルから使う |
| S7 | 概念 | `composer dump-autoload` の意味と使うタイミングを AI に質問してノートに整理 |

## Block 4 完了テスト

| Test | 内容 |
|------|------|
| B4-T1 | ゼロから Composer プロジェクトを作り、PSR-4 で `App\Sample\Hello` クラスを作って `bin/run.php` から呼び出す総合課題 |

Block 4 が終わったら Phase 0 修了テスト(PHP-FINAL-T1)へ。詰まったら Block 4 を再度繰り返す。

## ディレクトリ構成

```
block4/
├── README.md              ← このファイル
├── problems/              ← 問題文・各Stepの進め方(まずここ)
│   ├── s01_composer_basic_usage.md
│   ├── s02_composer_init.md
│   ├── ...
│   └── t1_psr4_hello.md
├── solutions/             ← 解答コード(.php / composer.json 等)
│   ├── s05_psr4_autoload.json
│   ├── s06_namespace_user.php
│   ├── ...
│   └── docs/              ← 解説Markdown
│       ├── s04_json_vs_lock.md
│       ├── s05_psr4_autoload.md
│       └── ...
└── my_answers/            ← 自分が書いたコード
    ├── s05_psr4_autoload.json
    ├── s06_namespace_user.php
    └── ...
```

なお、Phase 横断の汎用リファレンス(チートシート等)は `~/playground/references/` に置く。

## 進め方の例(Step ごと)

### ドキュメント読みStepの場合(S1)

1. `problems/sXX_xxx.md` で進め方と注目ポイントを確認
2. 提示された URL を時間制限内で読む(完璧な理解は不要、概要把握が目的)
3. 注目ポイントが掴めたら次へ

### 操作Stepの場合(S2、S3)

1. `problems/sXX_xxx.md` の手順を読む
2. ターミナルで実際にコマンドを実行する
3. 生成されたファイル(`composer.json`、`vendor/` など)を自分の目で確認する

### 写経・自力Stepの場合(S5、S6)

1. `problems/sXX_xxx.md` を読む
2. `my_answers/sXX_xxx.php` に書く(写経 → 閉じて再現 → 少し変えて自力)
3. 動かして確認
4. Claude にコードを見せてレビューを受ける
5. レビュー後に `solutions/` と `solutions/docs/` が提示される

### 概念Stepの場合(S4、S7)

1. AI(Claude など)に質問する
2. 自分の言葉でノートアプリに整理する
3. コマンドの実行結果も合わせてメモすると、後で見返した時に思い出しやすい

## Block 3 との違い

- **PHP の外側に出る**: Block 3 までは「PHP の言語仕様」だったが、Block 4 は **プロジェクトをどう組み立てるか** という周辺ツールの世界
- **操作Stepが多め**: コマンドを実際に叩いて、生成物を確認することが中心。写経より「手順の体得」
- **PSR-4 が本命**: S5〜S6 の PSR-4 が Phase 1(Laravel)のディレクトリ構造理解に直結する。ここを丁寧に
- **Laravel への橋渡し**: Laravel は内部で Composer と PSR-4 をフル活用している。ここを理解しておくと「Laravel の `app/Models/User.php` がなぜ動くのか」が腑に落ちる
- **軽めの Block**: 7Step + テスト1個。読む・叩く・メモするの混在型