<?php
//定数呼び出し
require_once '../conf/const.php';
//モデル呼び出し
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'historys.php';

session_start();

//ログイン情報のチェック
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//mysqlへ接続
$db = get_db_connect();
// 注文番号の取得
$order = get_post('order_id');
//該当注文の日時を取得
$created = get_post('created');
//該当注文の合計金額を取得
$total_price = get_post('total_price');


//購入履歴を取得
$detail = get_user_detail($db, $order);

//viewの表示　　
include_once '../view/purchase_details_view.php';