<?php

/**
 * コントローラーファイル
 */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

// ---------------------- POST routine ------------------------------------- //

//if ($_SERVER['REQUEST_METHOD'] === 'POST') {}

// ---------------------- GET routine ------------------------------------- //

if ($mode === 'checkout') {

    $cart = Tygh::$app['session']['cart'];

    if($cart['is_congratulatory_flower'] === 'Y') {
        Tygh::$app['view']->assign('is_congratulatory_flower', 'Y');
    }

    //祝電花の配送先をセット
    if($cart['hall_data']) {
        Tygh::$app['view']->assign('hall_data', $cart['hall_data']);
    }

    //チケット商品フラグ
    if($cart['is_ticket'] === 'Y') {
        Tygh::$app['view']->assign('is_ticket', 'Y');
    }
}
