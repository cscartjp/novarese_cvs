<?php

if (!defined('BOOTSTRAP')) { die('Access denied'); }


/**
 * addon.xml用のセレクトボックス novarese_club_payment_method
 * @return array
 */
function fn_settings_variants_addons_novarese_cvs_novarese_club_payment_method(): array
{
    $params = [
        'status' => 'A'
    ];
    /** @noinspection PhpUndefinedFunctionInspection */
    $payment_methods = fn_get_payments($params);
    /** @noinspection PhpUndefinedFunctionInspection */
    $payment_method_list = [
        '' => __('select')
    ];

    foreach ($payment_methods as $payment_method) {
        $payment_method_list[$payment_method['payment_id']] = $payment_method['payment'];
    }
    return $payment_method_list;
}


function fn_novarese_cvs_install()
{
    
}

function fn_novarese_cvs_uninstall()
{
    
}