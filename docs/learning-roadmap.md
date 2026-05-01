# PHP/Laravel エンジニア学習ロードマップ(改訂版 v2.0)

## 学習者プロフィール

- 未経験からプログラマーとして入社、現在2か月半経過
- 所属: AI促進部署(AIを活用して業務遂行)
- 業務: フルスタック、PHP/Laravelが主、AI(Claude Code)で設計から実装
- 目標: AIの成果物を評価できるエンジニア。AIの有無に左右されない市場価値を持つ
- 保有資格: Java Silver SE17

## 学習者の現状(レベル測定結果)

| 領域 | レベル | 補足 |
|------|--------|------|
| 概念理解(OOP等) | 中の上 | インターフェース等一部曖昧 |
| コードリーディング | 中の上 | 与えられたコードは読める |
| **コードライティング** | **初級の下** | **ゼロから自分で書けない(最重要課題)** |
| HTML/CSS/JS | チートシート参照で書ける | 実装力は要訓練 |
| SQL | チートシート参照で書ける | 実装力は要訓練 |
| HTTP/Web | 自信なし | 体系的理解が必要 |
| PHP/Laravel業務 | AIに依頼してCRUD・認証・Docker構築済 | 出力を完全理解できていない |
| Git | 基本操作 | チームでのブランチ運用は要確認 |
| Linux/CLI | 業務常用 | OK |
| デプロイ/本番 | 未経験 | 後半フェーズで習得 |

## このロードマップの特徴

**最重要課題: 「読めるけど書けない」状態の解消**

レベル測定で判明した最大の課題は、コードリーディング力に対してライティング力が極端に低いこと。
このため、本ロードマップは以下の方針で組まれている。

1. **読む時間を最小化、書く時間を最大化**
2. **概念解説は既知前提でスキップ、ピンポイント補強のみ**
3. **小さなコードを毎日書く習慣の形成**
4. **「写経 → 思い出して書く → 少し変えて書く」の反復**

## 学習の運用ルール

1. **学習用ディレクトリ**: `~/learning/phaseX-テーマ/` でPhaseごとに作る
2. **Git管理**: 学習用フォルダもGitHubにプライベートリポジトリで管理
3. **学習ログ**: 1日5分、`learning-log.md` に「今日書いたコード」「詰まった点」を記録
4. **継続最優先**: 完璧主義は禁物。1日1Stepでも続けることが最重要
5. **Phase完了時に進捗を振り返り**、次Phaseの内容を調整

## ゴールの3階層(常に意識する)

1. **コードレベル**: AIが出したコードのバグ・セキュリティ・パフォーマンス・可読性を指摘できる
2. **設計レベル**: AIが提案した設計が要件・将来の拡張・チーム規模に対して適切か判断できる
3. **要件レベル**: AIに渡している要件自体が正しいかをビジネス的に判断できる

## 期間の目安

合計6〜10ヶ月を想定。Phase 0は最重要なので時間をかけて良い。

| Phase | 内容 | 目安期間 |
|-------|------|---------|
| Phase 0 | 書く力をつける(最重要) | 1〜2ヶ月 |
| Phase 1 | PHP/Laravel基礎を実装力で習得 | 1〜2ヶ月 |
| Phase 2 | Laravel実践(Eloquent、認証等) | 1〜1.5ヶ月 |
| Phase 3 | 弱点補強(SQL、HTTP、セキュリティ) | 2ヶ月 |
| Phase 4 | 評価力の核(テスト、設計、リファクタリング) | 2ヶ月 |
| Phase 5 | 上級(DDD、インフラ、デプロイ) | 1〜2ヶ月 |

---

# Phase 0: 書く力をつける(最重要)

## このPhaseの目的

「読めるけど書けない」状態を解消する。**毎日コードを書く習慣を作り、ゼロから自分でコードを組み立てられるようになる**。

## Phaseのゴール

1. PHPの基本構文(変数、配列、ループ、条件分岐、関数)を**何も見ずに書ける**
2. 簡単なクラスを**何も見ずに書ける**(プロパティ、コンストラクタ、メソッド)
3. 与えられた要件(20〜30行程度)を、ヒントなしでコードに落とせる
4. インターフェースと抽象クラスの違いを、コードで示しながら説明できる
5. PHPとJavaの主要な差分を10個以上挙げられる

## 学習方針

- **書籍は使わない**(読む時間を最小化)
- PHP公式マニュアルとAIをサブとして参照
- 全Stepで「写経 → 1分後に何も見ずに再現 → 少し変えて書く」の3段階を実施
- 詰まったら答えを見てOK、ただし見た後に必ず「何も見ずに再現」する

## 環境準備

- **PHP-W0-S1**: PHP 8.2以上をローカルにインストール(Mac: `brew install php`、Windows: XAMPP)
- **PHP-W0-S2**: Composerをインストール、`composer --version` で確認
- **PHP-W0-S3**: VSCodeに「PHP Intelephense」拡張をインストール
- **PHP-W0-S4**: `~/learning/phase0-writing/` フォルダ作成
- **PHP-W0-S5**: 学習用GitHubリポジトリ作成、初回プッシュ

## Block 1: PHPの基本構文を体に染み込ませる

各Stepの進め方:
1. お題を見る
2. 写経用のコードを見ながら一度書く
3. ファイルを閉じて、何も見ずにもう一度書く
4. AIに採点してもらう

### 変数と出力

- **PHP-B1-S1**: `Hello, World!` を `echo` で出力(写経→再現)
- **PHP-B1-S2**: 変数を3つ宣言(string、int、bool)し、`var_dump` で出力(写経→再現)
- **PHP-B1-S3**: 自分の名前と年齢を変数に入れて、`echo` で「私は○○です。○○歳です。」と出力(自力)
- **PHP-B1-S4**: 上のコードをヒアドキュメント(`<<<EOT`)で書き直す(写経→再現)
- **PHP-B1-S5**: 文字列の中に変数を埋め込む3パターン(`.`連結、`""`内展開、`sprintf`)を全て書く

### 配列

- **PHP-B1-S6**: インデックス配列(数値5個)を作って、全部 `echo` で出力(自力)
- **PHP-B1-S7**: 連想配列(name、age、email)を作って、全部 `echo` で出力(自力)
- **PHP-B1-S8**: 連想配列の配列(ユーザー3人分)を作って、全員の name を表示(写経→再現)
- **PHP-B1-S9**: 配列に値を追加する3パターン(`[]=`、`array_push`、`+=`)を全て書く
- **PHP-B1-S10**: 配列から値を削除する2パターン(`unset`、`array_pop`)を書く

### 制御構文

- **PHP-B1-S11**: 1〜10を `for` で出力(自力)
- **PHP-B1-S12**: 配列を `foreach` で全部出力(自力)
- **PHP-B1-S13**: 配列を `foreach` で「キーと値」セットで出力(写経→再現)
- **PHP-B1-S14**: 数値を受け取って偶数なら「偶数」、奇数なら「奇数」と出力する `if` 文(自力)
- **PHP-B1-S15**: 上を `match` 式で書き直す(PHP 8の練習)

### 関数

- **PHP-B1-S16**: 2つの数を足して返す関数 `add($a, $b)`(自力)
- **PHP-B1-S17**: 上の関数に型宣言を追加: `function add(int $a, int $b): int`(写経→再現)
- **PHP-B1-S18**: 配列を受け取り、合計を返す関数 `sum(array $nums): int`(自力)
- **PHP-B1-S19**: 配列を受け取り、偶数だけの配列を返す関数(自力)
- **PHP-B1-S20**: 上の関数を `array_filter` を使って書き直す(写経→再現)

### Block 1 完了テスト

- **PHP-B1-T1**: 「整数の配列を受け取り、その中の偶数の合計を返す関数を書いてください」を**何も見ずに**書く。書けたらAIにレビュー依頼
- **PHP-B1-T2**: 「ユーザー(name、age)の連想配列の配列を受け取り、20歳以上のユーザーのnameだけを配列で返す関数を書いてください」を**何も見ずに**書く

両方できたらBlock 2へ。詰まったらBlock 1を再度繰り返す。

## Block 2: クラスを書けるようになる

### 簡単なクラス

- **PHP-B2-S1**: `User` クラス: name、ageプロパティ、コンストラクタ、`introduce()` メソッド(写経→再現→自力)
- **PHP-B2-S2**: 上のクラスをインスタンス化して `introduce()` を呼ぶ(自力)
- **PHP-B2-S3**: PHP 8のコンストラクタプロモーションで同じクラスを書き直す(写経→再現)
- **PHP-B2-S4**: getter/setter を持つ `Product` クラス: name、price、`getName()`、`setPrice()`(自力)
- **PHP-B2-S5**: 配列でユーザーを5人作って、全員の introduce を呼ぶ(自力)

### 継承

- **PHP-B2-S6**: `Animal` クラス(name、`speak()` メソッド)(自力)
- **PHP-B2-S7**: `Dog extends Animal` で `speak()` をオーバーライド(自力)
- **PHP-B2-S8**: `Cat extends Animal` も作る(自力)
- **PHP-B2-S9**: 配列に Dog と Cat を入れて `foreach` で全部 `speak()` を呼ぶ(ポリモーフィズムの体験)
- **PHP-B2-S10**: `parent::` で子クラスのコンストラクタから親のコンストラクタを呼ぶ(写経→再現)

### インターフェースと抽象クラス(あなたの曖昧ポイント)

- **PHP-B2-S11**: 「**インターフェースとは何か**」をAIに質問して、自分の言葉で `learning-log.md` に書き直す
- **PHP-B2-S12**: `Notifiable` インターフェース(`notify(): string` メソッドのみ宣言)(写経→再現)
- **PHP-B2-S13**: `User implements Notifiable` で `notify()` を実装(写経→再現)
- **PHP-B2-S14**: 別のクラス `Admin implements Notifiable` で違う実装(自力)
- **PHP-B2-S15**: 抽象クラス `AbstractAnimal`(`speak()` を抽象メソッドとして宣言)(写経→再現)
- **PHP-B2-S16**: `Dog extends AbstractAnimal` で `speak()` を実装(自力)
- **PHP-B2-S17**: 「**インターフェースと抽象クラスの違い**」をコード例とともに `learning-log.md` に書く

### Block 2 完了テスト

- **PHP-B2-T1**: 「`BankAccount` クラスを書いてください。残高プロパティ、入金メソッド、出金メソッド(残高不足なら例外を投げる)、残高表示メソッドを持ちます」を**何も見ずに**書く
- **PHP-B2-T2**: 「`Discountable` インターフェース(`applyDiscount(int $price): int` を持つ)を作り、それを実装する `Member` クラス(10%引き)と `VIP` クラス(20%引き)を作ってください」を**何も見ずに**書く

両方できたらBlock 3へ。

## Block 3: PHPとJavaの差分を体感する

Javaで書いたら「こうなる」というコードを、PHPで書いて差分を体で覚える。

### 配列の違い

- **PHP-B3-S1**: Javaの `ArrayList<String>` 相当のことをPHPの配列でやる(可変長、追加、削除)
- **PHP-B3-S2**: Javaの `HashMap<String, Integer>` 相当のことをPHPの連想配列でやる
- **PHP-B3-S3**: Javaの `for-each` ループに対応するPHPの `foreach` をkey付きで書く
- **PHP-B3-S4**: 配列の操作10個を、Javaなら何を使うか・PHPなら何を使うかの対応表を `learning-log.md` に書く

### 型システムの違い

- **PHP-B3-S5**: PHPの `==` と `===` の違いを5パターンの比較で確認するコードを書く
- **PHP-B3-S6**: PHP 8の Union 型(`int|string`)を使った関数(写経→再現)
- **PHP-B3-S7**: PHPの `null` 許容型(`?string`)を使った関数(写経→再現)
- **PHP-B3-S8**: `declare(strict_types=1)` を使ったファイルと使わないファイルで型チェックの動作を比較

### PHP特有の概念

- **PHP-B3-S9**: PHPの「リクエストごとにプロセスが立ち上がる」モデルについてAIに質問して、自分の言葉でメモ
- **PHP-B3-S10**: スーパーグローバル変数(`$_GET`、`$_POST`、`$_SESSION`)を使った簡単なフォーム処理(写経→再現)
- **PHP-B3-S11**: PHP 8 の `match` 式と Java の switch文 を比較するメモを書く
- **PHP-B3-S12**: PHP 8.1 の Enum を使ったコード(写経→再現)

### Block 3 完了テスト

- **PHP-B3-T1**: 「PHPとJavaの主な違いを10個」を `learning-log.md` に箇条書きで書く

## Block 4: Composer と PSR-4(モダンPHPの基礎)

### Composer の基本

- **PHP-B4-S1**: Composer公式ドキュメントの「Basic Usage」を読む(15分以内)
- **PHP-B4-S2**: 新規フォルダで `composer init` を実行して `composer.json` を作る
- **PHP-B4-S3**: `composer require monolog/monolog` でログライブラリを追加してみる
- **PHP-B4-S4**: `composer.json` と `composer.lock` の違いをAIに質問してメモ

### PSR-4 オートロード

- **PHP-B4-S5**: `composer.json` に PSR-4 のautoload設定を書く(写経→再現)
- **PHP-B4-S6**: `App\Models\User` を `app/Models/User.php` に作って、別ファイルから使う
- **PHP-B4-S7**: `composer dump-autoload` の意味と使うタイミングをAIに質問してメモ

### Block 4 完了テスト

- **PHP-B4-T1**: ゼロから新しいフォルダを作り、Composerプロジェクトを作成、PSR-4で `App\Sample\Hello` クラスを作って、`bin/run.php` から呼び出して動かす(全部自力)

## Phase 0 修了テスト

これが全部できたら Phase 1 に進める。詰まったら詰まったStepを繰り返す。

- **PHP-FINAL-T1**: 「ユーザー管理システムを書いてください。User クラス(name、email、age)、UserRepository クラス(配列でユーザーを管理、追加・全件取得・name で検索メソッド)を作って、5人のユーザーを追加して全員表示、特定のユーザーを検索して表示、するコードを書いてください」を**何も見ずに**書く

これができたら、「書ける人」の入口に立っている。

---

# Phase 1: PHP/Laravel基礎を実装力で習得

## このPhaseの目的

Phase 0 で身につけた「書く力」を Laravel の文脈に応用する。Laravel の主要機能を「使える」状態にする。

## Phaseのゴール

1. Laravelプロジェクトをゼロから作成・起動できる
2. ルート→コントローラ→ビューの基本フローを**何も見ずに**書ける
3. Bladeテンプレートで条件分岐、ループ、レイアウト継承を**何も見ずに**書ける
4. `php artisan` の主要コマンド10個以上を使いこなせる
5. 簡単なフォーム処理(GET/POST、CSRF、バリデーション)を**何も見ずに**書ける

## 使う教材

- Laravel公式ドキュメント(日本語版): https://readouble.com/laravel/
- 必要に応じて『PHP本格入門 上巻』(参考書として手元に)

## Block 1: Laravel プロジェクトの基本

- **L1-B1-S1**: Laravel公式の「インストール」セクションを読む
- **L1-B1-S2**: `composer create-project laravel/laravel learning-blog` で新規プロジェクト作成
- **L1-B1-S3**: `.env` の DB を SQLite に設定(軽量で学習向き)
- **L1-B1-S4**: `php artisan serve` で起動確認
- **L1-B1-S5**: Laravel公式の「ディレクトリ構造」を読む(15分以内)
- **L1-B1-S6**: `app/`、`config/`、`routes/`、`database/`、`resources/` に何があるかメモ
- **L1-B1-S7**: 写経 - `routes/web.php` に `/hello` ルートを追加、テキストを返す
- **L1-B1-S8**: 自力 - `routes/web.php` に `/about`、`/contact` ルートを追加
- **L1-B1-S9**: 写経 - `php artisan make:controller HelloController` でコントローラ作成、メソッドからテキストを返す
- **L1-B1-S10**: 自力 - `AboutController` を作って、ルートからコントローラを呼ぶように変更

## Block 2: Bladeテンプレート

- **L1-B2-S1**: Laravel公式の「Bladeテンプレート」を読む(30分以内)
- **L1-B2-S2**: 写経 - 簡単なBladeビュー(`hello.blade.php`)を作って、コントローラから返す
- **L1-B2-S3**: 自力 - 変数を渡して `{{ }}` で表示するBladeを書く
- **L1-B2-S4**: 写経 - `@if`、`@else`、`@elseif` を使ったBlade
- **L1-B2-S5**: 自力 - `@foreach` で配列を表示するBlade
- **L1-B2-S6**: 写経 - レイアウト継承(`layouts/app.blade.php` を作り、`@extends`、`@section`、`@yield`)
- **L1-B2-S7**: 自力 - 共通のヘッダー・フッター・ナビを持つレイアウトを作って、3ページに適用
- **L1-B2-S8**: 「`{{ }}` と `{!! !!}` の違い」をAIに質問、XSSの観点でメモ

## Block 3: フォーム処理

- **L1-B3-S1**: Laravel公式の「リクエスト」「レスポンス」を読む
- **L1-B3-S2**: 写経 - GET `/contact` でフォーム表示、POST `/contact` で受け取り
- **L1-B3-S3**: 自力 - `@csrf` の意味を理解、外したらどうなるか試す
- **L1-B3-S4**: Laravel公式の「バリデーション」を読む(30分以内)
- **L1-B3-S5**: 写経 - `$request->validate(...)` で簡単なバリデーション
- **L1-B3-S6**: 自力 - お問い合わせフォーム(name必須、email形式、message10文字以上)を完成させる
- **L1-B3-S7**: 写経 - バリデーションエラーをBladeで表示
- **L1-B3-S8**: 自力 - `old()` で入力値を保持

## Block 4: artisanコマンドとマイグレーション

- **L1-B4-S1**: `php artisan list` で全コマンドを眺める
- **L1-B4-S2**: 写経 - `make:controller`、`make:model`、`make:migration` を全て試す
- **L1-B4-S3**: 自力 - `tasks` テーブル(id、title、description、is_done、timestamps)のマイグレーションを書く
- **L1-B4-S4**: `php artisan migrate` で実行、SQLite ファイルを確認
- **L1-B4-S5**: 写経 - `make:seeder` でSeederを作って、5件のダミーデータ投入
- **L1-B4-S6**: 自力 - `tinker` で `\DB::table('tasks')->get()` を実行
- **L1-B4-S7**: `migrate:rollback`、`migrate:fresh` を試す

## Phase 1 修了テスト

- **L1-FINAL-T1**: ゼロから新規Laravelプロジェクトを作って、以下を**何も見ずに**実装
  - `/posts` で投稿一覧表示(DB から取得)
  - `/posts/create` で投稿作成フォーム
  - POST `/posts` で保存
  - バリデーション(タイトル必須、本文10文字以上)
  - レイアウト継承を使ったBlade

---

# Phase 2: Laravel実践

## Phaseのゴール

1. Eloquentで基本的なCRUDと主要クエリ(where、orderBy、whereHasなど)が書ける
2. リレーション(hasOne、hasMany、belongsTo、belongsToMany)を理解し使える
3. N+1問題を Debugbar で検知し、`with()` で解消できる
4. FormRequest を使ったバリデーションが書ける
5. Laravel Breeze で認証機能を実装できる

## Block 1: Eloquent基礎

- **L2-B1-S1**: Laravel公式「Eloquent: 入門」を読む
- **L2-B1-S2**: 写経 - Task モデルで `create`、`find`、`first`、`get`、`update`、`delete`
- **L2-B1-S3**: 自力 - 全タスクを取得して表示、特定IDのタスクを取得して表示
- **L2-B1-S4**: 写経 - `where`、`orWhere`、`whereIn`、`whereBetween`
- **L2-B1-S5**: 自力 - 「is_done が false のタスクだけ取得」「タイトルに『買い物』を含むタスクだけ取得」
- **L2-B1-S6**: 写経 - `orderBy`、`limit`、ページネーション(`paginate`)
- **L2-B1-S7**: 自力 - 集約関数(`count`、`sum`、`avg`)を使う
- **L2-B1-S8**: `Laravel Debugbar` をインストール、各操作で発行されるSQLを確認
- **L2-B1-S9**: 自力 - 簡単なブログアプリのCRUD(投稿一覧、詳細、作成、編集、削除)

## Block 2: Eloquentリレーション

- **L2-B2-S1**: Laravel公式「Eloquent: リレーション」を読む(30分以内)
- **L2-B2-S2**: 写経 - `users` `posts` `comments` テーブルを作成
- **L2-B2-S3**: 写経 - User hasMany Post、Post belongsTo User
- **L2-B2-S4**: 自力 - 特定ユーザーの投稿一覧を取得して表示
- **L2-B2-S5**: 写経 - Post hasMany Comment、Comment belongsTo Post
- **L2-B2-S6**: 写経 - Post belongsToMany Tag(中間テーブル含む)
- **L2-B2-S7**: 自力 - リレーションを使って「特定タグを持つ投稿一覧」を取得

## Block 3: N+1問題とEager Loading

- **L2-B3-S1**: 自力 - ユーザー10人、各5投稿、各投稿に3コメントのテストデータを Seeder で作成
- **L2-B3-S2**: 写経 - 全投稿一覧で「著者名」と「コメント数」を表示するBlade
- **L2-B3-S3**: Debugbar で発行SQLの数を確認(N+1が起きているはず)
- **L2-B3-S4**: 写経 - `with(['user', 'comments'])` で Eager Loading
- **L2-B3-S5**: SQL数が減ったことを確認
- **L2-B3-S6**: 自力 - `withCount('comments')` でコメント数を取得
- **L2-B3-S7**: 自力 - `whereHas` で「コメントが3件以上ある投稿」を取得

## Block 4: バリデーションとFormRequest

- **L2-B4-S1**: Laravel公式「バリデーション」全体を読む(30分以内)
- **L2-B4-S2**: 写経 - `php artisan make:request StorePostRequest` で FormRequest 作成
- **L2-B4-S3**: 自力 - 投稿作成のバリデーションを FormRequest に移行
- **L2-B4-S4**: 写経 - 主要ルール10種類(`required`、`string`、`max`、`unique`、`email`、`exists`、`confirmed`、`regex`、`in`、`date`)
- **L2-B4-S5**: 自力 - ユーザー登録フォーム(name、email、password、password_confirmation、age)のフルバリデーション
- **L2-B4-S6**: 写経 - エラーメッセージを日本語にカスタマイズ

## Block 5: 認証(Laravel Breeze)

- **L2-B5-S1**: Laravel公式「Laravel Breeze」を読む
- **L2-B5-S2**: `composer require laravel/breeze --dev`、`php artisan breeze:install`
- **L2-B5-S3**: 登録、ログイン、ログアウトの動作確認
- **L2-B5-S4**: 生成されたコントローラ・ビュー・ルートを30分かけて読む
- **L2-B5-S5**: 自力 - `auth` ミドルウェアで保護されたページを作る
- **L2-B5-S6**: 自力 - ログイン中のユーザーだけが自分の投稿を編集・削除できる権限制御

## Phase 2 修了テスト

- **L2-FINAL-T1**: 自力でブログアプリを完成させる
  - ユーザー登録・ログイン
  - 自分の投稿のみ編集・削除可能
  - 投稿一覧、詳細、コメント機能
  - バリデーション
  - N+1対策

---

# Phase 3: 弱点補強(SQL、HTTP、セキュリティ)

## Phaseのゴール

1. SQLをチートシートなしで書ける(主要クエリとJOIN)
2. HTTPの仕組みを体系的に説明できる
3. SQLインジェクション、XSS、CSRFがそれぞれ何で、Laravelがどう防いでいるかを説明できる
4. AIが出力したコードを見て、セキュリティ的に危険な書き方を指摘できる

## Block 1: SQL深掘り

**使う教材**: 『SQL第2版 ゼロからはじめるデータベース操作』(ミック)

- **SQL-B1-S1**: 書籍の Chapter 1〜3 を読む(SELECT、WHERE、ORDER BY)
- **SQL-B1-S2〜S15**: SQLite環境で書籍の演習問題を全て解く(写経禁止、自力で書く)
- **SQL-B1-S16**: 書籍の Chapter 4〜5 を読む(集約関数、サブクエリ)
- **SQL-B1-S17〜S25**: 同様に演習
- **SQL-B1-S26**: 書籍の Chapter 6〜7 を読む(JOIN、複数テーブル)
- **SQL-B1-S27〜S35**: 同様に演習
- **SQL-B1-T1**: 「2つのテーブルから JOIN して条件付きで取得する」クエリを5パターン、何も見ずに書く

**使う教材**: 『達人に学ぶDB設計徹底指南書』(ミック)

- **SQL-B2-S1〜S10**: 正規化、インデックスの基礎を学ぶ(各章ごとにメモを書く)
- **SQL-B2-T1**: 自分の Laravel プロジェクトのテーブル設計を見直し、正規化の観点で改善案を出す

## Block 2: HTTPとWebの仕組み

**使う教材**: 『Webを支える技術』(山本陽平、技術評論社)

- **WEB-B1-S1〜S10**: 章ごとに読む、各章でAIに質問して理解を深める
- **WEB-B1-T1**: 「HTTPメソッド(GET/POST/PUT/PATCH/DELETE)の使い分け」を自分の言葉で説明
- **WEB-B1-T2**: 「ステータスコード200番台、300番台、400番台、500番台」の違いを説明
- **WEB-B1-T3**: 「Cookieとセッションの違い」を実装の観点で説明
- **WEB-B1-T4**: 「RESTful API の設計原則」で `/users/{id}/posts` のようなエンドポイントを5パターン設計

## Block 3: セキュリティ

**使う教材**: 『体系的に学ぶ 安全なWebアプリケーションの作り方 第2版』(徳丸浩)

- **SEC-B1-S1〜S20**: 章ごとに読む、Laravelプロジェクトで攻撃が成立する例 → 防御を実装する練習
- **SEC-B1-T1**: SQLインジェクション、XSS、CSRFそれぞれを「攻撃が成立するコード」と「Laravelの防御機構」とともに `learning-log.md` に書く
- **SEC-B1-T2**: AIに「セキュリティ的に問題のあるLaravelコード」を書かせて、自分で問題点を全て指摘

---

# Phase 4: 評価力の核(テスト、設計、リファクタリング)

## Phaseのゴール

1. PHPUnitでユニットテストとFeatureテストが書ける
2. テスト駆動開発の基本サイクルを実践できる
3. 良いコードと悪いコードの違いを言語化できる
4. AIが出力したコードに対して可読性・保守性の観点で具体的な改善提案ができる
5. レイヤードアーキテクチャを Laravel 上で実装できる

## Block 1: テスト

**使う教材**: Laravel公式「テスト」、『テスト駆動開発』(Kent Beck、和田卓人訳)

- **TEST-B1-S1〜S10**: PHPUnit の基本、Laravel のテスト機能(Feature、Unit)を写経で習得
- **TEST-B1-S11〜S20**: 自力で既存のブログアプリのテストを書く
- **TEST-B1-T1**: 「TDD で簡単な機能(例: タグ付け機能)を実装する」を実践

## Block 2: コード品質

**使う教材**: 『リーダブルコード』、『良いコード/悪いコードで学ぶ設計入門』(ミノ駆動本)

- **CODE-B1-S1〜S15**: 書籍を読みながら、過去に書いた自分のコードをリファクタリング
- **CODE-B1-T1**: AIに「悪いコード」を書かせて、自分で改善版を書く演習を5回

## Block 3: 設計

**使う教材**: 『達人プログラマー 第2版』、『Clean Architecture』

- **ARCH-B1-S1〜S15**: 書籍を読みながら、Laravelでレイヤードアーキテクチャを実装
- **ARCH-B1-T1**: 自分のブログアプリを、コントローラ・サービス・リポジトリの3層に分けてリファクタリング

---

# Phase 5: 上級(DDD、インフラ、デプロイ)

## Phaseのゴール

1. ドメイン駆動設計の基本概念を実装で示せる
2. Docker でローカル開発環境を構築・運用できる
3. AWS の主要サービスを使って Laravel アプリをデプロイできる
4. 1つの他言語(Go か TypeScript)で簡単なAPIが書ける

## Block 1: DDD入門

**使う教材**: 『ドメイン駆動設計入門』(成瀬本)、『現場で役立つシステム設計の原則』(増田亨)

- **DDD-B1-S1〜S15**: 書籍を読みながら、エンティティ、値オブジェクト、リポジトリを Laravel で実装
- **DDD-B1-T1**: 簡単な業務ドメイン(例: タスク管理、在庫管理)をモデリングして実装

## Block 2: インフラとデプロイ

**使う教材**: 『Docker/Kubernetes実践コンテナ開発入門』、AWS 公式ハンズオン

- **INF-B1-S1〜S15**: Docker の基礎、Linux の基本コマンド、AWS の主要サービス(EC2、RDS、S3)
- **INF-B1-T1**: 自分の Laravel アプリを AWS にデプロイ

## Block 3: もう1つの言語

- **LANG-B1-S1〜S15**: Go か TypeScript で簡単な REST API を書く
- **LANG-B1-T1**: PHP/Laravel との違いを `learning-log.md` に10個以上書く

---

## ロードマップ完了時の到達点

- AIが出力したコードを、コード・SQL・セキュリティ・設計の観点でレビューでき、具体的な改善提案ができる
- 自分でゼロから中規模のWebアプリを設計・実装できる(AIなしでも)
- AIに対して、技術的に的確で具体的な指示が出せる
- 他言語にも展開できる土台ができている

「AIの成果物を評価できるエンジニア」のスタートラインに立っている状態。

---

## 改訂履歴

- v1.0 (初版): 未経験者向けに作成
- **v2.0 (本版)**: Java Silver SE17保持者かつ「読めるけど書けない」状態に対応。Phase 0 を新設し、書く力をつけることを最優先に。
