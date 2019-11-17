<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

//ログイン中のユーザーのカート情報を取得
$carts = get_user_carts($db, $user['user_id']);

$total_price = sum_carts($carts);

try {
  //トランザクション処理開始
  $db->beginTransaction();
  //購入処理（在庫数更新とカート情報消去）
  if(purchase_carts($db, $carts) === false){
    set_error('商品が購入できませんでした。');
    throw new PDOException();
  } 
  //購入履歴作成
  if(insert_order($db, $carts[0]['user_id'],$total_price) === false){
    set_error('採番エラー');
    throw new PDOException();
  } 
  //購入明細作成
  if(insert_detail($db, $carts) === false){
    set_error('明細作成エラー');
    throw new PDOException();
  } 
  $db->commit();
} catch (PDOException $e) {
  $db->rollBack();
  redirect_to(CART_URL);
}


include_once '../view/finish_view.php';