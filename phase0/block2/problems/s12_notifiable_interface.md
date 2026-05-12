# S12: Notifiable インターフェースの定義

## 目的

PHP でインターフェースを定義する基本構文を体に染み込ませる。

## やること

`Notifiable` という名前のインターフェースを定義する。
このインターフェースは `notify()` というメソッドを宣言する。

## 仕様

- インターフェース名: `Notifiable`
- メソッド: `notify(): string`(引数なし、戻り値は string)
- メソッドの中身は **書かない**(インターフェースは宣言のみ)

## ファイル名

`my_answers/s12_notifiable_interface.php`

## 進め方

1. 解答コードを見ながら写経する
2. ファイルを閉じて、何も見ずに再現する
3. `php -l my_answers/s12_notifiable_interface.php` で構文チェックする
   (`No syntax errors detected` と出ればOK)

## 確認ポイント

- `class` ではなく `interface` キーワードを使う
- メソッド本体 `{ ... }` は書かず、セミコロン `;` で終える
- 戻り値の型 `: string` を必ず書く