# PHP-B3-S6: PHP 8.1 Enum

## 目的

PHP 8.1 で導入された Enum を、3つの形で書けるようになる。

1. **基本 Enum(Pure Enum)** — 値を持たない、名前だけの列挙
2. **Backed Enum** — 各ケースに `string` か `int` のスカラー値を紐づける
3. **メソッドを持つ Enum** — `match` 式と組み合わせてラベルや判定ロジックを持たせる

## 進め方(写経 → 再現 → 自力)

1. **写経**: `solutions/s06_enum.php` を見ながら `my_answers/s06_enum.php` に書き写す
2. **再現**: ファイルを閉じて、何も見ずに同じものを書く
3. **自力**: 下の「自力課題」に取り組む

## 実行コマンド

```bash
php my_answers/s06_enum.php
```

### 出力例

```
Hearts
Active
active
Suspended
NULL
Low: 低
Medium: 中
High: 高
高
bool(true)
```

## 押さえるポイント

- `->name` はどのEnumでも使える（ケース名の文字列）
- `->value` は **Backed Enum でのみ** 使える
- `Status::from('active')` と `Status::tryFrom('xxx')` の違い
- `Priority::cases()` で全ケースを配列で取得できる

## 自力課題

写経・再現が終わったら、次の Enum を**何も見ずに**書く。

- `enum Weekday: int`（Backed Enum、`int` 型）を作る
  - `Monday = 1` から `Sunday = 7` まで
- メソッド `isWeekend(): bool` を持たせる
  - 土曜（`Saturday`）と日曜（`Sunday`）のときだけ `true` を返す
- `Weekday::cases()` でループし、各曜日について「曜日名: 平日 / 週末」を出力する

### 出力例

```
Monday: 平日
Tuesday: 平日
Wednesday: 平日
Thursday: 平日
Friday: 平日
Saturday: 週末
Sunday: 週末
```

### ファイル名

`my_answers/s06_weekday.php`