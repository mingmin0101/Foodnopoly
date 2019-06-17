<?php
session_start();
include("pdoInc.php");     //PDO
?>

<?php

$failStr = '';
// 如果登入過，則直接轉到登入後頁面
if(isset($_SESSION['account']) && $_SESSION['account'] != null){ 
    echo '<meta http-equiv=REFRESH CONTENT=0;url=index.php>';
}
// 如果有輸入登入會員的兩個欄位
else if(isset($_POST['login_account']) && isset($_POST['login_password'])){  
    $acc = preg_replace("/[^A-Za-z0-9]/", "", $_POST['login_account']);
    $pwd = preg_replace("/[^A-Za-z0-9]/", "", $_POST['login_password']);
    if($acc != NULL && $pwd != NULL){
        $login = $dbh->prepare('SELECT * FROM member WHERE account = ?');
        $login->execute(array($acc));
        $row = $login->fetch(PDO::FETCH_ASSOC);
        // 比對密碼
        if($row['password'] == hash('sha256', $pwd)){
            $_SESSION['account'] = $row['account'];
            $_SESSION['nickname'] = $row['nickname'];
            $_SESSION['realname'] = $row['realname'];
            $_SESSION['email_addr'] = $row['email_addr'];
            $_SESSION['point'] = $row['point'];
            $_SESSION['member_id'] = $row['id'];

            echo '<meta http-equiv=REFRESH CONTENT=0;url=index.php>';  
        }
        else{$failStr = '帳號或密碼輸入錯誤';}
    }
}
//加入會員 新增一筆資料到member資料表
else if(isset($_POST['name']) && isset($_POST['nickname']) && isset($_POST['account']) && isset($_POST['password']) && isset($_POST['email'])){   
    //檢查帳號是否已經存在
    $check = $dbh->prepare('SELECT account FROM member WHERE account = ?');
    $check->execute(array($_POST['account']));
    if($check->rowCount() == 0){  //如果account在資料庫中撈不到紀錄
        $create = $dbh->prepare('INSERT INTO member (realname, nickname, account, password, email_addr) VALUES (?, ?, ?, ?, ?)');
        if($_POST['name'] != NULL && $_POST['nickname'] != NULL && $_POST['account'] != NULL && $_POST['password'] != NULL && $_POST['email'] != NULL){ //檢查是否有欄位空白
            $create->execute(array(
                    $_POST['name'],
                    $_POST['nickname'],
                    $_POST['account'],
                    hash('sha256',$_POST['password']),
                    $_POST['email'],
                ));
        } 
        // 挑選出member資料 撈取id
        $member = $dbh->prepare('SELECT * from member WHERE account = ?');  
        $member->execute(array($_POST['account']));
        $memberRow = $member->fetch(PDO::FETCH_ASSOC);

        // 首次加入會員 新增200點
        $newpointrecord = $dbh->prepare('INSERT INTO pointrecord (member_id, point_in, record) VALUES (?, ?, ?)');
        $record = "新加入會員禮";
        $newpointrecord->execute(array($memberRow['id'], 200, $record)); 

        // update 該會員的 total point
        $memberUpdatePoint = $dbh->prepare('UPDATE member SET point = 200 WHERE id = ?');
        $memberUpdatePoint->execute(array($memberRow["id"]));
        
        echo '<meta http-equiv=REFRESH CONTENT=0;url=member.php>';

    } else {
        $failStr = '帳號重複or輸入不正確，未成功註冊';
    } 
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        textarea{vertical-align:top}
    </style>
</head>
<body style="font-family: 微軟正黑體; background-image: url(pic/restaurant_background.png)">

<br><br><br><br>
<div class="container">
<div class="row">
  <div class="col-4"></div>
  <div class="card col-4" style="background-color: rgb(255,255,255,0.5);">

      <br>
      <!-- Nav tabs -->
      <ul class="nav nav-tabs justify-content-center nav-justified" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#login">登入會員</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#join">註冊會員</a>
        </li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div id="login" class="container tab-pane active"><br>
          <h3>登入會員</h3>
          <form action="member.php" method="post">
            帳號：<input type="text" name="login_account" class="form-control"><br>
            密碼：<input type="text" name="login_password" class="form-control"><br><br>
            <input type="submit" class="btn btn-warning">
          </form>    
        </div>

        <div id="join" class="container tab-pane fade"><br>
          <h3>註冊會員</h3>
          <form action="member.php" method="post">
            姓名：<input type="text" name="name" class="form-control"><br>
            綽號：<input type="text" name="nickname" class="form-control"><br>
            信箱：<input type="text" name="email" class="form-control"><br>
            帳號：<input type="text" name="account" class="form-control" placeholder="限大小寫英文字母及數字" pattern="[A-Za-z0-9]+" max='20'><br>
            密碼：<input type="text" name="password" class="form-control" placeholder="限大小寫英文字母及數字" pattern="[A-Za-z0-9]+"><br><br>
            <input type="submit" class="btn btn-warning">
          </form>
      </div>
    <?php echo $failStr;?>
  </div>
  <div class="col-4"></div>
</div>
  


  </div>
</div>

</body>
</html>