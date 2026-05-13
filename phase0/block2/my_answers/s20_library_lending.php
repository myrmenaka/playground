<?php

declare(strict_types=1);

// 例外：BookAlreadyBorrowedException：既に貸し出されている場合の例外
class BookAlreadyBorrowedException extends \Exception {}
// 例外：BookNotBorrowedException：貸し出されていない場合の例外
class BookNotBorrowedException extends \Exception {}

// クラス：Book
class Book
{
    // プロパティ：bool borrowed = false：貸し出し状態を管理
    private bool $borrowed = false;

    // プロパティ：string title：コンストラクタ：本のタイトル
    public function __construct(
        private string $title
    ) {
    }

    // メソッド：borrow()：void：貸し出す
    public function borrow(): void
    {
        // 貸し出し中：BookAlreadyBorrowedExceptionをスロー
        if ($this->borrowed === true) { // if ($this->borrowed)
            throw new BookAlreadyBorrowedException("「{$this->title}」は既に貸し出し中です");
        }
        // 貸し出し成功：borrowedをtrueにする
        $this->borrowed = true;
    }
    
    // メソッド：returnBook()：void：返却する
    public function returnBook(): void
    {
        // 貸し出し中でない：BookNotBorrowedExceptionをスロー
        if ($this->borrowed === false) { // if (!$this->borrowed)
            throw new BookNotBorrowedException("「{$this->title}」は貸し出されていません");
        }
        // 返却成功：borrowedをfalseにする
        $this->borrowed = false;
    }
    
    // メソッド：isBorrowed()：bool：現在の状態を返す
    public function isBorrowed(): bool
    {
        return $this->borrowed;
    }

    // メソッド：getTitle()：string：タイトルを返す
    public function getTitle(): string
    {
        return $this->title;
    }
}

// Bookを作成：リーダブルコード
$book = new Book("リーダブルコード");
// 貸し出す→成功メッセージ・状態を表示
$book->borrow();
echo "「{$book->getTitle()}」を貸し出しました" . PHP_EOL;
echo "貸し出し中: " . ($book->isBorrowed() ? 'はい' : 'いいえ') . PHP_EOL;
// 同じ本をもう一度貸し出す→例外をキャッチしてエラーメッセージを表示
try {
    $book->borrow();
    echo "「{$book->getTitle()}」を貸し出しました" . PHP_EOL;
    echo "貸し出し中: " . ($book->isBorrowed() ? 'はい' : 'いいえ') . PHP_EOL;
} catch (BookAlreadyBorrowedException $e) {
    echo "エラー(既に貸し出し中): {$e->getMessage()}" . PHP_EOL;
}

// 返却する→成功メッセージ・状態を表示
$book->returnBook();
echo "「{$book->getTitle()}」を返却しました" . PHP_EOL;
echo "貸し出し中: " . ($book->isBorrowed() ? 'はい' : 'いいえ') . PHP_EOL;
// もう一度返却する→例外をキャッチしてエラーメッセージを表示
try {
    $book->returnBook();
    echo "「{$book->getTitle()}」を返却しました" . PHP_EOL;
    echo "貸し出し中: " . ($book->isBorrowed() ? 'はい' : 'いいえ') . PHP_EOL;
} catch (BookNotBorrowedException $e) {
    echo "エラー(未貸出): {$e->getMessage()}" . PHP_EOL;
}