<?php

declare(strict_types=1);

// ============================================
// Step 1: 登場人物
// ============================================
// クラス: Book
// 例外: BookAlreadyBorrowedException(既に貸し出し中の本を貸そうとした)
// 例外: BookNotBorrowedException(貸し出されていない本を返そうとした)

// ============================================
// Step 2: メソッド一覧
// ============================================
// Book::__construct(string $title)
// Book::borrow(): void           — 既に借用中なら BookAlreadyBorrowedException
// Book::returnBook(): void       — 借用されていなければ BookNotBorrowedException
// Book::isBorrowed(): bool
// Book::getTitle(): string

// ============================================
// Step 3 以降: 実装
// ============================================

class BookAlreadyBorrowedException extends \Exception {}
class BookNotBorrowedException extends \Exception {}

class Book
{
    private bool $borrowed = false;

    public function __construct(
        private string $title,
    ) {
    }

    public function borrow(): void
    {
        if ($this->borrowed) {
            throw new BookAlreadyBorrowedException("「{$this->title}」は既に貸し出し中です");
        }
        $this->borrowed = true;
    }

    public function returnBook(): void
    {
        if (!$this->borrowed) {
            throw new BookNotBorrowedException("「{$this->title}」は貸し出されていません");
        }
        $this->borrowed = false;
    }

    public function isBorrowed(): bool
    {
        return $this->borrowed;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}

// ============================================
// 動作確認
// ============================================
// 「成功する前提」のコードは try-catch で囲まず、
// 「例外が飛ぶ前提」のコードだけ try-catch で囲む。

$book = new Book("リーダブルコード");

// 1回目の貸し出し(成功する前提)
$book->borrow();
$status = $book->isBorrowed() ? 'はい' : 'いいえ';
echo "「{$book->getTitle()}」を貸し出しました" . PHP_EOL;
echo "貸し出し中: {$status}" . PHP_EOL;

// 2回目の貸し出し(失敗する前提)
try {
    $book->borrow();
} catch (BookAlreadyBorrowedException $e) {
    echo "エラー(既に貸出中): {$e->getMessage()}" . PHP_EOL;
}

// 1回目の返却(成功する前提)
$book->returnBook();
$status = $book->isBorrowed() ? 'はい' : 'いいえ';
echo "「{$book->getTitle()}」を返却しました" . PHP_EOL;
echo "貸し出し中: {$status}" . PHP_EOL;

// 2回目の返却(失敗する前提)
try {
    $book->returnBook();
} catch (BookNotBorrowedException $e) {
    echo "エラー(未貸出): {$e->getMessage()}" . PHP_EOL;
}