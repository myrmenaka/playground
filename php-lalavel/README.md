# PHP/Laravel 学習記録

未経験から「AIの成果物を評価できるエンジニア」を目指す学習ログ。

## 現在地

- **Phase**: Phase 0(書く力をつける)
- **Block**: Block 1(PHPの基本構文を体に染み込ませる)
- **直近完了Step**: -
- **次のStep**: PHP-W0-S1(環境準備)

最終更新: YYYY-MM-DD

## 進捗

| Phase | 内容 | 進捗 | 状態 |
|-------|------|------|------|
| Phase 0 | 書く力をつける | 0 / X Step | 進行中 |
| Phase 1 | PHP/Laravel基礎を実装力で習得 | - | 未着手 |
| Phase 2 | Laravel実践 | - | 未着手 |
| Phase 3 | 弱点補強(SQL、HTTP、セキュリティ) | - | 未着手 |
| Phase 4 | 評価力の核(テスト、設計、リファクタリング) | - | 未着手 |
| Phase 5 | 上級(DDD、インフラ、デプロイ) | - | 未着手 |

## このリポジトリについて

PHP/Laravel エンジニアとしての学習記録。

- **学習方針**: 「読めるけど書けない」状態の解消が最重要課題。書く時間を最大化する
- **保有資格**: Java Silver SE17(概念理解はできている前提)
- **学習スタイル**: 写経 → 何も見ずに再現 → 少し変えて自力で書く、の3段階反復

## ディレクトリ構成

```
php-laravel/
├── README.md                # このファイル(現在地・進捗)
├── logs/                    # 日々の学習ログ
│   └── 2026/
│       └── 05/
│           └── 2026-05-01.md
├── phase0-writing/          # Phase 0: 書く力をつける
│   ├── block1-basics/       # 基本構文
│   ├── block2-classes/      # クラス
│   ├── block3-php-vs-java/  # PHPとJavaの差分
│   └── block4-composer/     # Composer と PSR-4
├── phase1-laravel-basics/   # Phase 1
├── phase2-laravel-practice/ # Phase 2
├── phase3-fundamentals/     # Phase 3(SQL, HTTP, セキュリティ)
├── phase4-quality/          # Phase 4
├── phase5-advanced/         # Phase 5
├── tests/                   # 振り返り時の腕試しテストの解答
└── notes/                   # 横断的なメモ
    ├── php-vs-java.md       # PHPとJavaの差分まとめ
    └── stuck-points.md      # 詰まった点の集約
```

## 学習ログの運用

`logs/年/月/YYYY-MM-DD.md` に1日1ファイルで記録。1日5分以内で書ける軽量フォーマット。

必須項目:
- 今日の現在地(Phase / Block / 完了Step)
- 今日書いたコード
- 詰まった点・気づき
- 明日やること

## ファイル命名ルール

Phase 0 の演習ファイルは Step番号を入れる。

```
b1-s01-hello.php
b1-s02-variables.php
b2-s06-animal.php
b2-s11-notifiable-interface.php
```

## コミットメッセージ規約

シンプルに、Step番号を入れる:

```
Add PHP-B1-S6: index array
Add PHP-B2-S11: notifiable interface
Update log: 2026-05-01
Update README: 進捗更新
```

## ゴールの3階層

常に意識する目標:

1. **コードレベル**: AIが出したコードのバグ・セキュリティ・パフォーマンス・可読性を指摘できる
2. **設計レベル**: AIが提案した設計が要件・将来の拡張・チーム規模に対して適切か判断できる
3. **要件レベル**: AIに渡している要件自体が正しいかをビジネス的に判断できる

## 振り返り

Phase完了時の振り返りはここに追記していく。

### Phase 0 振り返り

- 完了日: -
- 所感: -
- 次Phaseへの準備: -