# PHP-B3-T1 解説: 商品の在庫状態管理

## キーポイント

### 1. nullable プロパティで「未設定」を表現
`private ?StockStatus $status = null;` の `?` が nullable。
「まだ判定していない状態」を null で表せる。Java の `StockStatus status = null;`
と同じ発想だが、PHP は `?` を明示しないと null を代入できない（strict_types 下）。

### 2. `match` は内部で `===` 比較している
```php
match ($this->status) {
    StockStatus::OutOfStock => '在庫切れ',
}
```
これは `$this->status === StockStatus::OutOfStock` と同義。
Enum の case はシングルトンなので `===` で同一性比較できる（Java enum の `==` と同じ）。
`if + ===` で書くより `match` の方が網羅的で読みやすい。

### 3. 境界値に注意（1〜5 と 6以上）
「5」がどちらに入るかは `<` か `<=` かで変わる。
仕様「1〜5 は LowStock」なら `<= 5`。境界値の取りこぼしは実務頻出バグ。

### 4. コンストラクタプロモーションの可視性
```php
public function __construct(
    public string $name,      // → public プロパティ $name を生成
    protected int $quantity,  // → protected プロパティ $quantity を生成
)
```
引数に付けた修飾子が、そのままプロパティの可視性になる。
コンストラクタ自体の `public` とは別物。外から `$obj->name` したいものだけ public に。

## よくあるミス
- `< 5` と書いて 5 を取りこぼす（境界値バグ）
- InStock と LowStock で文言フォーマットを揃えてしまう（仕様の差異を見落とす）
- `?` を付け忘れて null 代入で TypeError

## Java との対比
| | Java | PHP |
|---|---|---|
| enum 比較 | `==` | `===`（match 含む） |
| nullable | 参照型は元々 null 可 | `?Type` を明示 |
| switch網羅 | switch式 | match式（未網羅は UnhandledMatchError） |