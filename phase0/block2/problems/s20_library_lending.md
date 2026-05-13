# S20: 分解して書く手順を身につける(自力)

## 目的

S19 で「クラスや役割が増えると俯瞰できなくなる」という課題に気付いた。
S20 では、コードを書く時の **分解手順** を意識して書く練習をする。

題材自体はシンプルにし、**手順を踏むこと** に集中する。

## 状況設定

図書館の貸し出し管理を作る。
本は貸し出し中かどうかの状態を持ち、貸し出し・返却ができる。
ただし以下のエラー条件がある:

- 既に貸し出し中の本を貸し出そうとした場合はエラー
- 貸し出されていない本を返却しようとした場合はエラー

## 制約

- カスタム例外: `BookAlreadyBorrowedException`(`\Exception` を継承)
- カスタム例外: `BookNotBorrowedException`(`\Exception` を継承)
- クラス: `Book`
  - プロパティ:
    - `title`(string)
    - `borrowed`(bool、初期値 false)
  - コンストラクタで `title` を受け取る
  - メソッド:
    - `borrow(): void` — 貸し出す
      - 既に貸し出し中なら `BookAlreadyBorrowedException`
      - そうでなければ `borrowed` を true にする
    - `returnBook(): void` — 返却する
      - 貸し出されていないなら `BookNotBorrowedException`
      - そうでなければ `borrowed` を false にする
    - `isBorrowed(): bool` — 現在の状態を返す
    - `getTitle(): string` — タイトルを返す
- `declare(strict_types=1);` を冒頭に書く

## 動作確認(ファイル末尾に書く)

1. `Book` を1冊作成("リーダブルコード")
2. 貸し出す → 成功メッセージ表示、状態を表示
3. 同じ本をもう一度貸し出そうとする → 例外をキャッチして表示
4. 返却する → 成功メッセージ表示、状態を表示
5. もう一度返却しようとする → 例外をキャッチして表示

## 期待される出力例

```
「リーダブルコード」を貸し出しました
貸し出し中: はい
エラー(既に貸出中): 「リーダブルコード」は既に貸し出し中です
「リーダブルコード」を返却しました
貸し出し中: いいえ
エラー(未貸出): 「リーダブルコード」は貸し出されていません
```

## ファイル名

`my_answers/s20_library_lending.php`

---

## 【最重要】今回の進め方

**いきなり PHP のコードを書き始めないでください。**

以下の5ステップを、`my_answers/s20_library_lending.php` の冒頭に **コメント** として書いてから、コードを書き始めてください。

### Step 1. 登場人物を洗い出す

ファイルの冒頭にコメントで、登場するクラスと例外を箇条書きする。

例:
```php
<?php
// === 登場人物 ===
// クラス: XxxClass
// 例外: XxxException, YyyException
```

### Step 2. メソッドの一覧表を作る

クラスごとに、メソッド名・引数・戻り値だけをコメントで列挙する。中身は書かない。

例:
```php
// === Book クラスのメソッド ===
// borrow(): void  ← 例外: BookAlreadyBorrowedException
// returnBook(): void  ← 例外: BookNotBorrowedException
// isBorrowed(): bool
// getTitle(): string
```

### Step 3. 骨組みを空のまま書く

クラスとメソッドを、**中身は空**(または `// TODO` だけ)で書く。
この段階で動かなくて OK。型宣言とシグネチャだけ正しくする。

```php
class Book {
    public function __construct(private string $title) {}
    public function borrow(): void { /* TODO */ }
    public function returnBook(): void { /* TODO */ }
    // ...
}
```

### Step 4. メソッドの中身を1つずつ埋める

簡単なものから書く。`getTitle()` → `isBorrowed()` → `borrow()` → `returnBook()` の順がおすすめ。

### Step 5. 動作確認コードを書く

正常系から書いて、最後に異常系(例外)を書く。

---

## ヒント

<details>
<summary>詰まったら開く: コメントの書き方サンプル</summary>

```php
<?php

declare(strict_types=1);

// ============================================
// Step 1: 登場人物
// ============================================
// クラス: Book
// 例外: BookAlreadyBorrowedException
// 例外: BookNotBorrowedException

// ============================================
// Step 2: メソッド一覧
// ============================================
// Book::__construct(string $title)
// Book::borrow(): void           — 既に借用中なら例外
// Book::returnBook(): void       — 借用されていなければ例外
// Book::isBorrowed(): bool
// Book::getTitle(): string

// ============================================
// Step 3 以降: 実装
// ============================================
```
</details>

<details>
<summary>詰まったら開く: ファイル末尾の動作確認の骨組み</summary>

```php
// 正常系
$book = new Book("リーダブルコード");
$book->borrow();
echo "「{$book->getTitle()}」を貸し出しました" . PHP_EOL;
// ...

// 異常系
try {
    $book->borrow();  // 2回目
} catch (BookAlreadyBorrowedException $e) {
    echo "エラー(既に貸出中): " . $e->getMessage() . PHP_EOL;
}
```
</details>

---

## 完了の目安

- [ ] Step 1〜2 のコメントが書けている(コード前の設計図がある)
- [ ] Step 3 の骨組みが書けている
- [ ] Step 4 で全メソッドが埋まっている
- [ ] Step 5 の動作確認が期待される出力と一致する
- [ ] 「俯瞰できなくなる」感覚が S19 より軽減されたか自己評価する