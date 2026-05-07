# S01: User クラスの定義とインスタンス化

`User` クラスを定義し、インスタンスを作って自己紹介させてください。

## 進め方

このStepは**写経Step**です。

1. `solutions/s01_user_class.php` を見ながら写経する
2. 動作確認後、ファイルを閉じて何も見ずに再現する
3. 余裕があれば、プロパティを1つ追加(例: `email`)して `introduce()` の出力に含めてみる

## 仕様

- ファイル先頭で `declare(strict_types=1);` を宣言する
- `User` クラスを定義する
- プロパティ
  - `name`(`string`、`private`)
  - `age`(`int`、`private`)
- コンストラクタで `name` と `age` を受け取り、プロパティに設定する
- `introduce()` メソッドを定義し、「私の名前は◯◯、年齢は◯歳です。」という文字列を返す
  - 戻り値の型は `string`
- インスタンスを1つ作り、`introduce()` を呼んで結果を `echo` で出力する

## 出力例

```
私の名前はAlice、年齢は25歳です。
```

## ファイル名

`my_answers/s01_user_class.php`