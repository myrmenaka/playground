# S15: AbstractAnimal 抽象クラスの定義

## 目的

PHP で抽象クラスを定義する基本構文を体に染み込ませる。
「共通の実装は親に持たせ、違う部分だけ子クラスに任せる」というパターンを覚える。

## やること

`AbstractAnimal` という抽象クラスを定義する。
- `name` プロパティとコンストラクタは親クラスで持つ(共通)
- `speak()` メソッドは抽象メソッドとして宣言だけする(子クラスに任せる)
- `introduce()` メソッドは具体メソッドとして実装する(共通の自己紹介ロジック)

## 仕様

- クラス名: `AbstractAnimal`
- `abstract class` として宣言する
- プロパティ: `name`(string)
- コンストラクタで `name` を受け取る(コンストラクタプロモーションでOK)
- 抽象メソッド: `speak(): string`(中身は書かない)
- 具体メソッド: `introduce(): string`
  - `"私は {name} です。{speak()の戻り値}"` という文字列を返す
  - `speak()` の結果を含めることで、子クラスの実装に応じて自己紹介が変わる
- `declare(strict_types=1);` を冒頭に書く

## ファイル名

`my_answers/s15_abstract_animal.php`

## 進め方

1. 解答コードを見ながら写経する
2. ファイルを閉じて、何も見ずに再現する
3. `php -l my_answers/s15_abstract_animal.php` で構文チェックする
4. このファイル単体では実行しても何も出力されない(子クラスがないので)。それで正常。
   `php my_answers/s15_abstract_animal.php` を実行してエラーが出なければOK。

## 確認ポイント

- `abstract class` キーワードを書けたか
- 抽象メソッドは本体を書かず、セミコロンで終わっているか
- 具体メソッドはちゃんと本体を持っているか

## 抽象クラスをインスタンス化しようとするとどうなる?

試しに `$animal = new AbstractAnimal('test');` を末尾に書いて実行してみてください:

```
Fatal error: Cannot instantiate abstract class AbstractAnimal
```

抽象クラスは **直接インスタンス化できない**。これがインターフェースと共通の性質。