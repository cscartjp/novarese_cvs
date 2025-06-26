<?php

/**
 * コントローラーファイル
 * @noinspection PhpUndefinedClassInspection
 * @var $mode
 * @var  $action
 * @var $auth
 */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

// ---------------------- POST routine ------------------------------------- //

//if ($_SERVER['REQUEST_METHOD'] === 'POST') {}

// ---------------------- GET routine ------------------------------------- //

if ($mode === 'details') {
    $order_info = Tygh::$app['view']->getTemplateVars('order_info');

    




    //すべての商品がダウンロード商品の場合は、Trueとなる。
    $all_edp_free_shipping = false;

    //一部の商品がダウンロード商品の場合はTrueとする。
    $has_edp_product = false;

    //一部の商品が祝電花商品の場合はTrueとする。
    $congratulatory_flower = false;

    // 一部の商品がチケット商品の場合はTrueとする。
    $is_ticket = false;

    foreach ($order_info['product_groups'] as $group_key => $group) {
        if ($group['all_edp_free_shipping'] === true) {
            $all_edp_free_shipping = true;
        }

        $products = $group['products'];
        foreach ($products as $product_key => $product) {
            if ($product['is_edp'] === 'Y') {
                $has_edp_product = true;
            }        
        }
    }

    //一部の商品が祝電花商品の場合はTrueとする。
    //一部の商品がチケット商品の場合はTrueとする。
    foreach ($order_info['products'] as $product_key => $product) {
        if ($product['congratulatory_flower'] === 'Y') {
            $congratulatory_flower = true;
        }

        if ($product['is_ticket'] === 'Y') {
            $is_ticket = true;
        }
    }

    


    // die(fn_print_r([
    //     $order_info['products'],
    //     $all_edp_free_shipping,
    //     $has_edp_product
    // ]));

    Tygh::$app['view']->assign('all_edp_free_shipping', $all_edp_free_shipping);
    Tygh::$app['view']->assign('has_edp_product', $has_edp_product);
    Tygh::$app['view']->assign('congratulatory_flower', $congratulatory_flower);
    Tygh::$app['view']->assign('is_ticket', $is_ticket);
}
