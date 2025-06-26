<?php
/**
 * アドオンで使用するPHP HOOKポイントをfn_register_hooks()で登録する
 * @noinspection PhpUndefinedClassInspection
 */

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

// フックを登録
fn_register_hooks(
    'calculate_cart_items',
    'pre_get_cart_product_data',
    'get_order_items_info_post',
    'post_mtpl_get_ordered_products',
    'update_product_pre'
);