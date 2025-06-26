# アドオンの基本構造作成

## 2.1 アドオンディレクトリの作成
```
app/addons/{addon_id}/
```

## 2.2 addon.xmlの作成

このXMLファイルは、CS-Cartのアドオンを定義するための重要なファイルです。
このファイルさえあれば、アドオンとして認識されます。

```xml
<?xml version="1.0"?>
<addon scheme="3.0">
    <id>{addon_id}</id>
    <version>1.0.0</version>
    <priority>100</priority>
    <position>1</position>
    <status>active</status>
    <default_language>ja</default_language>
    <auto_install>MULTIVENDOR,ULTIMATE</auto_install>
    <!-- アドオンの互換性（任意） -->
    <compatibility>
        <core_version>
            <min>4.11.1</min>
        </core_version>
    </compatibility>
    <!-- アドオン設定項目（任意） -->
    <settings></settings>
    <!-- インストール/アンインストール用のSQLクエリ（任意） -->
    <queries>
        <!-- インストール時のSQLクエリ -->
        <item for="install">
            CREATE TABLE IF NOT EXISTS ?:{addon_id}_items (
                item_id int(11) unsigned NOT NULL auto_increment,
                name varchar(255) NOT NULL default '',
                PRIMARY KEY  (item_id)
            ) Engine=MyISAM DEFAULT CHARSET UTF8;
        </item>
        <!-- アンインストール時のSQLクエリ -->
        <item for="uninstall">DROP TABLE IF EXISTS ?:{addon_id}_items;</item>
    </queries>
    <!-- インストール/アンインストール用の関数（任意） -->
    <functions>
        <item for="install">fn_{addon_id}_install</item>
        <item for="uninstall">fn_{addon_id}_uninstall</item>
    </functions>
</addon>
```



## 2.3 言語ファイル（POファイル）の作成

言語ファイルに言語変数を定義します。これにより管理画面の言語＞言語変数から簡単に調整することができ、
多言語への対応も簡単になります。

```
# var/langs/ja/addons/{addon_id}.po

msgid ""
msgstr "Project-Id-Version: cs-cart-latest\\n"

msgctxt "{addon_id}.example_title"
msgid "Example Title"
msgstr "サンプルタイトル"

msgctxt "{addon_id}.manage_items"
msgid "Manage Items"
msgstr "アイテム管理"
```