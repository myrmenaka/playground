# PHP-B1-S7: 解説

## 解答コード

```php
<?php

$user = [
    'name' => 'Maru',
    'age' => 28,
    'email' => 'maru@example.com',
];

echo "name: {$user['name']}" . PHP_EOL;
echo "age: {$user['age']}" . PHP_EOL;
echo "email: {$user['email']}" . PHP_EOL;
```

## 解説

### 連想配列とは

PHP の連想配列は、**キー(string か int)と値のペア** で管理する配列。
Java の `HashMap<String, Object>` に相当する。

```php
$user = [
    'name' => 'Maru',
    'age' => 28,
];
```

| 観点 | Java HashMap | PHP 連想配列 |
|------|------------|------------|
| 宣言 | `Map<String, Object> user = new HashMap<>();` | `$user = [];` |
| 値の代入 | `user.put("name", "Maru");` | `$user['name'] = 'Maru';` |
| 値の取得 | `user.get("name")` | `$user['name']` |
| 存在確認 | `user.containsKey("name")` | `array_key_exists('name', $user)` または `isset($user['name'])` |

PHP は構文がシンプルで、`HashMap` よりも気軽に使える。

### キーの種類

PHP の連想配列のキーは **string か int のみ**:

```php
// OK
$arr = [
    'name' => 'Maru',  // string キー
    1 => 'one',        // int キー
];

// NG: float, bool, null, array, object はキーになれない
$arr = [
    1.5 => 'value',    // 1 として扱われる(切り捨て)
    true => 'value',   // 1 として扱われる
];
```

業務では基本 **string キー**を使う。

### キーアクセス

```php
$user['name']     // 'Maru'
$user['unknown']  // Warning: Undefined array key "unknown"、null を返す
```

存在しないキーへのアクセスは警告を出すが、例外にはならない(S6 と同じく PHP らしい寛容さ)。

### ダブルクォート内での配列要素の埋め込み

ダブルクォート文字列の中に配列の要素を埋め込む場合、**波括弧 `{}` で囲む** のが安全:

```php
echo "name: {$user['name']}" . PHP_EOL;  // 推奨
echo "name: $user[name]" . PHP_EOL;       // 動くが非推奨
```

**重要なポイント**:
- 波括弧で囲む場合、キーは **クォートあり**(`'name'` か `"name"`)を推奨
- 波括弧なしの簡易版(`$user[name]`)は、キーをノークォートで書く必要があり、定数との衝突リスクがある

#### ダブルクォートとシングルクォートの混在問題

```php
echo "name: {$user["name"]}";  // 動く(Maru さんの書き方)
echo "name: {$user['name']}";  // こちらが業務でよく見る形
```

文字列の外側がダブルクォートなので、中のキーをシングルクォートにすると視覚的に区別しやすい。
ただし PHP はパース時にちゃんと区別するので、**どちらでも動作的には問題なし**。

## 別解

### 連結演算子を使う

```php
echo "name: " . $user['name'] . PHP_EOL;
echo "age: " . $user['age'] . PHP_EOL;
echo "email: " . $user['email'] . PHP_EOL;
```

連結演算子のほうが「キーは普通の式の一部」として書けるので、エディタの色付けが見やすい場合もある。

### `var_dump` でまとめて表示(デバッグ用)

```php
var_dump($user);
```

実行結果:

```
array(3) {
  ["name"]=> string(4) "Maru"
  ["age"]=> int(28)
  ["email"]=> string(16) "maru@example.com"
}
```

### `foreach` を使った汎用的な書き方(S13 で扱う)

```php
foreach ($user as $key => $value) {
    echo "{$key}: {$value}" . PHP_EOL;
}
```

要素が何個あっても対応できる。実務ではほぼこの形を使う。

## Java との対比

### 連想配列の構造比較

| 観点 | Java HashMap | PHP 連想配列 |
|------|------------|------------|
| 値の型 | ジェネリクスで縛れる | 縛れない(混在可能) |
| 順序 | `LinkedHashMap` を使わないと不定 | **挿入順が保持される**(PHP 7+) |
| null キー | 1つだけ可 | 不可(エラー) |
| パフォーマンス | O(1) アクセス | O(1) アクセス(ハッシュテーブル) |

### 順序が保持される

これは **PHP の連想配列の重要な特徴**。Java の通常の `HashMap` と違い、PHP の連想配列は **追加した順番で並ぶ**:

```php
$user = [
    'name' => 'Maru',
    'age' => 28,
    'email' => 'maru@example.com',
];

// foreach で取り出すと name → age → email の順
```

Java で同じ挙動が欲しければ `LinkedHashMap` を使う必要がある。

## 実務での使われ方

連想配列は PHP/Laravel の業務コードで **超頻出**:

```php
// Eloquent から取得したデータ
$user = User::find(1);
echo $user->name;  // オブジェクトプロパティ

// 配列として取得した場合
$users = User::all()->toArray();
foreach ($users as $user) {
    echo $user['name'];  // 連想配列としてアクセス
}

// バリデーションのルール
$rules = [
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users',
];

// HTTPリクエストパラメータ
$name = request()->input('name');
```

連想配列が読み書きできることは Laravel エンジニアの **必須スキル**。

## つまずきやすいポイント

### 1. キーをクォートで囲み忘れる

```php
$user = [
    name => 'Maru',  // ← name を定数として扱おうとする
];
```

PHP 7.x まではノークォートでも警告だけで動いていたが、**PHP 8 ではエラー**。
キーは必ず `'name'` のようにクォートで囲む。

### 2. アロー演算子と取り違える

```php
$user->name      // オブジェクトのプロパティアクセス(クラスのとき)
$user['name']   // 連想配列のキーアクセス
```

Eloquent のモデルは「オブジェクト」なので `->`、`->toArray()` 後は「配列」なので `[]`。
**同じ「ユーザー名」を取るのに、コンテキストでアクセス方法が違う**ので業務でよく混乱する。

### 3. 存在しないキーにアクセスして気づかない

```php
$user = ['name' => 'Maru'];
echo $user['email'];  // 警告は出るが、出力は空、処理は続行
```

業務では `?? 'デフォルト値'` を併用するのが定番:

```php
echo $user['email'] ?? 'メール未登録';
```

`??` は null 合体演算子(Java の `Optional.orElse` 相当)。

### 4. キーの大文字小文字を間違える

```php
$user = ['name' => 'Maru'];
echo $user['Name'];  // ← 別のキー扱い、警告 + 空出力
```

PHP の配列キーは **大文字小文字を区別する**。ハマりやすいポイント。

## 学習者の実装例

実際にこの問題を解いた際のコード例:

```php
<?php

$user = [
    "name" => "Akane",
    "age" => 33,
    "email" => "akane@example.com"
];

echo "name: {$user["name"]}" . PHP_EOL;
echo "age: {$user["age"]}" . PHP_EOL;
echo "email: {$user["email"]}" . PHP_EOL;
```

→ 連想配列リテラルを正しく使い、波括弧 `{}` で囲んだ変数展開で配列要素を埋め込めている。
ダブルクォート内のキーをダブルクォートで囲む書き方は動作上問題なし。
業務コードではシングルクォートで囲む `{$user['name']}` が多いが、これは好みの範囲。