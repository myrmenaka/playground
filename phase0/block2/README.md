# Block 2: クラスを書けるようになる

クラス定義・継承・インターフェース・抽象クラス・例外処理を、写経と自力Stepで身につける。
Java Silver で OOP の概念は既に押さえているので、**PHP での書き方の差分**を中心に体に染み込ませる。

## このBlockのゴール

- クラス定義(プロパティ、コンストラクタ、メソッド)を何も見ずに書ける
- PHP 8 のコンストラクタプロモーションを使いこなせる
- 継承とポリモーフィズムを PHP で表現できる
- インターフェースと抽象クラスの違いを、コードで示しながら説明できる
- 例外処理(try-catch-finally、カスタム例外)を業務シナリオで書ける
- クラスや役割が複数あるコードを、分解手順を踏んで組み立てられる
- Block完了テスト(B2-T1、B2-T2)に合格する

## Step 一覧

### 簡単なクラス

| Step | 種類 | 内容 |
|------|------|------|
| S1 | 写経 | `User` クラス: プロパティ、コンストラクタ、`introduce()` メソッド |
| S2 | 自力 | 複数の `User` インスタンスを配列に入れて全員 introduce |
| S3 | 写経 | コンストラクタプロモーション(PHP 8)で書き直す |
| S4 | 自力 | getter/setter を持つ `Product` クラス |
| S5 | 自力 | ユーザー5人を作って全員の introduce を呼ぶ |

### 継承

| Step | 種類 | 内容 |
|------|------|------|
| S6 | 自力 | `Animal` クラス(name、`speak()` メソッド) |
| S7 | 自力 | `Dog extends Animal` で `speak()` をオーバーライド |
| S8 | 自力 | `Cat extends Animal` も作る |
| S9 | 自力 | Dog と Cat を配列に入れて全部 `speak()`(ポリモーフィズム) |
| S10 | 写経 | `parent::__construct(...)` で親のコンストラクタを呼ぶ |

### インターフェースと抽象クラス

| Step | 種類 | 内容 |
|------|------|------|
| S11 | 概念 | 「インターフェースとは何か」を AI に質問してノートに整理 |
| S12 | 写経 | `Notifiable` インターフェース(`notify(): string`) |
| S13 | 写経 | `User implements Notifiable` で `notify()` を実装 |
| S14 | 自力 | `Admin implements Notifiable` で違う実装 |
| S15 | 写経 | 抽象クラス `AbstractAnimal`(`speak()` を抽象メソッドに) |
| S16 | 自力 | `Dog extends AbstractAnimal` で `speak()` を実装 |
| S17 | 概念 | 「インターフェースと抽象クラスの違い」をコード例とともにノートに整理 |

### 例外処理

| Step | 種類 | 内容 |
|------|------|------|
| S18 | 写経 | 例外の基本構文(`throw` / `try-catch-finally` / カスタム例外) |
| S19 | 自力 | 在庫管理クラス(`Product` + 複数種類の例外を投げ分ける) |
| S20 | 自力 | 分解して書く手順を身につける(図書館の貸し出し管理) |

## Block 2 完了テスト

| Test | 内容 |
|------|------|
| B2-T1 | `BankAccount` クラス(残高、入金、出金、残高表示。残高不足なら例外) |
| B2-T2 | `Discountable` インターフェース + `Member`(10%引き)/ `VIP`(20%引き) |

両方できたら Block 3 へ。詰まったら Block 2 を再度繰り返す。

## ディレクトリ構成

```
block2/
├── README.md              ← このファイル
├── problems/              ← 問題文(まずここ)
│   ├── s01_user_class.md
│   ├── s02_multiple_users.md
│   └── ...
├── solutions/             ← 解答コード
│   ├── s01_user_class.php
│   ├── s02_multiple_users.php
│   └── docs/              ← 解説Markdown
│       ├── s01_user_class.md
│       ├── s02_multiple_users.md
│       └── ...
└── my_answers/            ← 自分が書いたコード
    ├── s01_user_class.php
    ├── s02_multiple_users.php
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

### 概念Stepの場合(S11、S17)

1. AI(Claude など)に質問する
2. 自分の言葉でノートアプリに整理する
3. コード例も合わせてメモすると、後で見返した時に思い出しやすい

## Block 1 との違い

- **概念は既知前提**: Java Silver 持ちなので、OOP の基本概念は説明をスキップ
- **PHP の差分に集中**: `__construct`、`$this->`、`->`、`extends`、`implements` の構文に慣れる
- **PHP 8 の新機能**: コンストラクタプロモーション、`readonly` プロパティ、Enum などを積極的に活用
- **概念Stepあり**: インターフェース・抽象クラスは「曖昧ポイント」なので、コードを書くだけでなく言語化する Step を挟む
- **例外処理を扱う**: クラスと組み合わせて、業務シナリオで例外を投げ分ける練習を行う