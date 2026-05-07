# S13: foreach でキーと値を同時に取得

連想配列(ユーザー情報)を `foreach` で走査し、キーと値の両方を出力するプログラムを書いてください。

## 進め方

このStepは**写経Step**です。

1. `solutions/s13_foreach_keys.php` を見ながら写経する
2. 動作確認後、ファイルを閉じて何も見ずに再現する
3. 余裕があれば、配列の内容を変えて自分で書いてみる

## 配列

```php
$user = [
    'name' => 'Alice',
    'age' => 25,
    'email' => 'alice@example.com',
    'role' => 'admin',
];
```

## 出力例

```
name: Alice
age: 25
email: alice@example.com
role: admin
```

## ファイル名

`my_answers/s13_foreach_keys.php`