# データベース設計と操作

## 7.1 CS-Cartのデータベース構造と命名規則

CS-Cartでは、データベースのテーブルとフィールドに関して一貫した命名規則があります。アドオン開発時にもこれらの規則に従うことが推奨されます。

### 7.1.1 テーブル命名規則

テーブル名のプレフィックスとして、常に`?:`を使用します。これはインストール時に自動的にCS-Cartのテーブルプレフィックス（例：`cscart_`）に置き換えられます。

```sql
CREATE TABLE IF NOT EXISTS ?:my_addon_items (
    /* フィールド定義 */
);
```

**テーブル命名のパターン**：

1. **エンティティテーブル**：`?:{entity}`
   - 例：`?:products`、`?:categories`

2. **リレーションテーブル**：`?:{entity1}_{entity2}_links`
   - 例：`?:product_feature_values`

3. **アドオン関連テーブル**：`?:{addon_id}_{entity}`
   - 例：`?:discussion_posts`、`?:banners_items`

### 7.1.2 フィールド命名規則

フィールド名はスネークケース（小文字とアンダースコア）を使用します。

**主要なフィールド命名パターン**：

1. **主キー**：通常は`{entity}_id`
   - 例：`product_id`、`category_id`、`item_id`

2. **外部キー**：参照先の主キーと同じ名前を使用
   - 例：`product_id`、`user_id`

3. **タイムスタンプ**：`timestamp`、`created_at`、`updated_at`など
   - 例：`timestamp`、`created_timestamp`

4. **ステータスフィールド**：`status`
   - 通常文字列型で、`A`（アクティブ）、`D`（無効）、`H`（非表示）などの値を取る

5. **並び順**：`position`
   - 整数型で、表示順を制御

### 7.1.3 インデックス命名規則

インデックスには明確で説明的な名前を使用します。

```sql
CREATE INDEX idx_my_addon_field1_field2 ON ?:my_addon_items (field1, field2);
```

## 7.2 データベース操作の実装

### 7.2.1 アドオンインストール/アンインストール時のスキーマ操作

`addon.xml`のqueriesセクションでテーブルの作成や削除を定義します：

```xml
<queries>
    <item for="install">
        CREATE TABLE IF NOT EXISTS ?:my_addon_items (
            item_id int(11) unsigned NOT NULL auto_increment,
            name varchar(255) NOT NULL default '',
            status char(1) NOT NULL default 'A',
            position int(11) NOT NULL default 0,
            timestamp int(11) unsigned NOT NULL default 0,
            PRIMARY KEY (item_id),
            KEY idx_status (status),
            KEY idx_position (position)
        ) Engine=MyISAM DEFAULT CHARSET UTF8;
    </item>
    <item for="uninstall">DROP TABLE IF EXISTS ?:my_addon_items;</item>
</queries>
```

### 7.2.2 データベース操作関数

CS-Cartには便利なデータベース操作関数が多数あります：

```php
// レコードの取得
$item = db_get_row("SELECT * FROM ?:my_addon_items WHERE item_id = ?i", $item_id);

// 複数レコードの取得
$items = db_get_array("SELECT * FROM ?:my_addon_items WHERE status = ?s ORDER BY position", 'A');

// レコードの挿入
$item_id = db_query("INSERT INTO ?:my_addon_items ?e", $item_data);

// レコードの更新
db_query("UPDATE ?:my_addon_items SET ?u WHERE item_id = ?i", $item_data, $item_id);

// レコードの削除
db_query("DELETE FROM ?:my_addon_items WHERE item_id = ?i", $item_id);
```

### 7.2.3 プレースホルダーの使用

CS-Cartのデータベース関数では、SQLインジェクションを防ぐためのプレースホルダーを使用します：

- `?i` - 整数値
- `?s` - 文字列値
- `?d` - 小数値（float）
- `?a` - 配列（IN句で使用）
- `?u` - UPDATEセット句用の配列
- `?e` - INSERTセット句用の配列
- `?p` - 事前に準備されたステートメント

```php
// プレースホルダーの使用例
$items = db_get_array(
    "SELECT * FROM ?:my_addon_items WHERE status = ?s AND position > ?i AND price BETWEEN ?d AND ?d",
    'A', 10, 5.99, 99.99
);

// IN句での配列使用
$items = db_get_array(
    "SELECT * FROM ?:my_addon_items WHERE item_id IN (?n)",
    array(1, 3, 5, 7)
);

// UPDATE文での配列使用
db_query(
    "UPDATE ?:my_addon_items SET ?u WHERE item_id = ?i",
    array('name' => '新しい名前', 'position' => 20),
    5
);
```

## 7.3 データベース設計のベストプラクティス

1. **テーブルに適切なインデックスを設定する**
   - 検索条件としてよく使われるフィールドにはインデックスを設定
   - 過度なインデックスは書き込みパフォーマンスに影響するため注意

2. **外部キー制約**
   - MyISAMではサポートされないため、アプリケーションロジックで整合性を確保

3. **文字セットとCollation**
   - UTF8を使用（`DEFAULT CHARSET UTF8`）
   - 多言語対応を考慮

4. **スケーラビリティを考慮**
   - 将来の拡張性を考慮してフィールドを設計
   - 何らかの理由でスキーマ変更が必要になった場合は、アドオンのバージョンアップ時に対応