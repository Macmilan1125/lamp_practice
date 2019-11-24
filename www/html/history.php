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
//user_id の取得
$user = get_login_user($db);

//adminでログインしたときは全購入履歴を取得
//全ての購入履歴を表示させるのでユーザー名も表示させるとなお良し
if(is_admin($user) === true){
    $history = get_user_historys($db);
//ログイン中のユーザーの購入履歴を取得
}else{
    $history = get_user_history($db, $user['user_id']);
}

//viewの表示　　
include_once '../view/purchase_history_view.php';