# PHP-B3-S6 解説: PHP 8.1 Enum

## キーポイント

### `name` と `value` の違い

| プロパティ | Pure Enum | Backed Enum | 中身 |
|-----------|-----------|-------------|------|
| `->name`  | ✅ 使える  | ✅ 使える    | ケース名の文字列(`'Active'`) |
| `->value` | ❌ ない    | ✅ 使える    | 紐づけたスカラー値(`'active'`) |

Pure Enum で `->value` を書くとエラーになる。値が欲しいなら Backed Enum を使う。

### `Pure Enum` と `Backed Enum` の使い分け

基本Enum（Pure Enum）は「値を保存も送信もしない、プログラムの中だけで状態を区別したいとき」に使います。

Backed Enumは value を持っているので、「DBに保存する」「フォームでやり取りする」「JSONで送る」みたいに外に出す用途で使います。

基本Enumは value を持ちません。だから外に出せない。その代わり、コードの中で「今どの状態か」を型として安全に区別するだけなら、値はいらないわけです。

「この値、DBに保存したりフォームから受け取ったりする予定はあるか？」を考えてみて

- ある → 外に出すから value が要る → Backed Enum
- ない、コードの中だけで完結する → 値はいらない → 基本Enum

#### 「フラグの強化版」みたいなイメージはかなり的確

「フラグ」って普通こういうものですよね。
```
$isActive = true;   // 有効か無効か、2択
```
これはオン/オフの2状態しか表せません。基本Enumが活きるのは、状態が3つ以上あるときです。
```
enum Status
{
    case Active;
    case Inactive;
    case Suspended;   // 「停止中」みたいな第3の状態
}
```
`true`/`false` だと「有効」「無効」しか言えませんが、Enumなら「有効」「無効」「停止中」と、いくらでも状態を増やせる。**真偽値フラグを、3択以上に拡張できるようにしたもの** ——この捉え方なら、まさにフラグの仲間です。

整理すると、

- 2択でいい、オン/オフだけ → 普通に bool（フラグ）で十分
- 3択以上ある、状態に名前を付けたい → 基本Enum（フラグの拡張版）
- その状態をDBに保存・送信もしたい → Backed Enum

**Enumは『状態を表す』という役割ではフラグと同じ仲間。違いは、状態が増やせて、かつ間違えられないこと**

### `from()` と `tryFrom()`

Backed Enum だけが持つ「値 → Enum」の変換メソッド。

```php
// 定義されている値を渡した場合
Status::from('active');     // Status::Active を返す

// 定義されていない値を渡した場合
Status::from('unknown');    // ValueError 例外を投げる
Status::tryFrom('unknown'); // null を返す(例外を投げない)
```

`from()` と `tryFrom()` の引数に入っているのは、**Backed Enumに紐づけた「中身の値（value）」**です。ケースの名前ではなく、= の右側に書いた値のほうです。

DBやフォームから来た「信用できない文字列」を変換するときは、例外で止めたくなければ
`tryFrom()` を使うのが定石。Phase 1 以降、Laravel のリクエスト値を Enum に変換する場面で頻出。

### `cases()`

全ケースを「定義順の配列」で返す静的メソッド。すべての Enum で使える。
ループや一覧生成(プルダウンの選択肢など)で多用する。

### `match` と Enum の相性

`match` は `===`(厳密一致)で比較する。Enum のケースは「同じケースなら同一インスタンス」
なので、`$this === Priority::High` がそのまま使える。`switch` の緩い比較より安全。

---

## Java との対比(教える側向け)

Java の経験者がいちばん混乱するのはここ。「Java の enum とは別物」と一度言い切ると早い。

| 観点 | Java | PHP 8.1 |
|------|------|---------|
| 全ケース取得 | `Status.values()` | `Status::cases()` |
| 名前の取得 | `Status.ACTIVE.name()` | `Status::Active->name` |
| 値からの復元 | `Status.valueOf("ACTIVE")` | `Status::from('active')` / `tryFrom()` |
| メソッド・定数 | 持てる | 持てる |
| インターフェース実装 | できる | できる |
| **インスタンス状態(フィールド)** | **持てる**(コンストラクタで初期化) | **持てない** |

最大の違いは最後の行。Java の enum は「コンストラクタ引数をフィールドに保持できる、ほぼ普通のクラス」。
一方 **PHP の Enum はインスタンスプロパティを持てない**。各ケースは状態を持たない不変のシングルトン。

```php
// ❌ PHP ではこれは書けない(Fatal error)
enum Status
{
    public string $label;   // Enums may not include properties
}
```

「ケースごとに付加情報を持たせたい」ときは、Java のようにフィールドに入れるのではなく、
**`match` を使ったメソッド**(本Stepの `label()` のように)で表現するのが PHP 流。

---

## よくあるミス

1. **Pure Enum で `->value` を呼ぶ** → エラー。値が要るなら型(`: string` など)を付けて Backed Enum にする。
2. **Backed Enum のケース名 = 値 だと思い込む** → `name`(`'Active'`)と `value`(`'active'`)は別物。
3. **`from()` に未知の値を渡して例外で落ちる** → 外部入力には `tryFrom()` を使う。
4. **`match` のケース漏れ** → `match` は網羅していないと `UnhandledMatchError`。Enum と組むときは全ケースを書く(これは「漏れたら実行時に気づける」利点でもある)。

### `value` には string だけでなく int も使える

Backed Enum の型宣言は `: string` だけでなく `: int` も指定できる。

```php
enum Priority: int   // int を value にする
{
    case Low = 1;
    case Medium = 2;
    case High = 3;
}

echo Priority::High->value;   // 3(整数)
```

指定できるのは `string` か `int` のどちらか。両方混在はできない
（`Low = 1` と `Medium = 'mid'` を混ぜると Fatal error）。

DBに「優先度を 1/2/3 の数値で保存している」ような場合は int 型の Backed Enum が便利。
from()/tryFrom() に渡す値も、その型に合わせる（int型なら `Priority::from(3)`）。

### `name` の中身は「ケース名そのまま」

`->name` が返すのは、`case` の後ろに書いた識別子がそのまま文字列になったもの。

```php
enum Status: string
{
    case Active = 'active';   // name は 'Active'、value は 'active'
}

echo Status::Active->name;    // 'Active'(case の後ろの綴りそのまま)
echo Status::Active->value;   // 'active'(= の右側)
```

つまり `name` は自分では決められない（ケース名で固定）。
自由に値を決めたいときは `value`(= の右側)を使う、という関係。

## 別解: インターフェースを実装する Enum

Enum はインターフェースを実装できる。共通の振る舞いを型で保証したいときに使う。

```php
interface HasColor
{
    public function color(): string;
}

enum Priority: int implements HasColor
{
    case Low = 1;
    case High = 3;

    public function color(): string
    {
        return match ($this) {
            Priority::Low  => 'gray',
            Priority::High => 'red',
        };
    }
}
```

---

## 次のステップ

このあとの完了テスト(PHP-B3-T1)では、ここで学んだ Enum を `Product` クラスと組み合わせる。
在庫状態を表す Enum を作り、nullable プロパティ・`declare(strict_types=1)`・`===` 判定と一緒に使う、
Block 3 の総まとめ課題になる。