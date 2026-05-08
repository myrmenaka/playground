# S05 解説: ユーザーの配列を作って一括処理

## ポイント

### 1. オブジェクトを配列に入れる

PHP の配列は何でも入ります(プリミティブ、オブジェクト、配列)。
Java の `List<User>` のように「ユーザーだけが入る配列」を型で縛ることは、プリミティブには簡単にはできませんが、PHPDoc で表現する習慣があります:

```php
/** @var User[] $users */
$users = [...];
```

実務(Laravel)ではこの PHPDoc が IDE の補完を効かせるために重要になります。今は「そういう書き方がある」と知っておけば十分です。

### 2. `introduce()` は「返す」か「出力する」か

```php
// パターン A: 返す(今回のコード)
public function introduce(): string
{
    return "私は{$this->name}です。{$this->age}歳です。";
}
echo $user->introduce() . PHP_EOL;

// パターン B: 出力する
public function introduce(): void
{
    echo "私は{$this->name}です。{$this->age}歳です。\n";
}
$user->introduce();
```

実務では **パターン A(返す)が推奨** です。理由:

- メソッドが「データを作る」責務だけ持ち、「どこに出力するか(画面・ファイル・テスト)」は呼び出し側に委ねられる
- 単体テストで戻り値を `assertEquals` で検証しやすい
- ビュー(Blade)に渡すこともできる

「出力するメソッドではなく、返すメソッドを書く」は良いコード設計の基本原則の一つです。

### 3. `foreach` のネーミング慣習

```php
foreach ($users as $user) { ... }   // ✅ 複数形 → 単数形
foreach ($items as $item) { ... }   // ✅
foreach ($posts as $post) { ... }   // ✅
foreach ($users as $u) { ... }      // ❌ 略しすぎ
```

Laravel のドキュメントやサンプルもこの形式で統一されています。

## Java との対比

```java
// Java
List<User> users = List.of(
    new User("田中", 25),
    new User("鈴木", 30)
);
for (User user : users) {
    System.out.println(user.introduce());
}
```

```php
// PHP
$users = [
    new User('田中', 25),
    new User('鈴木', 30),
];
foreach ($users as $user) {
    echo $user->introduce() . PHP_EOL;
}
```

ほぼ同じ構造です。違いは:

| 項目 | Java | PHP |
|------|------|-----|
| 型宣言 | `List<User>` | (PHPDoc で表現) |
| 拡張for | `for (User user : users)` | `foreach ($users as $user)` |
| 出力 | `System.out.println()` | `echo ... . PHP_EOL` |

## 別解: `array_map` を使う書き方

`foreach` で `echo` する代わりに、`array_map` で全員の自己紹介文を作って一気に出力することもできます:

```php
$introductions = array_map(
    fn(User $user) => $user->introduce(),
    $users
);
echo implode(PHP_EOL, $introductions) . PHP_EOL;
```

- `fn(...) => ...` はアロー関数(PHP 7.4+)。Java のラムダ `user -> user.introduce()` と同じ感覚
- `array_map` は Java の `stream().map()` 相当

今は `foreach` で書ければ十分。`array_map` は B3(PHPとJavaの差分)でまた登場します。

## `\n` と `PHP_EOL` の違い

- `"\n"` — LF(Linux/Mac の改行)
- `"\r\n"` — CRLF(Windows の改行)
- `PHP_EOL` — 実行環境の改行コードに合わせて自動切り替え

CLI で動かすだけなら `"\n"` で十分ですが、ファイル出力など環境に依存する処理では `PHP_EOL` が安全です。

## つまずきやすい点

### 配列の中に末尾カンマ

```php
$users = [
    new User('田中', 25),
    new User('鈴木', 30),  // ← この末尾カンマはOK
];
```

PHP 7.3 以降、配列リテラルの末尾カンマは許容されています。
git diff で「行追加だけ」になるので、実務で推奨されています。