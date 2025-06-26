# コントローラー作成

CS-Cartでは、コントローラーを2つの方法で実装できます：

## 独自のコントローラーを作成

独自のコントローラーとは？
`.../index.php?dispatch=my_addon_contoroller.test`のようなURLでアクセスする際に実行されるコントローラー
この場合は、app/addons/{addon_id}/controllers/frontend/my_addon_contoroller.php`に実装する必要があります。
`test`は`$mode`の値になります。

独自の機能用に新しいコントローラーを作成する場合：

```php
<?php
// app/addons/{addon_id}/controllers/frontend/example.php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * フロントエンドコントローラーの例
 * この場合は`.../index.php?dispatch=example.test`でアクセス可能になります。
 * ※正し、テンプレートファイルがない場合は404が表示されます。
 */
if ($mode === 'test') {
    // データの取得
    $test_data = fn_someting_function($_REQUEST['item_id']);
    
    // テンプレート変数へのアサイン
    // テンプレートでは、`{$test_data}`のように使用できます
    Tygh::$app['view']->assign('test_data', $test_data);
}
```

```php
<?php
// app/addons/{addon_id}/controllers/backend/example.php
// この場合はadmin.php?dispatch=example.manage でアクセス可能になります。

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * バックエンドコントローラーの例
 */
if ($mode === 'manage') {
    // 管理画面の一覧表示
    $items = fn_{addon_id}_get_items();
    
    Tygh::$app['view']->assign('items', $items);
}
```

## 既存のコントローラーを拡張（PRE/POST処理）

既存のコアコントローラーの処理前（PRE）や処理後（POST）に独自の処理を追加する場合は、特定の命名規則に従ったファイルを作成します：

### POST処理の例

例えば、`/app/controllers/backend/products.php`の処理後に独自の処理を追加したい場合：

`コア関数_post.php`や`コア関数_pre.php`のように、命名規則に従ったファイルを作成します。

```php
<?php
// app/addons/{addon_id}/controllers/backend/products.post.php
// 管理画面側の商品関連コントローラーのPOST処理

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * productsコントローラーのPOST処理
 * コアコントローラーの処理が完了した後にこのコードが実行される
 */
if ($mode === 'manage') {
    // コアの処理（`/app/controllers/backend/products.php`）で取得された製品データに追加情報を付加
    $products = Tygh::$app['view']->getTemplateVars('products');
    
    foreach ($products as &$product) {
        // 追加データを取得
        $product['additional_data'] = fn_{addon_id}_get_product_additional_data($product['product_id']);
    }
    
    // 変更されたデータを再度アサイン
    Tygh::$app['view']->assign('products', $products);
}
```

### PRE処理の例

コア処理の前に実行される処理を追加する場合：

```php
<?php
// app/addons/{addon_id}/controllers/backend/products.pre.php

if (!defined('BOOTSTRAP')) { die('Access denied'); }

/**
 * productsコントローラーのPRE処理
 * コアコントローラーの処理が実行される前にこのコードが実行される
 */
if ($mode === 'update') {
    // 製品更新前に追加の検証や処理を行う
    if (!empty($_REQUEST['product_id'])) {
        $can_update = fn_{addon_id}_can_update_product($_REQUEST['product_id']);
        
        if (!$can_update) {
            fn_set_notification('W', __('warning'), __('addon_id.product_update_restricted'));
            return [CONTROLLER_STATUS_REDIRECT, 'products.manage'];
        }
    }
}
```

この方法を使うことで、コアファイルを直接編集することなく、既存の機能を拡張できます。これにより、アップデート時の互換性問題を大幅に軽減できます。