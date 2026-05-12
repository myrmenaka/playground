# S18: 例外処理

## 目的

PHP の例外処理の基本構文を体に染み込ませる。具体的には:

1. `throw new \Exception(...)` で例外を投げる
2. `try { ... } catch (...) { ... } finally { ... }` で例外を捕まえる
3. PHP 標準の例外クラスを使い分ける(`\InvalidArgumentException` など)
4. カスタム例外クラスを作る

これらは Laravel のコードを読むと頻繁に登場するので、Phase 1 に入る前に押さえておく。

## やること

`UserRegistration` クラスを写経する。
このクラスはユーザー登録処理を表現し、入力値の検証で異なる種類の例外を投げる。
呼び出し側で複数の例外を使い分けて catch する。

## 仕様

- カスタム例外: `EmailAlreadyExistsException`(`\Exception` を継承)
- クラス: `UserRegistration`
  - メソッド: `register(string $email, int $age): void`
    - `$email` が空文字なら `\InvalidArgumentException` を投げる
    - `$age` が0未満または150を超えるなら `\OutOfRangeException` を投げる
    - `$email` が `"existing@example.com"` なら `EmailAlreadyExistsException` を投げる
    - すべて通過したら `"登録完了"` を `echo` する
- ファイル末尾で動作確認:
  - 4回 `register()` を呼び出す(各例外パターン + 正常系)
  - 各回 `try-catch` で囲み、複数の catch 節で例外を使い分ける
  - `finally` 節で「処理を試みました」と毎回出力する

## ファイル名

`my_answers/s18_exception_handling.php`

## 進め方

1. 解答コードを見ながら写経する
2. ファイルを閉じて、何も見ずに再現する
3. `php my_answers/s18_exception_handling.php` で実行する

## 期待される出力

```
エラー(引数不正): メールアドレスは必須です
処理を試みました
---
エラー(範囲外): 年齢は0〜150の範囲で指定してください
処理を試みました
---
エラー(重複): existing@example.com は既に登録されています
処理を試みました
---
登録完了
処理を試みました
---
```

## 確認ポイント

- `throw new \Exception("メッセージ")` の構文
- `try { ... } catch (\Exception $e) { ... }` の構造
- `class EmailAlreadyExistsException extends \Exception` の書き方
- 複数 `catch` 節の書き方(上から順にマッチする)
- `finally` 節は **例外が発生してもしなくても必ず実行される** こと
- 例外クラスごとに違うメッセージを出せること