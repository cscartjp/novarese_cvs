# CS-Cartアドオン開発ガイド - 索引

CS-Cartアドオン開発の基本的な流れについて、以下のファイルに分割して説明しています。各ファイルは特定のトピックに焦点を当てています。








## 目次

1. [CS-Cartのディレクトリ構造](01cs-cart-directory-structure.md)
   - CS-Cartのアドオン開発に関連する主要なディレクトリ構造の解説

2. [アドオンの設計・構想](02cs-cart-addon-design.md)
   - 機能要件の明確化
   - 技術的な検討

3. [アドオンの基本構造作成](03cs-cart-addon-structure.md)
   - アドオンディレクトリの作成
   - addon.xmlの作成

4. 機能の実装
   - [関数定義 (func.php)](04cs-cart-addon-function-definition.md)
   - [コントローラー作成](05cs-cart-addon-controllers.md)
   - [フックとテンプレートの実装](06cs-cart-addon-hooks-templates.md)

5. [アドオンのテスト](07cs-cart-addon-testing.md)
   - インストールテスト
   - 機能テスト
   - パフォーマンステスト

6. [パッケージング・リリース](08cs-cart-addon-packaging.md)
   - アドオンパッケージの作成
   - ドキュメント作成
   - バージョン管理
   - アドオンのインストール方法

7. [CS-Cart固有の開発ポイント](09cs-cart-addon-specific-points.md)
   - アドオン間の依存関係設定
   - アドオン設定項目の追加
   - 管理画面メニュー項目の追加
   - フックの登録

8. [データベース設計と操作](10cs-cart-database-design.md)
   - CS-Cartのデータベース構造と命名規則
   - データベース操作の実装
   - データベース設計のベストプラクティス

9. [テーマリポジトリの重要性](11cs-cart-theme-repository.md)
   - テーマリポジトリとは
   - アドオン開発でのテーマリポジトリの扱い
   - キャッシュと開発効率の向上

このガイドはCS-Cartアドオン開発の基本的な流れを説明するものです。実際の開発では、CS-Cartの公式ドキュメントも参照しながら進めることをお勧めします。

## 参考リンク

- [CS-Cart 開発者向け公式ドキュメント](https://docs.cs-cart.com/latest/developer_guide/index.html)
- [アドオン開発ガイド](https://docs.cs-cart.com/latest/developer_guide/addons/index.html)
- [コーディング規約](https://docs.cs-cart.com/latest/developer_guide/core/coding_standards/index.html)