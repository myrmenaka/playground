# 解説: composer init

## composer init とは

`composer.json`(プロジェクトの設定ファイル)を対話形式で生成するコマンド。
手で一から書いてもいいが、最初は init に質問してもらうと、書くべき項目を取りこぼさずに済む。

## composer.json の役割

`composer.json` は、そのプロジェクトが「何という名前で」「どんな依存ライブラリを必要としていて」「どう動かすか」を宣言するファイル。
プロジェクトのルートに 1 つ置く。これがあることで `composer install` / `composer require` などのコマンドが意味を持つようになる。

## 各項目の意味

| 項目 | 意味 |
|------|------|
| name | `vendor/package` 形式の識別子。**すべて小文字**。ハイフン区切り。Packagist(PHPのパッケージ公開サイト)で公開するときの名前になる |
| description | パッケージの説明文。一行で何のプロジェクトかを書く |
| type | `library`(他のプロジェクトから使われる部品)/ `project`(アプリ本体)。今回はアプリ側なので project |
| license | ライセンス。配布しないなら `proprietary` でも可。学習用なら `MIT` で十分 |
| minimum-stability | 受け入れる最低の安定度。通常は空(= `stable`)でよい。`dev` や `beta` を許可したいときだけ変える |
| authors | 作者情報。**必須ではない**。なくても動く |
| require | 本番で使う依存ライブラリ。今回は空。次の S3 で `composer require` を実行すると、ここに自動で書き込まれる |

## キーポイント

### name は小文字とハイフンのみ

`name` は `vendor/package` の形で、両方とも小文字。大文字やスペースを入れるとエラーになる。
`myrmenaka/composer-practice` のように、自分の名前(vendor)とプロジェクト名(package)をスラッシュでつなぐ。

### type を project にする理由

`project` は「これはアプリ本体であって、他から部品として require される側ではない」という宣言。
逆に、再利用される部品(自作ライブラリなど)を作るときは `library` を選ぶ。
今回は学習用のアプリを作っていくので `project`。

### require が空でも正しい

「依存ライブラリをまだ何も入れていない」だけなので、`require: {}` は正常な状態。
S3 で `composer require monolog/monolog` を実行すると、ここに `"monolog/monolog": "^3.x"` のような行が自動で追加される。

## つまずきやすい点

- **name に大文字を使う** → エラーになる。`MyRmenaka/...` は不可。小文字とハイフンのみ。
- **PSR-4 autoload をこの init の段階で設定してしまう** → 今回は `n`(設定しない)で OK。S5 で `composer.json` に手書きして、オートロードの仕組みを自分の手で理解する。init に任せると中身を読まずに進んでしまうため、あえて後回しにしている。
- **authors に本物のメールを入れて public リポジトリに上げてしまう** → composer.json はそのまま公開されるので、メールアドレスもそのまま見える。気になるなら authors ブロックは削除してよい(必須項目ではない)。

## 別解: 対話なしで一気に作る

`composer init` には対話を飛ばすオプションもある。慣れてきたらこちらが速い。

```bash
composer init \
  --name="myrmenaka/composer-practice" \
  --description="Composer init practice" \
  --type="project" \
  --license="MIT" \
  --no-interaction
```

`--no-interaction` を付けると質問されずにデフォルト+指定値で生成される。
ただし学習段階では、対話で 1 項目ずつ意味を考えながら進めるほうが身につく。