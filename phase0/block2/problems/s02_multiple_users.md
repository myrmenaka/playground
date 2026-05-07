# S02: 複数のUserインスタンスを扱う

S01 で定義した `User` クラスを使って、**3人のユーザーをインスタンス化し、配列に入れて、全員の自己紹介を出力する**プログラムを書いてください。

## 制約

- ファイル先頭で `declare(strict_types=1);` を宣言すること
- `User` クラスを再定義する(S01 と同じ内容でOK、コピペ可)
- 3人分の `User` インスタンスを作り、配列に入れること
- `foreach` で配列をループし、全員の `introduce()` を呼んで出力すること
- ユーザーの名前と年齢は自由に決めて構わない

## 入力

なし

## 出力例

```
私の名前はAlice、年齢は25歳です。
私の名前はBob、年齢は30歳です。
私の名前はCharlie、年齢は28歳です。
```

## ファイル名

`my_answers/s02_multiple_users.php`

## ヒント

<details>
<summary>クリックで表示</summary>

- `User` クラスは S01 と同じものをそのまま使う
- インスタンス化は `new User('名前', 年齢)`
- 配列に入れる: `$users = [new User(...), new User(...), new User(...)];`
- ループ: `foreach ($users as $user) { echo $user->introduce() . PHP_EOL; }`
- T2 で書いた「リスト → 個別オブジェクト → フィールド」の構造を思い出すと、今回は「個別オブジェクトが連想配列ではなく User インスタンス」になっているだけ

</details>