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

if ($mode === 'update') {
   $wedding_halls = fn_novdg_get_wedding_halls();

   Tygh::$app['view']->assign('wedding_halls', $wedding_halls);
}