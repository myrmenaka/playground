# S13: User クラスで Notifiable を実装する

## 目的

インターフェースを `implements` して、契約通りにメソッドを実装する流れを体に染み込ませる。

## やること

S12 で作った `Notifiable` インターフェースを `implements` した `User` クラスを作る。
インスタンスを作って `notify()` を呼び出し、結果を出力する。

## 仕様

- クラス名: `User`
- `Notifiable` インターフェースを実装する
- プロパティ: `name`(string)
- コンストラクタで `name` を受け取る(コンストラクタプロモーションでOK)
- `notify(): string` メソッドを実装し、`"{name} さんに通知を送りました"` という文字列を返す
- ファイル末尾で `User` をインスタンス化し、`notify()` の戻り値を `echo` する

## ファイル名

`my_answers/s13_user_implements_notifiable.php`

## 進め方

1. S12 の `Notifiable` インターフェースは同じファイル内に書いてOK
   (本来は別ファイルに分けて require するが、Composer のオートロードを学ぶ Block 4 まではこの形で進める)
2. 解答コードを見ながら写経する
3. ファイルを閉じて、何も見ずに再現する
4. `php my_answers/s13_user_implements_notifiable.php` で実行する

## 期待される出力

```
山田太郎 さんに通知を送りました
```

(名前は任意でOK)

## 確認ポイント

- `class User implements Notifiable` の書き方
- `notify()` の戻り値の型 `: string` がインターフェースと一致しているか
- 文字列展開で `{$this->name}` の書き方