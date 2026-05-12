# S14: Admin クラスで Notifiable を実装する

## 目的

S13 で写経した `User implements Notifiable` を踏まえて、別のクラスを **何も見ずに** 書けるかを試す。
同じインターフェースでも実装は自由に変えられる、というポリモーフィズムの本質を体感する。

## 状況設定

ある通知システムを開発しています。
S13 では `User` クラスが `Notifiable` インターフェースを実装し、通知メッセージを返すようにしました。
今回は、管理者向けに別の実装を持つ `Admin` クラスを作成します。

## 制約

- `Notifiable` インターフェースを定義する(`notify(): string` メソッドのみ)
- `Admin` クラスを定義し、`Notifiable` を `implements` する
- `Admin` は以下を満たす:
  - プロパティ: `name`(string)、`department`(string)
  - コンストラクタで `name` と `department` を受け取る(コンストラクタプロモーション推奨)
  - `notify(): string` メソッドを実装し、後述の形式の文字列を返す
- `declare(strict_types=1);` を冒頭に書く
- ファイル末尾で `Admin` をインスタンス化し、`notify()` の戻り値を `echo` する

## 入力

なし(コード内でインスタンスを生成する)

## 出力例

`Admin` のインスタンスを `name="鈴木花子"`、`department="営業部"` で作って `notify()` を呼ぶと、以下のように出力されること:

```
【管理者通知】営業部 の 鈴木花子 さんへ通知を送信しました
```

## ファイル名

`my_answers/s14_admin_implements_notifiable.php`

## 確認ポイント

- `Notifiable` インターフェースを何も見ずに書けたか
- `class Admin implements Notifiable` の書き方が出てきたか
- `notify(): string` のシグネチャがインターフェースと一致しているか
- コンストラクタプロモーションで2つのプロパティを受け取れたか

## ヒント(詰まったら開く)

<details>
<summary>ヒント1: クラスの骨組み</summary>

S13 の `User` クラスとほぼ同じ構造です。プロパティが1つ増えるだけ。

</details>

<details>
<summary>ヒント2: 文字列展開で2つのプロパティを使う</summary>

`"{$this->name}"` のように `{$...}` で囲むのは S13 と同じ。
2つ使うときも同じ書き方を2回使うだけです:
`"{$this->department} の {$this->name} さん"`

</details>