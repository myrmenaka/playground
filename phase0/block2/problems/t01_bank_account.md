# T1: BankAccount クラス

## 目的

Block 2 で学んだクラスの基本(プロパティ、コンストラクタ、メソッド)に加えて、**例外を投げる** という新しい要素を組み合わせて書けるかを試す。

## 状況設定

ある銀行の口座管理システムを開発しています。
口座には残高があり、入金・出金ができます。ただし、残高不足の状態で出金しようとした場合は **例外を投げて処理を中断** する必要があります。

## 制約

- クラス名: `BankAccount`
- プロパティ: `balance`(残高、int型)
- コンストラクタで初期残高を受け取る(コンストラクタプロモーション推奨)
- 以下の3つのメソッドを実装する:
  - `deposit(int $amount): void` — 入金する。`balance` に加算する
  - `withdraw(int $amount): void` — 出金する。`balance` から減算する。
    ただし、**`amount` が `balance` より大きい場合は例外を投げる**
  - `getBalance(): int` — 現在の残高を返す
- `declare(strict_types=1);` を冒頭に書く
- ファイル末尾で動作確認のコードを書く(後述の出力例を参照)

## 入力

なし(コード内で操作する)

## 出力例

以下の操作を行う:

1. 初期残高 1000円 で口座を作る
2. 500円を入金 → 残高表示
3. 800円を出金 → 残高表示
4. 1000円を出金しようとする → 例外が発生するので catch して、エラーメッセージを表示

```
入金後の残高: 1500円
出金後の残高: 700円
エラー: 残高不足です(残高: 700円、出金額: 1000円)
```

例外メッセージのフォーマット(`残高: XXX円、出金額: YYY円`)は厳密に守ってください。

## ファイル名

`my_answers/t01_bank_account.php`

## 進め方

1. この問題文を保存する
2. `my_answers/t01_bank_account.php` を新規作成
3. **何も見ずに** 書く(S1〜S17 のファイルを開かない)
4. `php my_answers/t01_bank_account.php` で実行確認
5. 出力例と完全に一致するか diff を取る癖を意識する

## ヒント(本当に詰まったら開く)

<details>
<summary>ヒント1: 例外の投げ方</summary>

PHP で例外を投げる構文:

```
throw new \Exception("エラーメッセージ");
```

`\Exception` は PHP の組み込みクラス。Java の `RuntimeException` に近い感覚。
`\` は名前空間のルートを指す(今は深く考えなくてOK、おまじないとして書く)。

</details>

<details>
<summary>ヒント2: try-catch の書き方</summary>

Java とほぼ同じ:

```
try {
    $account->withdraw(1000);
} catch (\Exception $e) {
    echo "エラー: " . $e->getMessage() . PHP_EOL;
}
```

`$e->getMessage()` で例外メッセージを取得できる(Java の `e.getMessage()` と同じ)。

</details>

<details>
<summary>ヒント3: 例外メッセージのフォーマット</summary>

`throw new \Exception(...)` の `...` に渡す文字列で、出金時の `balance` と `amount` を含める必要がある。
S13 で書いた文字列展開 `"{$this->name}"` のテクニックがここで活きる。

</details>

## 確認ポイント

- クラス、プロパティ、コンストラクタを **何も見ずに** 書けたか
- 3つのメソッドのシグネチャ(戻り値の型含む)を正しく書けたか
- `throw new \Exception(...)` の構文を書けたか
- `try-catch` で例外をキャッチして処理できたか
- 出力例と完全に一致するか