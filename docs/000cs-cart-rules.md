# CS-Cart開発規約

本規約は、CS-Cartのアドオンを開発に関するものです。アドオン名(addon_id)は、`addon.xml`で定義されています。

## 基本ルール

- ワークスペースで変更可能なものは、すべてアドオン関連のディレクトリに配置されている
- それ以外は変更しないこと（テーマのファイルなども同様）
- 例）`app/addons/{addon_id}/func.php`については編集可能だが、`app/functions`以下のPHPはコアのものなので編集しないこと

## 参考ドキュメント

### CS-Cart 開発者向け公式ドキュメント
- メインの開発者ガイド: https://docs.cs-cart.com/latest/developer_guide/index.html
- アドオン開発ガイド: https://docs.cs-cart.com/latest/developer_guide/addons/index.html
- コーディング規約: https://docs.cs-cart.com/latest/developer_guide/core/coding_standards/index.html
  - Hooks: https://docs.cs-cart.com/latest/developer_guide/core/coding_standards/hooks.html
  - HTML, CSS, JavaScript, Smarty: https://docs.cs-cart.com/latest/developer_guide/core/coding_standards/html_css_js_smarty.html
  - jQuery: https://docs.cs-cart.com/latest/developer_guide/core/coding_standards/jquery.html
  - PHP: https://docs.cs-cart.com/latest/developer_guide/core/coding_standards/php.html
- APIリファレンス: https://docs.cs-cart.com/latest/developer_guide/api/index.html

### 特に役立つセクション
- アドオンの構造: https://docs.cs-cart.com/latest/developer_guide/addons/addon_scheme.html
- コントローラーの作成: https://docs.cs-cart.com/latest/developer_guide/core/controllers/index.html
- フックの使い方: https://docs.cs-cart.com/latest/developer_guide/addons/hooking/index.html
- テンプレートの作成: https://docs.cs-cart.com/latest/developer_guide/core/templates/index.html

### その他の参考資料
- CS-Cartのデータベース構造: ルートディレクトリの`cscart_standard.sql`
- addon.xmlの詳細: ルートディレクトリの`addon.xml.full`

## 命名規則

### 関数の命名規則
- PHPの関数は`fn_`で始まる命名規則を遵守すること
- PHP HOOKに関する命名規則について十分理解すること
  - HOOKに関する規則: https://docs.cs-cart.com/latest/developer_guide/core/coding_standards/hooks.html
- アドオン独自の関数:
  - `fn_`で始まり、`_`で区切られた名前を採用
  - PHP HOOK関数とバッティングしないよう注意
  - 例: `hello_world`というアドオン名なら`fn_hwld_{function_name}`のようなユニークな名前を採用

## 開発Tips

### データベースの操作
- 基本的な方法: addon.xmlのqueriesセクションを使用
- テーブル名には必ず?:プレフィックスを付ける
- インストール時とアンインストール時の両方のクエリを定義
- 複雑なケース: addon.xmlに`function`セクションを使い、`app/functions/{addon_id}/`ディレクトリの`function.php`ファイルを利用

### 言語ファイル(.po)
- 配置場所: `var/langs/{language_code}/addons/{addon_id}.po`
- 言語変数: `{addon_id}.変数名`とすることでバッティングを防止

## 詳細仕様

### プロジェクト全般
```
[general]
project_type = "cs-cart"
version = "latest"
code_style = "cs-cart-standard"
php_version = "7.4+"
```

### 命名規則
```
[naming]
controllers = "snake_case.php"
models = "snake_case.php"
addons = "snake_case"
templates = "snake_case.tpl"
hooks = "snake_case"
functions = "fn_snake_case"
variables = "snake_case"
classes = "PascalCase"
```

### ディレクトリ構造
```
[directory_structure]
addons = "app/addons/"
backend_controllers = "app/{addon_id}/controllers/backend/"
frontend_controllers = "app/{addon_id}/controllers/frontend/"
functions = "app/{addon_id}/func.php"
schemas = "app/{addon_id}/schemas/"
frontend_templates = "design/themes/{theme}/addons/{addon_id}/"
less = "design/themes/{theme}/css/addons/{addon_id}/"
backend_templates = "design/backend/templates/addons/{addon_id}/"
js = "/js/addons/{addon_id}/"
themes_repository = "/var/themes_repository/{theme}/" -- frontend_templatesと同一にすること。
```

### フック
```
[hooks]
naming = "{hook_name}.{addon_name}"
documentation = "必須コメント：目的、パラメータ、戻り値"
```

### データベース
Placeholderを利用すること。
```
[database]
table_prefix = "cscart_"
table_naming = "snake_case"
field_naming = "snake_case"
primary_key = "item_id"
foreign_key = "{table_name}_id"
index_naming = "idx_{table}_{fields}"
```

### アドオン開発
```
[addon_development]
manifest = "addon.xml"
version_format = "x.y.z"
supported_versions = "明示的に記載すること"
uninstall_cleanup = "必須"
dependencies = "明示的に記載すること"
```

### テンプレート
```
[templates]
smarty_version = "4.x"
variables = "{$snake_case}"
blocks = "{block name=\"block_name\"}"
include = "{include file=\"path/to/template.tpl\"}"
translation = "{__(\"{key}\")}"
```

### JavaScript規約
```
[js_standards]
module_pattern = "true"
namespace = "Tygh"
jquery_usage = "$(document).ready(function() {})"
ajax = "$.ceAjax('request', url, {})"
event_binding = "$.ceEvent('on', element, event, callback)"
```

### セキュリティ
```
[security]
input_validation = "fn_validate_*"
xss_prevention = "fn_sanitize_html"
csrf_protection = "必須"
permissions = "fn_check_permissions"
sql_injection = "プリペアドステートメント必須"
```

### ドキュメント
```
[documentation]
code_comments = "PHPDoc準拠"
readme = "addon.xml内で記述"
changelog = "各バージョンごとに詳細を記載"
screenshots = "必要に応じて添付"
demo_data = "提供すること"
```


### ローカライゼーション
```
[localization]
language_variables = "{langvar}"
translation_files = "var/langs/{lang_code}/addons/{addon}.po"
plural_forms = "サポート必須"
right_to_left = "考慮すること"
```