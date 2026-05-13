# B2-T2 解説: Discountable インターフェース

## このテストの本当の目的

B2-T2 は Block 2 の集大成。表面的には「割引機能を作る」だが、本当に問われているのは以下の3点。

1. **インターフェースを使ってコードを抽象化できるか**
2. **複数のクラスを協調させて書けるか**(S20 の分解手順が活きる場面)
3. **ポリモーフィズムを「実装」だけでなく「使う側」で書けるか**(ボーナス部分)

3点目が最も重要。インターフェースは「定義しただけ」では価値がない。**使う側のコード(foreach)で初めて威力が出る**。

## なぜインターフェースを使うのか

「Member は10%引き、VIP は20%引き」だけなら、インターフェースなしでも書ける。例えば:

```php
// インターフェースなし版
class Member {
    public function applyDiscount(int $price): int {
        return (int)($price * 0.9);
    }
}

class VIP {
    public function applyDiscount(int $price): int {
        return (int)($price * 0.8);
    }
}

// 使う側
$member = new Member();
$vip = new VIP();
echo $member->applyDiscount(1000);  // 動く
echo $vip->applyDiscount(1000);     // 動く
```

これでも動く。**ではなぜインターフェースを使うのか?**

答えは「**配列やコレクションで、異なる型を統一的に扱いたい時**」に効くから。

```php
$discountables = [$member, $vip, $premium, $student];

foreach ($discountables as $d) {
    echo $d->applyDiscount(1000);  // 全部同じように呼べる
}
```

このコードを書くには、`$d` の型が「`applyDiscount` を持っている」と保証されている必要がある。それを保証するのが **インターフェース**。

### Java で例えると

Java では `List<Discountable>` のように、ジェネリクスで型を明示する。

```java
List<Discountable> discountables = Arrays.asList(member, vip);
for (Discountable d : discountables) {
    d.applyDiscount(1000);
}
```

PHP は動的型付けなので、配列の型を厳密に縛れない。代わりに「**インターフェースを実装しているという暗黙の契約**」でコードを書く。

## 型キャストの3つの選択肢

```php
return (int)($price * 0.9);
```

割引計算の戻り値を int にする方法は複数ある。

### 案1: `(int)` キャスト(今回採用)

```php
return (int)($price * 0.9);
```

- 最もシンプル
- **小数点以下を切り捨て**(800.999... → 800)

### 案2: `intval()`

```php
return intval($price * 0.9);
```

- `(int)` キャストと同じ動作
- 関数呼び出しの形式が好きならこちら

### 案3: 整数演算で完結

```php
return $price - intdiv($price * 10, 100);
```

- float を経由しない、厳密な整数演算
- **業務で「絶対に金額を float で扱いたくない」時に使う**
- 浮動小数点の誤差を完全に避けられる

### 業務的にどれが正しい?

**金額の計算は本来 float を使うべきではない**。理由は浮動小数点の誤差。

```php
echo 0.1 + 0.2;  // 0.3 ではなく 0.30000000000000004 が出る
```

業務で本格的にやるなら:

- **`bcmath` 拡張** を使う(任意精度演算)
- **すべて整数で扱う**(例: 円ではなく銭の単位で持つ)
- **`Money` パッケージ** を使う(`moneyphp/money` など)

ただし学習段階では `(int)` キャストで全く問題ない。「**float の誤差は怖い**」という感覚を頭に入れておけば、業務で本気の金額計算が必要な時に思い出せる。

## 切り捨て vs 切り上げ

```php
return (int)($price * 0.9);  // 切り捨て(800.9 → 800)
return (int)ceil($price * 0.9);  // 切り上げ(800.1 → 801)
return (int)round($price * 0.9);  // 四捨五入
```

業務では「**お客様にとって有利な方向に丸める**」のが原則。
割引なら **切り上げ**(より多く引く)、税込価格なら **切り捨て**(より安く)など。

今回は仕様が指定されていないので切り捨てで正解。実務では仕様書に「丸め方法」が書かれていない場合は、必ず確認すること。

## ポリモーフィズムの威力(ボーナス部分)

```php
$discountables = [$member, $vip];

foreach ($discountables as $discountable) {
    echo get_class($discountable) . ": {$discountable->applyDiscount(1000)}円" . PHP_EOL;
}
```

このコードは **インターフェースの真価** を体現している。

### 何がすごいのか

3つの「**しなくて済むこと**」がある:

1. **`if` 文で型を分岐させなくて済む**
```php
   // 悪い例(インターフェースを使わない場合)
   foreach ($users as $user) {
       if ($user instanceof Member) {
           echo $user->applyDiscount(1000);
       } else if ($user instanceof VIP) {
           echo $user->applyDiscount(1000);
       }
       // 新しいランクが増えるたびに if が増える
   }
```

2. **クラスごとに違う処理を書かなくて済む**
   各クラスの `applyDiscount` の中身は違うが、呼び出し側は **同じコード** で済む。

3. **将来クラスが増えても、使う側のコードを変えなくて済む**
   `Premium`、`Student` を追加しても、`foreach` のコードは1文字も変えない。これが **「拡張に対して開いている」(Open/Closed Principle)**。

### Java の比較

Java では「**Liskov の置換原則**」「**多態性(polymorphism)**」として習う概念。
Java Silver で「親の型変数に子のインスタンスを代入できる」と学んだはず。

PHP では型変数の代わりに **配列の中身が動的に違っても呼び出せる** という形で実現される。本質は同じ。

## `get_class()` の使い方と注意点

```php
echo get_class($discountable);  // "Member" or "VIP"
```

オブジェクトのクラス名を取得する組み込み関数。
今回は名前空間を使っていないので短い名前で返るが、業務では名前空間を使うのでフル修飾名が返る:

```php
namespace App\Models;
class Member { ... }

echo get_class(new Member());  // → "App\Models\Member"
```

### 表示用に短い名前だけ欲しい時

```php
// 方法1: ReflectionClass
$shortName = (new \ReflectionClass($discountable))->getShortName();

// 方法2: basename(古典的)
$shortName = basename(str_replace('\\', '/', get_class($discountable)));

// 方法3: PHP 8 の ::class 構文
$className = $discountable::class;  // フル修飾名
```

Phase 1 で Laravel を使い始めると名前空間が出てくるので、その時に思い出すこと。

## つまずきやすい点

### 1. 戻り値の型エラー

```php
public function applyDiscount(int $price): int
{
    return $price * 0.9;  // float が返るので型エラー
}
```

`$price * 0.9` は **float** を返す。戻り値の型宣言が `int` なら型エラーになる。
`(int)` キャストを忘れないこと。

### 2. インターフェースの構文ミス

```php
// よくある間違い
interface Discountable {
    public function applyDiscount(int $price): int { }  // 本体を書いてしまう
}
```

インターフェースのメソッドは **シグネチャだけ** を書く。波括弧の中は書かない。
最後に `;`(セミコロン)を付ける。

### 3. `implements` と `extends` の混同

```php
class Member extends Discountable  // 間違い、Discountable はインターフェース
class Member implements Discountable  // 正解
```

- `extends`: クラス → クラス、インターフェース → インターフェース(複数継承可)
- `implements`: クラス → インターフェース(複数実装可)

### 4. メソッド名やシグネチャの食い違い

```php
interface Discountable {
    public function applyDiscount(int $price): int;
}

class Member implements Discountable {
    public function applyDiscount(int $price): float  // ← 戻り値型が違うとエラー
}
```

インターフェースのメソッドシグネチャと、実装クラスのシグネチャは **完全一致** が必要。
これが PHP 8 以降は厳格にチェックされる。

### 5. ボーナス部分をやらない

ボーナスを省略しても問題は解けるが、**ポリモーフィズムを体感する場面が消える**。
インターフェースの価値を理解する一番のチャンスなので、できる限りやること。

## Java との対比

| 項目 | Java | PHP |
|------|------|-----|
| インターフェース宣言 | `interface Discountable { int applyDiscount(int p); }` | `interface Discountable { public function applyDiscount(int $price): int; }` |
| 実装の構文 | `class Member implements Discountable` | `class Member implements Discountable`(同じ) |
| 複数実装 | カンマ区切りで複数可 | カンマ区切りで複数可(同じ) |
| 型変数 | `List<Discountable>` で型を縛れる | 配列の型は縛れない(慣習で運用) |
| デフォルト実装 | Java 8+ で可能 | PHP インターフェースでは不可(trait を使う) |
| 多態性 | 親型変数に子のインスタンス | 配列の中身がインターフェース実装なら統一的に扱える |

PHP の `interface` は Java とほぼ同じ概念。慣れの問題で、構文に戸惑うことはあっても、考え方は移植できる。

## 完了の目安

- [ ] インターフェース `Discountable` が宣言できている
- [ ] `Member` と `VIP` がそれぞれ `implements Discountable` で実装している
- [ ] `applyDiscount` の戻り値が `int` 型になっている
- [ ] 動作確認の出力が期待値と一致する
- [ ] ボーナスの `foreach` が書けている(ポリモーフィズムの体感)
- [ ] 「将来 `Premium` クラスを追加するなら、どこを変える必要があるか」が答えられる

## 次の Step

Block 2 完了。Phase 0 全体としては Block 3(PHP と Java の差分を体感する)に進む。

Block 2 のサマリーを GitHub にコミットすることを忘れずに。
README.md(block2/、phase0/)の状態を最新化し、Block 2 で身につけたことを振り返るのもおすすめ。

## ふりかえり

Block 2 を通して身につけたこと:

1. クラスの基本構造(プロパティ・コンストラクタ・メソッド)
2. PHP 8 のコンストラクタプロモーション
3. 継承とポリモーフィズム
4. インターフェースと抽象クラス
5. 例外処理(throw / try-catch / カスタム例外)
6. 分解して書く手順(S20 で身につけた型)
7. **複数の概念を組み合わせて、実用的なコードを書く力**(T2)

Block 1 では「PHP の基本構文を書く」が中心だったが、Block 2 では「**業務でそのまま使えるオブジェクト指向のコード**」を書けるようになった。

Phase 1 で Laravel に入った時、ここで学んだ「クラス」「インターフェース」「例外」「分解の手順」がすべて活きる。Laravel のコードを読む時、書く時、両方で土台になる。