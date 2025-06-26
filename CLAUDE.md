# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## プロジェクト概要

このプロジェクトは、CS-Cartの決済方法固定アドオン「novarese_cvs」の開発プロジェクトです。特定の商品に対してコンビニ決済のみを許可する機能を実装します。

### 主な機能
- 商品別の決済方法固定機能
- 管理画面での決済方法設定
- 商品にコンビニ決済固定フラグ（char1）の設定機能

## アーキテクチャ

### CS-Cartアドオン構造
```
app/addons/novarese_cvs/
├── addon.xml                 # アドオン定義ファイル
├── func.php                  # 汎用関数定義
├── controllers/              # コントローラーファイル
├── schemas/                  # スキーマ定義
│   ├── menu/                # 管理画面メニュー定義
│   └── routes/              # ルート定義
├── templates/               # テンプレートファイル
└── var/langs/ja/            # 日本語言語ファイル
```

### 主要な開発ファイル
- `addon.xml`: アドオンのメタデータと設定項目を定義
- `func.php`: アドオン固有の関数を定義
- `schemas/menu/menu.post.php`: 管理画面メニューの追加
- テンプレートファイル: フロントエンド/バックエンドの画面

### データベース設計
- 商品テーブル（cscart_products）のchar1フィールドを使用
- コンビニ決済固定フラグとして利用

## 開発ガイドライン

### CS-Cart固有の重要事項
1. **アドオンID**: `novarese_cvs`として統一
2. **言語ファイル**: PO形式で多言語対応（主に日本語）
3. **フック機能**: CS-Cartのフックシステムを活用
4. **テーマリポジトリ**: キャッシュクリアが必要な場合あり

### 設定項目の実装
```xml
<settings>
    <sections>
        <section id="general">
            <items>
                <item id="cvs_payment_method">
                    <type>selectbox</type>
                    <variants>
                        <!-- 決済方法のリスト -->
                    </variants>
                </item>
            </items>
        </section>
    </sections>
</settings>
```

### 管理画面での商品設定
商品編集画面に「コンビニ決済固定」チェックボックスを追加し、char1フィールドに保存します。

## 参考ドキュメント

詳細な開発手順は以下のドキュメントを参照：
- `docs/00cs-cart-addon-development-index.md`: 開発ガイド索引
- `docs/03cs-cart-addon-structure.md`: アドオン基本構造
- `docs/09cs-cart-addon-specific-points.md`: CS-Cart固有の開発ポイント

### 外部リンク
- [CS-Cart 開発者向け公式ドキュメント](https://docs.cs-cart.com/latest/developer_guide/index.html)
- [アドオン開発ガイド](https://docs.cs-cart.com/latest/developer_guide/addons/index.html)