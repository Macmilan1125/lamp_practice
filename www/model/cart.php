<?php 
require_once 'functions.php';
require_once 'db.php';

function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
  ";
  return fetch_all_query($db, $sql);
}

function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
    AND
      items.item_id = {$item_id}
  ";

  return fetch_query($db, $sql);

}

function add_cart($db, $item_id, $user_id) {
  $cart = get_user_cart($db, $item_id, $user_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $item_id, $user_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES({$item_id}, {$user_id}, {$amount})
  ";

  return execute_query($db, $sql);
}

function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = {$amount}
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  return execute_query($db, $sql);
}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";

  return execute_query($db, $sql);
}

//カート内の商品の購入処理
function purchase_carts($db, $carts){
  //購入可能な商品かチェック
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  //各商品の在庫量更新処理
  foreach($carts as $cart){
    //DB更新処理に失敗した場合falseが戻ってくる
    if(update_item_stock(
        $db, 
        //対象ユーザー
        $cart['item_id'], 
        //更新値は（購入前の在庫量）-（購入数）
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
      return false;
    }
  }
  //該当ユーザーのカート情報を削除
  return delete_user_carts($db, $carts[0]['user_id']);
}

//カートDBから該当ユーザーの情報のみ削除
function delete_user_carts($db, $user_id){
  //sql文の作成
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";
  //sql文の実行
  return execute_query($db, $sql);
}

//購入合計額の計算
function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}


//各アイテムが購入処理可能か条件判定し論理値を返す
function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

//購入番号を新規作成
function insert_order($db, $user_id, $total_price){
  $sql = "
    INSERT INTO
      orders(
        user_id,
        created,
        total_price
      )
    VALUES(?,now(),?)
  ";
  $insert_order = array($user_id, $total_price);
  return execute_query($db, $sql,$insert_order);
}

//購入明細を新規作成
function insert_detail($db, $carts = array()){
  //最新のオートインクリメントIDの値を取得
  $order_id = $db->lastInsertId();
  foreach($carts as $cart){
    $sql = "
      INSERT INTO
        details(
          order_id,
          item_id,
          sale_price,
          number
        )
      VALUES(?, ?, ?, ?)
    ";
    //excute_queryに引数として渡す配列
    $params = array($order_id, $cart['item_id'],$cart['price'], $cart['amount']);
    $result = execute_query($db, $sql, $params);
    if($result === false){
      return false;
    }
  }
  return true;
}

