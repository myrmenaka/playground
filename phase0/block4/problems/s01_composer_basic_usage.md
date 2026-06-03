# PHP-B4-S1: Composer 公式「Basic Usage」を読む

Composer は PHP の依存管理ツール。Java で言えば Maven / Gradle にあたる。
このBlockの最初は、まず公式ドキュメントで全体像を掴むところから。

## URL

[https://getcomposer.org/doc/01-basic-usage.md](https://getcomposer.org/doc/01-basic-usage.md)

monolog/monolog というログライブラリを題材に、`composer.json` から依存を追加していく流れが解説されている。
英語が読みづらければ内容はほぼ同じなので日本語訳サイトでも可。ただし正典は公式。

## 進め方

15分以内で読む。完璧に理解する必要はない。「こういう仕組みなんだな」が掴めればOK。
実際の操作は S2 以降で手を動かして覚える。

## 読む時に注目するポイント

- `composer.json` の `require` キーに何を書くのか
- パッケージは Packagist という場所から取ってくること
- `composer install` を実行すると `vendor/` フォルダと `composer.lock` が生成されること
- `vendor/autoload.php` を `require` すれば、入れたライブラリが使えること

この4点が掴めていれば次に進める。
Java の Maven で `pom.xml` に依存を書いて `~/.m2` に落ちてくる感覚とほぼ同じ、と思って読むと早い。

## このStepのゴール

Composer が「やっていること」をざっくり言語化できる状態。
細かいコマンドや設定は覚えなくてよい(次Step以降で手を動かす)。