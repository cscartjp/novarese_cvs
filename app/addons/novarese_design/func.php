<?php
/**
 * アドオンで使用するPHP HOOK関数や独自の関数を定義するファイル
 * @noinspection PhpUndefinedClassInspection
 */

if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

use Tygh\Registry;


/**
 * 商品オプションの前に改行を追加し、オプション項目ごとに整形する
 * fn_set_hook('post_mtpl_get_ordered_products', $ordered_products, $items_ordered, $tpl_base_data, $type, $supplier_id);
 *
 * @param string $ordered_products 組み立てられた商品リスト文字列 (参照渡し)
 * @param array  $items_ordered    注文商品データ
 * @param array  $tpl_base_data    テンプレート基本データ
 * @param string $type             メールタイプ
 * @param int    $supplier_id      サプライヤーID
 */
function fn_novarese_design_post_mtpl_get_ordered_products(&$ordered_products, $items_ordered, $tpl_base_data, $type, $supplier_id)
{
    // オプション文字列のプレフィックス（検索用）- 先頭を全角スペースに修正
    $options_prefix_search = '　' . __('options') . ' - ';
    // オプション見出し文字列（置換後）- 改行を含めない
    $options_heading = '　' . __('options');

    // 改行コードを統一
    $ordered_products_normalized = str_replace("\r\n", "\n", $ordered_products);
    $product_lines = explode("\n", $ordered_products_normalized);

    $processed_lines = [];
    $current_product_block = '';

    foreach ($product_lines as $line) {
        // オプション行かどうかを判定 (行頭の全角スペースを考慮)
        // 注意: trim() は全角スペースを削除しないため、$line を直接使う
        if (strpos($line, $options_prefix_search) !== false) {
            // 蓄積された商品基本情報を追加 (末尾の改行や空白を除去)
            if (!empty(trim($current_product_block))) {
                $processed_lines[] = rtrim($current_product_block);
            }
            // オプション見出しを追加 (改行なし)
            $processed_lines[] = $options_heading;

            // オプション詳細部分を取得 (プレフィックス以降の文字列)
            $options_details_str = trim(substr($line, strpos($line, $options_prefix_search) + strlen($options_prefix_search)));

            // オプションをカンマとスペースで分割
            $options_array = explode(', ', $options_details_str);

            foreach ($options_array as $option) {
                 $trimmed_option = trim($option);
                 if (!empty($trimmed_option)) { // 空のオプションを除外
                    $processed_lines[] = ' - ' . $trimmed_option;
                 }
            }
            $current_product_block = ''; // 商品ブロックをリセット
        } else {
             // オプション行以外の場合
             $trimmed_line = trim($line);
             if (!empty($trimmed_line)) {
                 // 空でない行は現在のブロックに追加（元の行と改行を追加）
                 $current_product_block .= $line . "\n";
             } elseif (!empty(trim($current_product_block))) {
                 // 空行が現れ、かつ直前のブロックが空でない場合、区切りとみなしブロックを追加
                  $processed_lines[] = rtrim($current_product_block); // 末尾の改行を除去して追加
                  $processed_lines[] = ""; // 空行（区切り）を追加
                  $current_product_block = ''; // ブロックをリセット
             } else {
                  // 連続する空行などはそのまま追加（元のフォーマットを尊重する場合）
                  // $processed_lines[] = "";
                  // または無視する場合
                  // continue;
                  // ここでは無視せず追加
                  $processed_lines[] = "";
             }
        }
    }

    // ループ終了後、最後のブロックが残っていれば追加
    if (!empty(trim($current_product_block))) {
        $processed_lines[] = rtrim($current_product_block);
    }

    // 処理後の行を結合して $ordered_products を更新
    // 配列末尾の不要な空要素を削除
    while (count($processed_lines) > 0 && trim(end($processed_lines)) === "") {
        array_pop($processed_lines);
    }
    $ordered_products = implode("\n", $processed_lines);
}


/**
 * Prepare params before getting product data from cart
 *
 * @param string                           $hash             Unique product HASH
 * @param array<string, int|string|array>  $product          Product data
 * @param bool                             $skip_promotion   Skip promotion calculation
 * @param array<string, int|string|array>  $cart             Array of cart content and user information necessary for purchase
 * @param array<string, int|string|array>  $auth             Array with authorization data
 * @param int                              $promotion_amount Amount of product in promotion (like Free products, etc)
 * @param array<string, string>            $fields           SQL query fields
 * @param string                           $join             JOIN statement
 * @param array<string, array>             $params           Array of additional params
 */
//fn_set_hook('pre_get_cart_product_data', $hash, $product, $skip_promotion, $cart, $auth, $promotion_amount, $fields, $join, $params);
function fn_novarese_design_pre_get_cart_product_data($hash, $product, $skip_promotion, $cart, $auth, $promotion_amount, &$fields, $join, $params){

    //祝電花フラグも取得
    $fields[] = '?:products.congratulatory_flower';
    //祝電花会場IDも取得
    $fields[] = '?:products.congratulatory_flower_hall';

    //チケット商品フラグも取得
    $fields[] = '?:products.is_ticket';
}



/**
 * Executes when calculating cart content after products data is collected.
 * Allows to modify cart content and affect further processes like promotions or shipping calculation.
 *
 * @param array $cart                  Array of the cart contents and user information necessary for purchase
 * @param array $cart_products         Array of products in cart
 * @param array $auth                  Array of user authentication data (e.g. uid, usergroup_ids, etc.)
 * @param bool  $apply_cart_promotions Whether promotions have to be applied to cart content
 */
// fn_set_hook('calculate_cart_items', $cart, $cart_products, $auth, $apply_cart_promotions);
function fn_novarese_design_calculate_cart_items(&$cart, &$cart_products, &$auth, &$apply_cart_promotions){

    $_controller = Registry::get('runtime.controller');
    $_mode = Registry::get('runtime.mode');

    if($_controller === 'checkout' && $_mode === 'checkout') {
        
        //祝電花フラグ
        $is_congratulatory_flower = false;
        $hall_data = [];
        foreach($cart_products as $cp){

            //祝電花フラグをチェック
            if($cp['congratulatory_flower'] === 'Y'){
                $is_congratulatory_flower = true;

                $hall_id = $cp['congratulatory_flower_hall'];
                $hall_data = fn_novdg_get_wedding_hall($hall_id);

                //商品に対応する配送先をセットする


                // [s_country] => JP
                // [s_state] => 
                // [s_city] => HOGEHOGE
                // [s_zipcode] => 
                // [s_firstname] => 
                // [s_lastname] => 
                // [s_address] => 
                // [s_address_2] => 
                // [s_county] => 
                // [s_phone] => 
                // [s_address_type] => 
                // [s_country_descr] => Japan
                



                // $cart['user_data']['s_city'] = 'HOGEHOGE';



                // //祝電花の場合は、
                // fn_lcjp_dev_notify([
                //     // 'is_congratulatory_flower',
                //     // $cp['congratulatory_flower_hall'],
                //     // 'is_congratulatory_flower'
                //     $hall_data
                // ]);


                // Tygh::$app['view']->assign('congratulatory_flower', 'Y');
            }

        }

        //祝電花フラグをセット
        if($is_congratulatory_flower){
            $cart['is_congratulatory_flower'] = 'Y';
            $cart['hall_data'] = $hall_data;
        }else{
            unset($cart['is_congratulatory_flower']);
            unset($cart['hall_data']);
        }


        //チケット商品フラグをチェック
        $is_ticket = false;
        foreach($cart_products as $cp){
            if($cp['is_ticket'] === 'Y'){
                $is_ticket = true;
            }
        }

        //チケット商品フラグをセット
        if($is_ticket){
            $cart['is_ticket'] = 'Y';
        }else{
            unset($cart['is_ticket']);
        }

    }

}



//fn_set_hook('get_order_items_info_post', $order, $v, $k);
function fn_novarese_design_get_order_items_info_post(&$order, &$v, $k){
    
    $product_id = $v['product_id'];
    $novarese_values = db_get_row("SELECT congratulatory_flower, is_ticket FROM ?:products WHERE product_id = ?i", $product_id);
    
    $v['congratulatory_flower'] = $novarese_values['congratulatory_flower'];
    $v['is_ticket'] = $novarese_values['is_ticket'];

    $order['products'][$k]['congratulatory_flower'] = $novarese_values['congratulatory_flower'];
    $order['products'][$k]['is_ticket'] = $novarese_values['is_ticket'];
}


//fn_set_hook('update_product_pre', $product_data, $product_id, $lang_code, $can_update);
function fn_novarese_design_update_product_pre(&$product_data, $product_id, $lang_code, $can_update){

    // [congratulatory_flower] => N
    // [is_ticket] => Y
    //どちらかがYの場合、はis_edpをYにする
    if($product_data['congratulatory_flower'] === 'Y' || $product_data['is_ticket'] === 'Y'){
        $product_data['is_edp'] = 'Y';
    }

    //どちらもNの場合、はis_edpをNにする
    if($product_data['congratulatory_flower'] === 'N' && $product_data['is_ticket'] === 'N'){
        $product_data['is_edp'] = 'N';
    }
}


//////////////////


//?:wedding_hallsからデータを取得する
function fn_novdg_get_wedding_halls(){
    $result = db_get_array("SELECT * FROM ?:wedding_halls");
    return $result;
}

//$hall_idから会場データを取得する
function fn_novdg_get_wedding_hall($hall_id){
    $result = db_get_row("SELECT * FROM ?:wedding_halls WHERE hall_id = ?i", $hall_id);
    return $result;
}
