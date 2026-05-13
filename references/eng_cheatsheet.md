# PHP/Laravel 英単語チートシート

> 業務で出会う英単語を、**カテゴリ別・対比形式** で一覧できるチートシート。
> 「単語を引く」ためではなく、「**コードを書きながら命名の選択肢を眺める**」ための資料です。
>
> 育てていくもの。業務で出会った新しい単語は、適切なセクションに追加していってください。

## 目次

1. [動詞 by 用途](#1-動詞-by-用途)
2. [紛らわしい類義語ペア](#2-紛らわしい類義語ペア)
3. [状態を表す形容詞(対義語ペア)](#3-状態を表す形容詞対義語ペア)
4. [-ed パターン早見表](#4--ed-パターン早見表状態を表す過去分詞)
5. [接頭辞早見表(否定・反対)](#5-接頭辞早見表否定反対)
6. [PHP/Laravel 固有用語](#6-phplaravel-固有用語)
7. [CRUD 早見表](#7-crud-早見表)
8. [略語・短縮形](#8-略語短縮形)

---

## 1. 動詞 by 用途

メソッド名を考える時に、用途別に動詞の選択肢を眺める。

### 取得系

| 動詞 | 意味 | 使用例 | ニュアンス |
|------|------|------|---------|
| `get` | 取得する(汎用) | `getUser()` | 最も汎用的、プロパティ取得にも |
| `find` | 探して取得する | `findById($id)` | 検索ロジックがある |
| `fetch` | 取りに行く | `fetchAll()` | DB/APIから取りに行くニュアンス |
| `retrieve` | 取り出す | `retrieveOrder()` | 保存場所から取り出す |
| `load` | 読み込む | `loadConfig()` | ファイル・設定の読み込み |
| `read` | 読む | `readFile()` | 読み込み専用の操作 |
| `pick` | 選び取る | `pickRandom()` | 一部を選択 |

### 保存・作成系

| 動詞 | 意味 | 使用例 | ニュアンス |
|------|------|------|---------|
| `save` | 保存する | `saveUser()` | 新規・更新を問わない |
| `create` | 作成する | `createPost()` | 新規作成 |
| `make` | 作る | `makeRequest()` | インスタンス化に近い |
| `build` | 組み立てる | `buildQuery()` | 部品から組み立てる |
| `store` | 格納する | `store()` | DBや永続層に保存(Laravel頻出) |
| `register` | 登録する | `registerUser()` | システムに登録 |
| `add` | 追加する | `addItem()` | コレクションに追加 |
| `insert` | 挿入する | `insertRecord()` | DBレコード挿入 |

### 更新系

| 動詞 | 意味 | 使用例 | ニュアンス |
|------|------|------|---------|
| `update` | 更新する | `updateProfile()` | 既存データの変更 |
| `modify` | 変更する | `modifySettings()` | 部分的な変更 |
| `change` | 変える | `changePassword()` | 値を変える(汎用) |
| `set` | 設定する | `setName()` | プロパティに値をセット |
| `edit` | 編集する | `editPost()` | ユーザー操作的な編集 |
| `replace` | 置き換える | `replaceItem()` | 既存を別のもので置き換え |

### 削除系

| 動詞 | 意味 | 使用例 | ニュアンス |
|------|------|------|---------|
| `delete` | 削除する | `deleteUser()` | 永続的削除(DB操作で頻出) |
| `remove` | 取り除く | `removeItem()` | コレクションから除外 |
| `destroy` | 破棄する | `destroy($id)` | Laravel の controller でよく使う |
| `clear` | 一掃する | `clearCache()` | 全部空にする |
| `purge` | 完全削除する | `purgeLogs()` | ゴミ箱から完全削除 |
| `discard` | 捨てる | `discardChanges()` | 変更を捨てる |

### 検証・確認系

| 動詞 | 意味 | 使用例 | ニュアンス |
|------|------|------|---------|
| `validate` | 検証する | `validate($data)` | 妥当性チェック |
| `check` | 確認する | `checkPermission()` | 確認(汎用) |
| `verify` | 真偽を確かめる | `verifyEmail()` | 本物かを確かめる |
| `confirm` | 確定する | `confirmOrder()` | 確認・確定 |
| `ensure` | 保証する | `ensureLogin()` | 〜であることを保証 |
| `assert` | 主張・断言する | `assertEquals()` | テストで頻出 |

### 判定系(bool を返す)

| 動詞 | 意味 | 使用例 | ニュアンス |
|------|------|------|---------|
| `is` | 〜である | `isActive()` | 状態の判定 |
| `has` | 〜を持つ | `hasPermission()` | 所有の判定 |
| `can` | できる | `canEdit()` | 可能性の判定 |
| `should` | すべき | `shouldRetry()` | 推奨判定 |
| `exists` | 存在する | `userExists()` | 存在判定 |
| `contains` | 含む | `contains($item)` | 包含判定 |
| `matches` | 一致する | `matches($pattern)` | パターン一致 |

### 変換系

| 動詞 | 意味 | 使用例 | ニュアンス |
|------|------|------|---------|
| `convert` | 変換する | `convertToJson()` | 形式変換(汎用) |
| `transform` | 変形する | `transform($data)` | 構造変換 |
| `parse` | 解析する | `parseDate()` | 文字列を構造化 |
| `format` | 整形する | `formatPrice()` | 表示形式に整える |
| `serialize` | 直列化する | `serialize()` | オブジェクト → 文字列 |
| `encode` | 符号化する | `encodeJson()` | データ → 別形式 |
| `decode` | 復号する | `decodeBase64()` | 別形式 → データ |
| `map` | 対応付ける | `map($callback)` | 各要素を変換 |

### 処理・実行系

| 動詞 | 意味 | 使用例 | ニュアンス |
|------|------|------|---------|
| `process` | 処理する | `processPayment()` | 一連の処理 |
| `execute` | 実行する | `executeQuery()` | コマンド・クエリ実行 |
| `run` | 走らせる | `runTask()` | タスク実行 |
| `handle` | 処理を担当する | `handleRequest()` | リクエスト処理 |
| `apply` | 適用する | `applyDiscount()` | 何かを適用 |
| `perform` | 行う | `performAction()` | 行為を実行 |
| `invoke` | 呼び出す | `__invoke()` | 関数として呼び出す |
| `dispatch` | 派遣する | `dispatchEvent()` | イベント発火など |

### コレクション操作系

| 動詞 | 意味 | 使用例 | ニュアンス |
|------|------|------|---------|
| `filter` | ふるい分ける | `filter($callback)` | 条件で絞る |
| `sort` | 並べ替える | `sortByDate()` | 並び替え |
| `group` | グループ化する | `groupByCategory()` | グループ分け |
| `merge` | 統合する | `merge($array)` | 配列を結合 |
| `split` | 分割する | `split($delimiter)` | 文字列・配列を分割 |
| `join` | 連結する | `join(',')` | 配列を文字列に |
| `count` | 数える | `count()` | 要素数 |
| `reduce` | 集約する | `reduce($callback)` | 1つの値にまとめる |

---

## 2. 紛らわしい類義語ペア

「どっちを使う?」で迷った時の比較表。

### `get` vs `fetch` vs `retrieve`

| 動詞 | 使うべき場面 | 使用例 |
|------|----------|------|
| `get` | プロパティ取得、汎用的な取得 | `$user->getName()` |
| `fetch` | DBやAPIから取りに行く時 | `$posts = Post::fetch()` |
| `retrieve` | 保存場所から取り出す | `retrieveSession()` |

**迷ったら `get`**。最も汎用的で読み手が困らない。

### `delete` vs `remove` vs `destroy`

| 動詞 | 使うべき場面 | 使用例 |
|------|----------|------|
| `delete` | 永続的削除、DBからの削除 | `User::delete($id)` |
| `remove` | コレクションから外す(永続削除でないことも) | `$cart->removeItem($id)` |
| `destroy` | Laravel のリソースコントローラの慣習 | `destroy(Request $request)` |

### `create` vs `make` vs `build`

| 動詞 | 使うべき場面 | 使用例 |
|------|----------|------|
| `create` | DBに永続化する新規作成 | `User::create([...])` |
| `make` | インスタンスを作る(永続化しない) | `User::make([...])` |
| `build` | 部品から組み立てる | `$builder->build()` |

### `update` vs `modify` vs `change` vs `edit`

| 動詞 | 使うべき場面 | 使用例 |
|------|----------|------|
| `update` | DBレコードの更新(最も一般的) | `$post->update([...])` |
| `modify` | 部分的な変更、調整 | `modifyConfig()` |
| `change` | 値を別の値に変える | `changePassword()` |
| `edit` | UI上の編集操作 | `editProfile()` |

### `check` vs `verify` vs `validate`

| 動詞 | 使うべき場面 | 使用例 |
|------|----------|------|
| `check` | 確認する(汎用) | `checkStatus()` |
| `verify` | 本物・正当性を確かめる | `verifyEmail()` |
| `validate` | データの妥当性検証 | `validate($request)` |

### `find` vs `search`

| 動詞 | 使うべき場面 | 使用例 |
|------|----------|------|
| `find` | 主キーや一意キーで1件取得 | `User::find($id)` |
| `search` | 条件で複数件を探す | `searchByKeyword($q)` |

### `show` vs `display` vs `render`

| 動詞 | 使うべき場面 | 使用例 |
|------|----------|------|
| `show` | Laravel の resource controller の慣習 | `show($id)` |
| `display` | UI上に表示する | `displayMessage()` |
| `render` | テンプレートをHTMLに変換 | `view()->render()` |

### `add` vs `append` vs `push` vs `insert`

| 動詞 | 使うべき場面 | 使用例 |
|------|----------|------|
| `add` | コレクションに追加(汎用) | `addItem()` |
| `append` | 末尾に追加 | `appendChild()` |
| `push` | スタック・配列の末尾に追加 | `array_push()` |
| `insert` | DBレコード挿入、特定位置に挿入 | `DB::insert()` |

### `clear` vs `reset` vs `empty`

| 動詞 | 使うべき場面 | 使用例 |
|------|----------|------|
| `clear` | 中身を全部消す | `clearCache()` |
| `reset` | 初期状態に戻す | `resetPassword()` |
| `empty` | 空にする(状態としての空) | `$cart->isEmpty()` |

---

## 3. 状態を表す形容詞(対義語ペア)

bool プロパティの命名や `isXxx()` メソッドで使う形容詞。**対義語をペアで覚える**。

| 肯定 | 否定 | 意味 | 使用例 |
|------|------|------|------|
| `active` | `inactive` | 有効な/無効な | `isActive()` |
| `enabled` | `disabled` | 有効化/無効化 | `isEnabled()` |
| `valid` | `invalid` | 妥当な/不正な | `isValid()` |
| `available` | `unavailable` | 利用可能/不可 | `isAvailable()` |
| `visible` | `hidden` / `invisible` | 表示/非表示 | `isVisible()` |
| `public` | `private` | 公開/非公開 | アクセス修飾子 |
| `open` | `closed` | 開いた/閉じた | `isOpen()` |
| `empty` | `full` | 空/満杯 | `isEmpty()` |
| `online` | `offline` | オンライン/オフライン | `isOnline()` |
| `connected` | `disconnected` | 接続済/未接続 | `isConnected()` |
| `published` | `draft` / `unpublished` | 公開済/下書き | `isPublished()` |
| `paid` | `unpaid` | 支払い済/未払い | `isPaid()` |
| `verified` | `unverified` | 確認済/未確認 | `isVerified()` |
| `confirmed` | `unconfirmed` | 確定済/未確定 | `isConfirmed()` |
| `authorized` | `unauthorized` | 権限あり/権限なし | 403エラー |
| `authenticated` | `unauthenticated` | 認証済/未認証 | 401エラー |
| `read` | `unread` | 既読/未読 | `isRead()` |
| `seen` | `unseen` | 既見/未見 | 通知など |
| `expired` | `valid` | 期限切れ/有効 | `isExpired()` |
| `locked` | `unlocked` | ロック済/解除済 | `isLocked()` |
| `completed` | `pending` / `incomplete` | 完了/未完了 | `isCompleted()` |
| `success` | `failure` / `failed` | 成功/失敗 | `isSuccess()` |
| `required` | `optional` | 必須/任意 | バリデーション |
| `synchronous` | `asynchronous` | 同期/非同期 | 略: sync/async |
| `temporary` | `permanent` | 一時的/恒久的 | |

---

## 4. -ed パターン早見表(状態を表す過去分詞)

**動詞 + ed = 「〜された状態」** という形容詞。`isXxx()` メソッドや bool プロパティでよく使う。

| 動詞 | 過去分詞(-ed) | 意味 | 使用例 |
|------|--------------|------|------|
| `borrow`(借りる) | `borrowed` | 借りられた状態 | `isBorrowed` |
| `discontinue`(廃止する) | `discontinued` | 廃番の | `isDiscontinued` |
| `delete`(削除する) | `deleted` | 削除済の | `deletedAt` |
| `archive`(保管する) | `archived` | アーカイブ済 | `isArchived` |
| `approve`(承認する) | `approved` | 承認済 | `isApproved` |
| `reject`(却下する) | `rejected` | 却下された | `isRejected` |
| `complete`(完了する) | `completed` | 完了した | `isCompleted` |
| `cancel`(キャンセルする) | `cancelled` / `canceled` | キャンセルされた | `isCancelled` |
| `assign`(割り当てる) | `assigned` | 割り当てられた | `isAssigned` |
| `select`(選ぶ) | `selected` | 選択された | `isSelected` |
| `submit`(提出する) | `submitted` | 提出済 | `isSubmitted` |
| `process`(処理する) | `processed` | 処理済 | `isProcessed` |
| `register`(登録する) | `registered` | 登録済 | `isRegistered` |
| `subscribe`(購読する) | `subscribed` | 購読中 | `isSubscribed` |
| `block`(ブロックする) | `blocked` | ブロック済 | `isBlocked` |
| `ban`(禁止する) | `banned` | BAN済 | `isBanned` |
| `expire`(期限切れになる) | `expired` | 期限切れ | `isExpired` |
| `lock`(ロックする) | `locked` | ロック中 | `isLocked` |
| `permit`(許可する) | `permitted` | 許可された | `isPermitted` |
| `restrict`(制限する) | `restricted` | 制限された | `isRestricted` |

### 注意:不規則変化

| 動詞 | 過去分詞 | 意味 |
|------|--------|------|
| `pay` | `paid` | 支払い済 |
| `send` | `sent` | 送信済 |
| `read` | `read`(発音はレッド) | 既読 |
| `write` | `written` | 書き込み済 |
| `forget` | `forgotten` | 忘れられた |
| `lose` | `lost` | 失われた |
| `give` | `given` | 与えられた |

---

## 5. 接頭辞早見表(否定・反対)

### `un-` パターン(最も汎用的)

| 単語 | 意味 |
|------|------|
| `unread` | 未読 |
| `unpaid` | 未払い |
| `unverified` | 未確認 |
| `unauthorized` | 権限なし(401/403) |
| `unauthenticated` | 未認証(401) |
| `unavailable` | 利用不可 |
| `unknown` | 不明 |
| `undefined` | 未定義 |
| `unset` | セットされていない |
| `unlock` | ロック解除 |

### `in-` / `im-` パターン

| 単語 | 意味 |
|------|------|
| `invalid` | 不正な |
| `invisible` | 不可視 |
| `inactive` | 非アクティブ |
| `incorrect` | 間違った |
| `incomplete` | 未完了 |
| `impossible` | 不可能 |
| `improper` | 不適切な |

### `dis-` パターン(分離・否定)

| 単語 | 意味 |
|------|------|
| `disabled` | 無効化された |
| `disconnect` | 切断する |
| `discontinue` | 廃止する |
| `dismiss` | 却下する |
| `discard` | 捨てる |

### `non-` パターン(非〜)

| 単語 | 意味 |
|------|------|
| `nonexistent` | 存在しない |
| `non-null` | null でない |
| `non-empty` | 空でない |

### `mis-` パターン(誤った)

| 単語 | 意味 |
|------|------|
| `mismatch` | 不一致 |
| `misconfiguration` | 設定ミス |
| `misuse` | 誤用 |

### `re-` パターン(再〜)※否定ではないが頻出

| 単語 | 意味 |
|------|------|
| `retry` | 再試行 |
| `refresh` | 再読み込み |
| `reload` | 再ロード |
| `reset` | リセット |
| `restore` | 復元 |
| `rebuild` | 再構築 |
| `redirect` | リダイレクト |

---

## 6. PHP/Laravel 固有用語

### PHP 言語の用語

| 単語 | 意味 | 補足 |
|------|------|------|
| `namespace` | 名前空間 | クラスをフォルダ的に分類 |
| `class` | クラス | |
| `interface` | インターフェース | |
| `trait` | トレイト | コードの再利用機構(PHP独自) |
| `abstract` | 抽象 | `abstract class` |
| `extends` | 継承する | クラスの継承 |
| `implements` | 実装する | インターフェースの実装 |
| `static` | 静的 | インスタンス化せず使える |
| `final` | 最終 | 継承禁止 |
| `void` | 戻り値なし | 戻り値の型 |
| `mixed` | 任意の型 | 型宣言 |
| `nullable` | null許容 | `?string` の `?` |
| `exception` | 例外 | エラーを表現するオブジェクト |
| `throw` | 投げる | 例外をスローする |
| `catch` | 捕まえる | 例外をキャッチする |
| `finally` | 最後に | 例外有無に関わらず実行 |
| `iterable` | 反復可能な | foreach できる型 |
| `closure` | クロージャ | 無名関数 |
| `callable` | 呼び出し可能 | 関数を表す型 |

### Laravel の用語

| 単語 | 意味 | 補足 |
|------|------|------|
| `controller` | 制御するもの | リクエストを受け取る |
| `model` | モデル | DBのレコードを表現 |
| `view` | 表示 | HTMLを生成するテンプレート |
| `route` | ルート | URLとアクションの対応 |
| `middleware` | 中間処理 | リクエスト前後の処理 |
| `request` | リクエスト | HTTPリクエスト |
| `response` | レスポンス | HTTPレスポンス |
| `migration` | 移行 | DBスキーマの変更履歴 |
| `seeder` | シーダー | DBに初期データを投入 |
| `factory` | ファクトリ | テストデータ生成 |
| `provider` | 提供者 | ServiceProvider |
| `facade` | ファサード | 静的なAPIエイリアス |
| `eloquent` | 雄弁な | LaravelのORM名 |
| `query` | 問い合わせ | DBクエリ |
| `builder` | 組み立て役 | QueryBuilder |
| `collection` | コレクション | 配列のラッパー |
| `resource` | リソース | API用のデータ整形 |
| `policy` | 方針 | 権限制御 |
| `gate` | ゲート | 権限制御 |
| `guard` | 守衛 | 認証方式 |
| `tenant` | 借主・テナント | マルチテナント |
| `pipeline` | パイプライン | 処理を連鎖させる |
| `dispatch` | 派遣する | イベント・ジョブを発火 |
| `queue` | キュー | 非同期処理の待ち行列 |
| `job` | 仕事 | キューで実行されるタスク |
| `event` | イベント | 発生した出来事 |
| `listener` | 聞き手 | イベントを受けて動く |
| `observer` | 観察者 | モデルの変化を監視 |
| `scope` | 範囲 | クエリの再利用部品 |
| `mutator` | 変換器 | モデル属性の書き込み変換 |
| `accessor` | アクセサ | モデル属性の読み出し変換 |
| `cast` | 型変換 | 属性を特定の型に変換 |

### DB・SQL 用語

| 単語 | 意味 |
|------|------|
| `table` | テーブル |
| `column` | カラム(列) |
| `row` / `record` | 行・レコード |
| `index` | インデックス |
| `constraint` | 制約 |
| `foreign key` | 外部キー |
| `primary key` | 主キー |
| `unique` | 一意 |
| `nullable` | NULL許可 |
| `default` | デフォルト値 |
| `cascade` | 連鎖 |
| `timestamp` | タイムスタンプ |

### HTTP 用語

| 単語 | 意味 |
|------|------|
| `request` | リクエスト |
| `response` | レスポンス |
| `header` | ヘッダー |
| `body` | 本文 |
| `payload` | ペイロード(本文データ) |
| `endpoint` | エンドポイント |
| `redirect` | リダイレクト |
| `authentication` | 認証(誰か) |
| `authorization` | 認可(何ができるか) |
| `session` | セッション |
| `cookie` | クッキー |
| `token` | トークン |
| `csrf` | クロスサイトリクエスト偽造 |

---

## 7. CRUD 早見表

Create / Read / Update / Delete それぞれで使われる動詞バリエーション。

| 操作 | 主要動詞 | 類義語・派生 |
|------|--------|-----------|
| **Create** | `create` | `make`, `build`, `register`, `add`, `insert`, `store`, `submit` |
| **Read** | `get` | `find`, `fetch`, `retrieve`, `load`, `read`, `show`, `list`, `index` |
| **Update** | `update` | `modify`, `change`, `set`, `edit`, `replace`, `patch` |
| **Delete** | `delete` | `remove`, `destroy`, `clear`, `purge`, `discard` |

### Laravel リソースコントローラの規約

Laravel が自動生成するメソッド名:

| メソッド | HTTPメソッド | 役割 |
|---------|-----------|------|
| `index` | GET | 一覧表示 |
| `create` | GET | 作成フォーム表示 |
| `store` | POST | 新規作成(DB保存) |
| `show` | GET | 1件詳細表示 |
| `edit` | GET | 編集フォーム表示 |
| `update` | PUT/PATCH | 更新 |
| `destroy` | DELETE | 削除 |

Laravel ではこの規約に従うことで、コードの見通しがよくなる。

---

## 8. 略語・短縮形

業務で頻出する略語。

| 略語 | 元 | 意味 |
|------|-----|------|
| `id` | identifier | 識別子 |
| `db` | database | データベース |
| `uri` | Uniform Resource Identifier | URI |
| `url` | Uniform Resource Locator | URL |
| `dto` | Data Transfer Object | データ転送用オブジェクト |
| `dao` | Data Access Object | データアクセス用オブジェクト |
| `orm` | Object-Relational Mapping | DBとオブジェクトの対応付け |
| `crud` | Create Read Update Delete | 基本的なDB操作 |
| `rest` | Representational State Transfer | REST API の設計様式 |
| `api` | Application Programming Interface | API |
| `csrf` | Cross-Site Request Forgery | クロスサイトリクエスト偽造 |
| `xss` | Cross-Site Scripting | クロスサイトスクリプティング |
| `sql` | Structured Query Language | DB操作言語 |
| `json` | JavaScript Object Notation | データ形式 |
| `yaml` | YAML Ain't Markup Language | 設定ファイル形式 |
| `env` | environment | 環境 / 環境変数 |
| `auth` | authentication / authorization | 認証 / 認可 |
| `mw` | middleware | ミドルウェア |
| `req` | request | リクエスト |
| `res` | response | レスポンス |
| `params` | parameters | パラメータ |
| `args` | arguments | 引数 |
| `attr` | attribute | 属性 |
| `prop` | property | プロパティ |
| `var` | variable | 変数 |
| `func` / `fn` | function | 関数 |
| `tmp` / `temp` | temporary | 一時的 |
| `prev` | previous | 前の |
| `curr` | current | 現在の |
| `min` | minimum | 最小 |
| `max` | maximum | 最大 |
| `avg` | average | 平均 |
| `sum` | summary / total | 合計 |
| `cnt` | count | 個数 |
| `idx` | index | インデックス |
| `dest` | destination | 宛先 |
| `src` | source | 送信元 / ソース |
| `desc` | description / descending | 説明 / 降順 |
| `asc` | ascending | 昇順 |
| `sync` | synchronous | 同期 |
| `async` | asynchronous | 非同期 |

---

## 使い方ガイド

### コードを書く時

1. メソッド名を考える → **セクション1(動詞 by 用途)** を見る
2. `get` と `fetch` で迷う → **セクション2(類義語ペア)** を見る
3. bool プロパティの命名 → **セクション3(状態の形容詞)** を見る
4. 「〜された」を表す形容詞 → **セクション4(-ed パターン)** を見る
5. 否定の形容詞 → **セクション5(接頭辞)** を見る

### コードを読む時

1. 知らない単語が出てきた → **セクション6(PHP/Laravel固有用語)** を見る
2. 略語の意味 → **セクション8(略語)** を見る

### 育て方

業務で新しい単語に出会ったら、適切なセクションに追加していく。
特に Laravel の独自用語(セクション6)は、Phase 2 以降でどんどん追加していく。

---

## 改訂履歴

- v1.0(S21 で初版作成): PHP/Laravel の業務でよく出る単語を中心に