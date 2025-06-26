# CS-Cart アドオン関数定義ガイド

## 概要

CS-Cartアドオンの関数定義は、主に `app/addons/{addon_id}/func.php` に記述します。このファイルはアドオンがインストールされ、有効化されると自動的に読み込まれます。

## 基本構造

```php
<?php
// app/addons/{addon_id}/func.php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * アドオンインストール時の処理
 * addon.xmlで定義した<functions>タグの<item for="install">で指定された関数
 */
function fn_{addon_id}_install()
{
    // インストール時の追加処理
}

/**
 * アドオンアンインストール時の処理
 * addon.xmlで定義した<functions>タグの<item for="uninstall">で指定された関数
 */
function fn_{addon_id}_uninstall()
{
    // アンインストール時の追加処理
}
```

## 関数の命名規則

CS-Cartでは関数の命名規則が非常に重要です。すべての関数は `fn_` プレフィックスで始まります。

### 1. PHPフックで利用する関数

フックポイントで利用する関数は、特定の命名規則に従う必要があります：

```php
/**
 * {addon_id}アドオンのフック関数
 * 
 * @param array $product 製品データ（参照渡し）
 * @param mixed $auth 認証情報（参照渡し）
 * @return void
 */
function fn_{addon_id}_{hook_name}(&$product, &$auth)
{
    // フック処理の実装
    // 引数は元のフック関数のものと同じにする
}
```

#### フックポイントとは

フックポイント（PHP）とは、主にコア関数（`app/functions`で定義）に書かれている拡張ポイントを指します。
`fn_set_hook` の呼び出し箇所を検索することで、利用可能なフックポイントを特定できます。

#### 実装例

「my_addon」というアドオンで「get_product_data_post」というフックを実装する場合：

```php
/**
 * 製品データ取得後の処理
 * 
 * @param array $product 製品データ（参照渡し）
 * @param mixed $auth 認証情報（参照渡し）
 * @param array $params 追加パラメータ
 * @return void
 */
function fn_my_addon_get_product_data_post(&$product, &$auth, $params)
{
    // 製品データに追加情報を付与するなどの処理
    $product['custom_field'] = 'カスタム値';
}
```

### 2. アドオン独自の関数

アドオン独自の関数（ユーティリティ関数）は、他のアドオンや関数との名前の衝突を避けるため、以下の命名規則を推奨します：

```php
/**
 * アドオン独自の機能を実装した関数
 *
 * @param array $params パラメータ
 * @return mixed 処理結果
 */
function fn_{アドオンIDの省略形}_{function_name}($params = [])
{
    // 独自機能の実装
    return $result;
}
```

#### 実装例

「my_addon」というアドオンで「get_special_data」という機能を実装する場合、「myadn」のような省略形を使用：

```php
/**
 * 特別なデータを取得する関数
 *
 * @param int $item_id アイテムID
 * @return array 取得したデータ
 */
function fn_myadn_get_special_data($item_id)
{
    // データ取得ロジック
    $data = db_get_row("SELECT * FROM ?:my_addon_items WHERE item_id = ?i", $item_id);
    
    return $data;
}
```

## 命名規則のメリットと重要性

適切な命名規則に従うことで以下のメリットがあります：

- **名前の衝突防止**: 他のアドオンやコア関数との名前衝突のリスクを低減
- **可読性の向上**: 関数の目的や所属が明確になる
- **メンテナンス性**: 同じアドオンの関数であることが省略形で判別できる
- **一貫性**: コードベース全体での一貫性が保たれる

### 注意点

- CS-Cartの関数はすべて `fn_` で始める
- フック関数は `fn_{addon_id}_{hook_name}` の形式を厳守
- アドオン独自関数は `fn_{アドオンIDの省略形}_{function_name}` の形式を推奨
- アドオンIDの省略形は一貫性を持って使用し、チーム内で共有する

適切な命名規則を守ることで、他のアドオンとの競合を防ぎ、メンテナンス性の高いコードを書くことができます。