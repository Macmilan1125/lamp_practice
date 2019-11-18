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

    <?php if(count($history) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>合計金額</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($history as $his){ ?>
          <tr>
            <td><?php print($his['order_id']); ?></td>
            <td><?php print(esc($his['created'])); ?></td>
            <td><?php print($his['total_price']); ?>　円</td>
            <td>
              <form method="post" action="details.php">
                <input type="submit" value="購入明細表示" class="btn btn-danger delete">
                <input type="hidden" name="order_id" value="<?php print($his['order_id']); ?>">
                <input type="hidden" name="created" value="<?php print(esc($his['created'])); ?>">   
                <input type="hidden" name="total_price" value="<?php print($his['total_price']); ?>">                           
              </form>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入履歴はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>