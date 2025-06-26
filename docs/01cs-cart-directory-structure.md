# CS-Cartのディレクトリ構造

## 概要

CS-Cartのアドオン開発を始めるには、システムのディレクトリ構造を理解することが不可欠です。この文書では、開発に関連する主要なディレクトリと、それぞれの役割について詳しく解説します。

## ディレクトリ階層図

```
📁 CS-Cart
│
├── 📁 app                    # アプリケーションのコアファイル
│   └── 📁 addons            # すべてのアドオンが保存される場所
│       └── 📁 {addon_id}    # あなたのアドオン専用ディレクトリ
│           ├── 📄 addon.xml      # アドオンの定義・設定ファイル
│           ├── 📄 func.php       # アドオンのメイン関数群
│           ├── 📄 init.php       # フック登録を行うファイル
│           ├── 📁 controllers    # コントローラーディレクトリ
│           │   ├── 📁 frontend   # フロントエンド（表側）用コントローラー
│           │   └── 📁 backend    # バックエンド（管理画面）用コントローラー
│           └── 📁 schemas        # 各種設定スキーマファイル
│               ├── 📁 menu       # メニュー関連スキーマ
│               ├── 📁 settings   # 設定関連スキーマ
│               └── 📁 permissions # 権限関連スキーマ
│
├── 📁 design                 # デザイン関連ファイル
│   ├── 📁 backend           # 管理画面デザイン
│   │   └── 📁 templates     # 管理画面用テンプレート
│   │       └── 📁 addons    
│   │           └── 📁 {addon_id} # 管理画面用アドオンテンプレート
│   │
│   └── 📁 themes            # フロントエンドテーマ
│       └── 📁 {theme}       # 各テーマディレクトリ（responsive, brightなど）
│           ├── 📁 templates # テンプレートファイル
│           │   └── 📁 addons
│           │       └── 📁 {addon_id} # フロントエンド用テンプレート
│           └── 📁 css
│               └── 📁 addons
│                   └── 📁 {addon_id} # CSSファイル
│
├── 📁 js                     # JavaScriptファイル
│   └── 📁 addons
│       └── 📁 {addon_id}     # アドオン用JavaScriptファイル
│
└── 📁 var                    # 可変データの保存ディレクトリ
    ├── 📁 langs             # 言語ファイル
    │   └── 📁 {language_code} # 各言語コード（ja, en, etc）
    │       └── 📁 addons
    │           └── 📄 {addon_id}.po # アドオン用言語ファイル
    │
    └── 📁 themes_repository  # ⚠️重要：テーマリポジトリ
        └── 📁 {theme}        # フロントエンドテーマと同期
            └── 📁 templates
                └── 📁 addons
                    └── 📁 {addon_id} # テーマリポジトリ用テンプレート
```

## 主要ディレクトリの役割

### app/addons/{addon_id}/ — アドオンのコア部分

- **addon.xml**: アドオンの基本情報、動作設定、互換性などを定義
- **func.php**: アドオンのメイン関数を定義（インストール/アンインストール処理なども含む）
- **init.php**: PHPフックの使用を宣言する`fn_register_hooks`関数が定義されているファイル
- **controllers/**: アドオン独自のコントローラー（画面遷移や処理を制御）
  - **frontend/**: 一般ユーザー向け画面のコントローラー
  - **backend/**: 管理者向け画面のコントローラー
- **schemas/**: 各種設定ファイル（メニュー構造、権限設定など）

### design/ — 表示関連ファイル

- **backend/templates/addons/{addon_id}/**: 管理画面用のテンプレートファイル
- **themes/{theme}/templates/addons/{addon_id}/**: フロントエンド用のテンプレートファイル

### js/addons/{addon_id}/ — JavaScriptファイル

- アドオン用のJavaScriptファイルを配置
- フロントエンド・バックエンド両方で使用されるJSはここに

### var/ — 可変データ

- **langs/{language_code}/addons/{addon_id}.po**: 多言語対応用の言語ファイル
- **themes_repository/{theme}/**: ⚠️重要：テーマファイルのマスターコピーを保持

## 特別な注意点：テーマリポジトリ

`var/themes_repository/`ディレクトリは非常に重要です。このディレクトリは：

- テーマファイルのマスターコピーを保持する
- アドオン開発では**必ず**ここにもテンプレートを配置する必要がある
- アドオンのインストール時にここからファイルがコピーされる
- テンプレート変更時は`var/themes_repository/`と`design/themes/`の両方を同期させること

## 開発のヒント

- 既存アドオンのコードを参照して学ぶことが効果的です
- 特に`app/addons/discussion`や`app/addons/banners`などのコアアドオンは良い参考になります
- 紹介したディレクトリ構造以外にも、アドオンによっては`Tygh\Addons\{AddonName}`名前空間のクラスが含まれる`src`ディレクトリや、`init.php`など追加のファイルが存在することがあります
- 既存のアドオンを調査して、特定の機能実装に必要な具体的なファイル構成を参考にしましょう
- アドオンのプレフィックスを決めて関数名の衝突を避けましょう（例：`fn_myprefix_function_name`）
- 常にCS-Cartの命名規則とコーディング規約に従いましょう

## 関連ドキュメント

- [アドオンの基本構造作成](cs-cart-addon-structure.md)
- [テーマリポジトリの重要性](cs-cart-theme-repository.md)
- [CS-Cart開発規約](cs-cart-rules.md)
