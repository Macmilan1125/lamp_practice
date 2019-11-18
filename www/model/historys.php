<?php 
require_once 'functions.php';
require_once 'db.php';

function get_user_history($db, $user_id){
  $sql = "
    SELECT
      orders.order_id,
      orders.created,
      orders.total_price
    FROM
      orders
    WHERE
      orders.user_id = {$user_id}
  ";
  return fetch_all_query($db, $sql);
}

function get_user_historys($db){
    $sql = "
      SELECT
        orders.order_id,
        orders.created,
        orders.total_price
      FROM
        orders
    ";
    return fetch_all_query($db, $sql);
  }


function get_user_detail($db, $order){
    $sql = "
      SELECT
        details.order_id,
        details.item_id,
        details.sale_price,
        details.number,
        items.name
      FROM
        details
      JOIN
        items
      ON
        details.item_id = items.item_id
      WHERE
        order_id = {$order}
    ";
    return fetch_all_query($db, $sql);
  }
  
  