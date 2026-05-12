# S16: Dog クラスで AbstractAnimal を継承する

## 目的

S15 で写経した `AbstractAnimal` を踏まえて、それを継承する具象クラスを **何も見ずに** 書けるかを試す。
抽象メソッドを子クラスで実装する流れを体に染み込ませる。

## 状況設定

ある動物園の管理システムを開発しています。
S15 では `AbstractAnimal` 抽象クラスを定義し、共通の自己紹介ロジック `introduce()` と、子クラスに任せる抽象メソッド `speak()` を用意しました。
今回は、犬を表す `Dog` クラスを作成します。

## 制約

- `AbstractAnimal` 抽象クラスを定義する(S15 と同じ内容を **何も見ずに書き起こす**)
- `Dog` クラスを定義し、`AbstractAnimal` を `extends` する
- `Dog` は抽象メソッド `speak()` を実装し、`"ワン!"` という文字列を返す
- `declare(strict_types=1);` を冒頭に書く
- ファイル末尾で `Dog` をインスタンス化し、以下の2つを実行する:
  - `speak()` の戻り値を `echo` する
  - `introduce()` の戻り値を `echo` する

## 入力

なし(コード内でインスタンスを生成する)

## 出力例

`Dog` のインスタンスを `name="ポチ"` で作って `speak()` と `introduce()` を呼ぶと、以下のように出力されること:

```
ワン!
私は ポチ です。ワン!
```

## ファイル名

`my_answers/s16_dog_extends_abstract_animal.php`

## 進め方

1. この問題文を保存する
2. `my_answers/s16_dog_extends_abstract_animal.php` を新規作成
3. **何も見ずに** 書く(S15 のファイルを開かない)
4. `php my_answers/s16_dog_extends_abstract_animal.php` で実行確認

## ヒント(詰まったら開く)

<details>
<summary>ヒント1: 継承の書き方</summary>

`class Dog extends AbstractAnimal` のように `extends` キーワードを使う。
Java と同じ書き方。

</details>

<details>
<summary>ヒント2: コンストラクタはどうする?</summary>

`Dog` 独自のプロパティを追加しないなら、コンストラクタは **書かなくて良い**。
親クラス `AbstractAnimal` のコンストラクタが自動的に使われる。

つまり `new Dog('ポチ')` と書くと、親の `__construct(string $name)` が呼ばれて `name` プロパティがセットされる。

</details>

<details>
<summary>ヒント3: speak() の実装</summary>

抽象メソッドだったものを、子クラスで本体ありで書く:

```
public function speak(): string
{
    return "ワン!";
}
```

`abstract` キーワードは付けない(もう抽象ではないので)。
シグネチャは親と完全一致させる(`speak(): string`)。

</details>

## 確認ポイント

- `AbstractAnimal` の構造を何も見ずに書けたか
- `class Dog extends AbstractAnimal` の書き方が出てきたか
- `speak()` の実装で `abstract` を **付けない** ことに気付いたか
- 親の `introduce()` を `Dog` のインスタンスから呼べることが体感できたか