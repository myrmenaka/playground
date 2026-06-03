# PHP-B4-S2: composer init で composer.json を作る

## ゴール

`composer init` を対話形式で実行し、`composer.json` をゼロから生成する。

## 手順

1. 練習用フォルダを作って移動する

```bash
mkdir -p ~/playground/phase0/block4/my_answers/s02_composer
cd ~/playground/phase0/block4/my_answers/s02_composer
```

2. 対話形式で composer init を実行する

```bash
composer init
```

3. 各プロンプトに以下のように答える(`[]` 内はデフォルト値。Enter で採用)

- **Package name** (`<vendor>/<name>`): `myrmenaka/composer-practice`
- **Description**: `Composer init practice`
- **Author**: git config から自動補完されたらそのまま Enter(なければ `名前 <メール>` を入力、不要なら `n`)
- **Minimum Stability**: 空のまま Enter(= stable)
- **Package Type**: `project`
- **License**: `MIT`
- **define dependencies (require) interactively?**: `no`
- **define dev dependencies interactively?**: `no`
- **Add PSR-4 autoload mapping?**: `n`(オートロードは S5 で手書きする)
- **Do you confirm generation?**: `yes`

4. 生成結果を確認する

```bash
cat composer.json
```

## 確認ポイント

- `composer.json` が生成されているか
- `name` / `type` / `license` が意図どおりか