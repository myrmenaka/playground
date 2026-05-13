# Block 3: PHP独自の重要要素

PHP/Laravel 業務で頻繁に出会う、または知らないと詰まる PHP 独自の概念を、写経と自力Stepで身につける。
Block 2 で書く力が一定レベルに達したことを前提に、**Laravel に入る前に押さえておくと Phase 1 がラクになる要素** に絞る。

## このBlockのゴール

- `==` と `===` の違いを5パターン以上のコードで説明できる
- nullable 型 と Union 型 の使い分けができる
- `declare(strict_types=1)` が何を保証しているかを説明できる
- PHP の「リクエストごとプロセスモデル」を説明できる
- スーパーグローバル変数の役割を理解し、Laravel での隠蔽方法を知っている
- PHP 8.1 Enum を使ったコードが書ける
- Block完了テスト(B3-T1)に合格する

## Step 一覧

### 型の挙動

| Step | 種類 | 内容 |
|------|------|------|
| S1 | 写経+自力 | `==` と `===` の違いを5パターンで確認 |
| S2 | 写経+自力 | nullable 型(`?string`)と Union 型(`int\|string`) |
| S3 | 写経+概念 | `declare(strict_types=1)` の有無で型チェックを比較 |

### PHP のリクエストモデル

| Step | 種類 | 内容 |
|------|------|------|
| S4 | 概念 | リクエストごとプロセスモデルを AI に質問してノートに整理 |
| S5 | 写経+自力 | `$_GET`、`$_POST`、`$_SESSION` を使ったフォーム処理 |

### Enum(PHP 8.1+)

| Step | 種類 | 内容 |
|------|------|------|
| S6 | 写経+自力 | 基本 Enum、Backed Enum、メソッドを持つ Enum |

## Block 3 完了テスト

| Test | 内容 |
|------|------|
| B3-T1 | 商品の在庫状態 Enum(`InStock`/`LowStock`/`OutOfStock`)を作り、Product クラスで使う総合課題 |

Block 3 が終わったら Block 4 へ。詰まったら Block 3 を再度繰り返す。

## ディレクトリ構成

```
block3/
├── README.md              ← このファイル
├── problems/              ← 問題文(まずここ)
│   ├── s01_loose_vs_strict.md
│   ├── s02_nullable_union.md
│   └── ...
├── solutions/             ← 解答コード
│   ├── s01_loose_vs_strict.php
│   ├── s02_nullable_union.php
│   └── docs/              ← 解説Markdown
│       ├── s01_loose_vs_strict.md
│       ├── s02_nullable_union.md
│       └── ...
└── my_answers/            ← 自分が書いたコード
    ├── s01_loose_vs_strict.php
    ├── s02_nullable_union.php
    └── ...
```

なお、Phase 横断の汎用リファレンス(チートシート等)は `~/playground/references/` に置く。

## 進め方の例(Step ごと)

### 写経Stepの場合

1. `problems/sXX_xxx.md` を読む
2. `solutions/sXX_xxx.php` を見ながら `my_answers/sXX_xxx.php` に写経
3. 動かす
4. ファイルを閉じて再現

### 自力Stepの場合

1. `problems/sXX_xxx.md` を読む(問題文のみ先に提示される)
2. `my_answers/sXX_xxx.php` を作って自力で書く
3. 動かして期待通りか確認
4. Claude にコードを見せてレビューを受ける
5. レビュー後に `solutions/sXX_xxx.php` と `solutions/docs/sXX_xxx.md` が提示される
6. 必要に応じて自分のコードを修正

### 概念Stepの場合(S3、S4)

1. AI(Claude など)に質問する
2. 自分の言葉でノートアプリに整理する
3. コード例も合わせてメモすると、後で見返した時に思い出しやすい

## Block 2 との違い

- **書く力は前提**: Block 2 までで「クラスを書く力」が身についているので、Block 3 は **PHP 独自の挙動と概念** に集中する
- **業務頻度で取捨選択**: Java との網羅的比較ではなく、業務で頻繁に出会う要素(`==/===`、nullable、Enum など)に絞る
- **概念Stepが多め**: リクエストモデル、`strict_types` など「コードで書くより理解が大事」なものは概念Step で扱う
- **Laravel 入門の準備**: スーパーグローバルを素の PHP で書いておくことで、Phase 1 で Laravel が何を隠蔽しているかが対比で見える
- **軽めの Block**: Block 2 が濃かった分、6Step + テスト1個で構成