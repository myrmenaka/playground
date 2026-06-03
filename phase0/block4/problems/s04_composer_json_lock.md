# S4: composer.json と composer.lock の違い

## このStepの目的

`composer.json` と `composer.lock` の役割の違いを理解し、自分の言葉でノートアプリにまとめる。
S3 で `monolog/monolog` を入れた際に生成された2ファイルを題材にする。

## 進め方(コードは書かない)

1. プロジェクト直下の `composer.json` と `composer.lock` を両方開いて見比べる
2. 以下の問いに自分で答えられるか確認する(分からなければAIに質問)
3. 答えを自分の言葉でノートアプリにまとめる

## 調べる問い

- `composer.json` は誰が書く?何を宣言している?
- `composer.lock` はいつ生成・更新される?何を記録している?
- `composer install` と `composer update` の動作はどう違うか
- なぜ2ファイルに分かれているのか(チーム開発・本番で何を保証するか)
- `composer.lock` は Git にコミットすべきか

## 観察ポイント

S3 で入れた monolog を例に:

- `composer.json` … `"monolog/monolog": "^3.0"` のような**範囲指定**になっているか
- `composer.lock` … `3.x.y` のような**確定バージョン**と、直接書いていない**間接依存**まで記録されているか

## 完了条件

上記の問いに答える形で、ノートアプリにまとめられたらOK。