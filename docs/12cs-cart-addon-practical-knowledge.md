# CS-Cartアドオン開発 - 実践的な学習内容

novarese_cvsアドオンの開発を通じて学んだCS-Cartアドオン開発の実践的なポイントを記録します。

## 1. アドオンの基本構成

### 必須ファイル
- `addon.xml`: アドオンの定義ファイル（最重要）
- `func.php`: アドオン固有の関数定義
- `var/langs/ja/addons/{addon_id}.po`: 言語ファイル

### ディレクトリ構造
```
app/addons/novarese_cvs/
├── addon.xml
├── func.php
├── controllers/
├── schemas/
│   ├── menu/
│   └── routes/
└── templates/

design/backend/templates/addons/novarese_cvs/
└── hooks/
    └── products/
        └── detailed_content.post.tpl

var/langs/ja/addons/
└── novarese_cvs.po
```

## 2. addon.xmlの重要なポイント

### 基本構成
```xml
<?xml version="1.0"?>
<addon scheme="3.0">
    <id>novarese_cvs</id>
    <version>0.0.1</version>
    <priority>100</priority>
    <position>1</position>
    <status>active</status>
    <default_language>ja</default_language>
    <auto_install>MULTIVENDOR,ULTIMATE</auto_install>
```

### 設定項目の定義
```xml
<settings>
    <sections>
        <section id="general">
            <items>
                <item id="novarese_club_payment_method">
                    <type>selectbox</type>
                </item>
            </items>
        </section>
    </sections>
</settings>
```

### データベースクエリ
```xml
<queries>
    <item for="install">
        ALTER TABLE `?:products` ADD COLUMN `cvs_fixed` CHAR(1) DEFAULT 'N';
    </item>
    <item for="uninstall">
        ALTER TABLE `?:products` DROP COLUMN `cvs_fixed`;
    </item>
</queries>
```

### インストール/アンインストール関数
```xml
<functions>
    <item for="install">fn_novarese_cvs_install</item>
    <item for="uninstall">fn_novarese_cvs_uninstall</item>
</functions>
```

## 3. 設定項目のバリアント関数

### func.phpでの実装
```php
function fn_settings_variants_addons_novarese_cvs_novarese_club_payment_method(): array
{
    $params = [
        'status' => 'A'
    ];
    $payment_methods = fn_get_payments($params);
    $payment_method_list = [
        '' => __('select')
    ];

    foreach ($payment_methods as $payment_method) {
        $payment_method_list[$payment_method['payment_id']] = $payment_method['payment'];
    }
    return $payment_method_list;
}
```

### 命名規則
- 関数名: `fn_settings_variants_addons_{addon_id}_{setting_id}`
- 戻り値: 配列（キー => 値の形式）

## 4. 言語ファイル（POファイル）の正しい書き方

### ヘッダー部分
```po
msgid ""
msgstr ""
"Project-Id-Version: cs-cart-latest\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Language-Team: Japanese\n"
"Language: ja_JP\n"
"Plural-Forms: nplurals=1; plural=0;\n"
"X-Generator: crowdin.com\n"
"Last-Translator: novarese <noreply@novarese.com>\n"
"PO-Revision-Date: 2024-01-01 00:00+0000\n"
```

### 設定項目の言語定義
```po
msgctxt "SettingsSections::novarese_cvs::general"
msgid "General"
msgstr "全般"

msgctxt "SettingsOptions::novarese_cvs::novarese_club_payment_method"
msgid "Convenience Store Payment"
msgstr "コンビニ決済"

msgctxt "SettingsTooltips::novarese_cvs::novarese_club_payment_method"
msgid "Select the payment method for convenience store payment"
msgstr "コンビニ決済の決済方法を選択してください"
```

### テンプレート用の言語定義
```po
msgctxt "Languages::novarese_cvs.cvs_fixed_flag"
msgid "CVS Payment Fixed"
msgstr "コンビニ決済固定"
```

## 5. テンプレートフック

### 管理画面テンプレート
```smarty
{include file="common/subheader.tpl" title=__("novarese_cvs.cvs_fixed_flag") target="#cvs_fixed_fields"}

<div id="cvs_fixed_fields" class="in collapse">
    <fieldset>
        <div class="control-group">
            <label for="cvs_fixed_flag" class="control-label">{__("novarese_cvs.cvs_fixed_flag")}:</label>
            <div class="controls">
                <input type="hidden" name="product_data[cvs_fixed]" value="N">
                <input type="checkbox" id="cvs_fixed_flag" name="product_data[cvs_fixed]" value="Y" {if $product_data.cvs_fixed == "Y"}checked="checked"{/if}>
                <span class="help-block">{__("novarese_cvs.cvs_fixed_flag_description")}</span>
            </div>
        </div>
    </fieldset>
</div>
```

### 言語キーの使用方法
- テンプレート内: `{__("addon_id.language_key")}`
- 間違った書き方: `{__("Languages::language_key")}` ❌

## 6. 重要なポイント

### データベースフィールド
- 既存テーブルにカラムを追加する場合はALTER TABLEを使用
- CS-Cartのテーブルプレフィックス `?:` を使用
- デフォルト値を必ず設定

### ファイル配置
- バックエンド用テンプレート: `design/backend/templates/addons/{addon_id}/`
- フロントエンド用テンプレート: `design/themes/responsive/templates/addons/{addon_id}/`

### 命名規則
- アドオンID: 小文字とアンダースコア
- 関数名: `fn_{addon_id}_` で始める
- 言語キー: `{addon_id}.{key_name}` 形式

## 7. 開発時の注意点

### テーマリポジトリ
- テンプレート変更後はキャッシュクリアが必要
- 管理画面 > デザイン > テーマリポジトリ でクリア

### デバッグ
- `fn_print_r()` でデバッグ出力可能
- ログは `var/log/` 以下に出力される

### バージョン管理
- addon.xmlのバージョンを適切に管理
- データベース変更時はマイグレーションを考慮

この知識を基に、効率的にCS-Cartアドオンを開発できます。