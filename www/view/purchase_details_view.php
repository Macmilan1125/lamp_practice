<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'admin.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細表示</h1>

  <div class="container">
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
 
    <?php if(count($detail) > 0){ ?>
        <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php print($order); ?></td>
            <td><?php print(esc($created)); ?> </td>
            <td><?php print($total_price); ?></td>        
          </tr>
        </tbody>
      </table>
      <h2>細目</h2>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>購入価格</th>
            <th>購入数量</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($detail as $dtl){ ?>
          <tr>
            <td><?php print(esc($dtl['name'])); ?></td>
            <td><?php print($dtl['sale_price']); ?> 円</td>
            <td><?php print($dtl['number']); ?></td>
            <td>
                <?php print($dtl['sale_price']*$dtl['number']); ?> 円
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入明細がありません。</p>
    <?php } ?> 
  </div>
</body>
</html>